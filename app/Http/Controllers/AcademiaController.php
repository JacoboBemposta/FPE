<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use App\Models\CursoAcademico;
use App\Models\AlumnoCurso;
use App\Models\User;
use App\Models\Calificacion;
use App\Models\DetalleCurso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Traits\EnviaEmails;


class AcademiaController extends Controller
{
    use EnviaEmails;
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->rol !== 'academia') {
            return back()->withErrors(['error' => 'No tienes permisos para acceder a esta sección.']);
        }
        
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
        
        // Obtener los cursos académicos del usuario (para la tabla)
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();
        
        // Pasar ambas variables a la vista
        return view('academia.index', compact('familias_profesionales', 'misCursos'));
    }


    public function misCursos()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Verificar si el usuario tiene el rol de academia
        if ($user->rol !== 'academia') {
            return back()->withErrors(['error' => 'No tienes permisos para acceder a esta sección.']);
        }
    
        // Obtener los cursos académicos del usuario
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();

        return view('academia.index', compact('misCursos'));
    }
        
    public function verDocentes(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = DB::table('users')
            ->join('curso_academicos', 'users.id', '=', 'curso_academicos.academia_id')
            ->join('cursos', 'curso_academicos.curso_id', '=', 'cursos.id')
            ->where('users.rol', 'profesor')
        
            // filtros opcionales
            ->when($request->filled('docente_nombre'), fn($q) =>
                $q->where('users.name', 'like', '%' . strtolower(trim($request->docente_nombre)) . '%')
            )
            ->when($request->filled('codigo'), fn($q) =>
                $q->where('cursos.codigo', 'like', '%' . strtolower(trim($request->codigo)) . '%')
            )
            ->when($request->filled('nombre'), fn($q) =>
                $q->where('cursos.nombre', 'like', '%' . strtolower(trim($request->nombre)) . '%')
            )
            ->when($request->filled('municipio'), fn($q) =>
                $q->where('curso_academicos.municipio', 'like', '%' . strtolower(trim($request->municipio)) . '%')
            )
            ->when($request->filled('provincia'), fn($q) =>
                $q->where('curso_academicos.provincia', 'like', '%' . strtolower(trim($request->provincia)) . '%')
            )
            ->select([
                'users.id as docente_id',
                'users.name as docente_nombre',
                'users.email as docente_email',
                'curso_academicos.id as curso_acad_id',
                'curso_academicos.municipio',
                'curso_academicos.provincia',
                'curso_academicos.inicio',
                'curso_academicos.fin',
                'cursos.nombre as curso_nombre',
                'cursos.codigo as curso_codigo',
            ])
            // Ordenar por fecha de suscripción más reciente primero
            // Nota: inicio_suscripcion existe, fin_suscripcion tiene typo (fin_suscrpcion)
            ->orderBy('users.inicio_suscripcion', 'desc')
            ->orderBy('users.name');
        
        $docentesConCursos = $query->paginate($perPage);

        return view('academia.docentes', compact('docentesConCursos'));
    }


    public function obtenerEmailDocente($docenteId)
    {
        try {
            // Verificar que el usuario es academia
            if (Auth::user()->rol !== 'academia') {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            // Obtener el email del docente
            $docente = DB::table('users')
                ->where('id', $docenteId)
                ->where('rol', 'profesor')
                ->select('email')
                ->first();

            if (!$docente) {
                return response()->json(['error' => 'Docente no encontrado'], 404);
            }

            return response()->json(['email' => $docente->email]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno'], 500);
        }
    }



    
    public function enviarMensajeDocente(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $exito = $this->enviarEmailRegistrado([
            'destinatario_email' => $request->email,
            'asunto' => $request->subject,
            'mensaje' => $request->message,
            'contexto' => 'academia_a_docente',
        ]);

        if ($exito) {
            return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
        } else {
            return redirect()->back()->with('error', 'Error al enviar el mensaje.');
        }
    }



    public function asignarCurso(Request $request, $curso_id)
    {
        $user = Auth::user();
        
        // Validación de usuario
        if (!$user || $user->rol !== 'academia') {
            return redirect()->route('login')->withErrors(['error' => 'Acceso no autorizado']);
        }
    
        // Verificar si el curso existe
        $curso = Curso::find($curso_id);
        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe']);
        }
    
        // Crear el curso académico con valores por defecto
        $cursoAcademico = CursoAcademico::create([
            'curso_id' => $curso->id, // Aquí se pasa correctamente el curso_id
            'academia_id' => $user->id,

        ]);
    
        // Vincular al usuario en la tabla pivote
        $user->cursosAcademicos()->attach($cursoAcademico->id, [

            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        return redirect()
               ->route('academia.miscursos')
               ->with('success', 'Curso asignado correctamente. Ahora puedes editar los detalles.');
    }
    
    public function destroyCursoAcademico($id)
    {
        $curso = CursoAcademico::findOrFail($id);
        $curso->delete();

        return redirect()->back()->with('success', 'Curso eliminado correctamente.');
    }

    public function cursos()
    {
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
    
        // Obtener todos los cursos disponibles
        $cursosDisponibles = Curso::all();
    
        return view('cursos.index', compact('familias_profesionales', 'cursosDisponibles'));
    }
    
    public function agregarDetallesCurso($cursoAcademicoId)
    {
        $cursoAcademico = CursoAcademico::findOrFail($cursoAcademicoId);
    
        // Iterar sobre los módulos del curso académico
        foreach ($cursoAcademico->curso->modulos as $modulo) {
            foreach ($modulo->unidades as $unidadFormativa) {
                DetalleCurso::create([
                    'curso_academico_id' => $cursoAcademico->id,
                    'unidad_formativa_id' => $unidadFormativa->id,
                    'codigo' => $unidadFormativa->codigo, // Suponiendo que las unidades formativas tienen un campo 'codigo'
                    'nombre' => $unidadFormativa->nombre, // Nombre de la unidad formativa
                    'inicio' => null, // Este valor lo podrás modificar más tarde
                    'fin' => null,    // Este valor lo podrás modificar más tarde
                    'calificacion' => null, // Este valor lo podrás modificar más tarde
                ]);
            }
        }
    
        return redirect()->route('cursoAcademico.detalles', $cursoAcademicoId);
    }

    public function verDetalles($id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        // Obtener los detalles del curso asociados con el curso académico
        $detalles = $cursoAcademico->detallesCurso;

        return view('academia.detalle_curso', compact('cursoAcademico', 'detalles'));
    }

    public function detalleCurso($id)
    {
        // Buscar el CursoAcademico por su ID con la relación de cursos, modulos y unidades
        $cursoAcademico = CursoAcademico::with([
            'curso.modulos.unidades', // si tienes relaciones anidadas
            'detallesCurso',
            'curso.FamiliaProfesional'
        ])->findOrFail($id);

        // Preparar los detalles, incluyendo los módulos para cada unidad formativa
        $detalles = [];
        foreach ($cursoAcademico->curso->modulos as $modulo) {
            foreach ($modulo->unidades as $unidad) {
                // Aquí se accede al módulo desde la unidad formativa
                $detalles[] = [
                    'unidad_formativa' => $unidad->nombre,
                    'codigo' => $unidad->codigo,
                    'inicio' => $unidad->inicio,  
                    'fin' => $unidad->fin,        
                    'Examen0' => $unidad->examen0, 
                    'ExamenF' => $unidad->examenF, 
                    'modulo' => $modulo->nombre,  // Módulo relacionado con la unidad
                ];
            }
        }


        return view('academia.detalle_curso', compact('cursoAcademico', 'detalles'));
    }

    public function guardarDetallesCurso(Request $request, $id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);
    
        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.inicio' => 'nullable|date',
            'detalles.*.fin' => 'nullable|date',
            'detalles.*.calificacion' => 'nullable|numeric|min:0|max:10',
            'detalles.*.modulo_id' => 'nullable|exists:modulos,id' // Nuevo campo para módulos
        ]);
    
        foreach ($request->detalles as $detalleData) {
            // Buscar o crear el detalle
            $detalle = DetalleCurso::updateOrCreate(
                [
                    'curso_academico_id' => $id,
                    'unidad_formativa_id' => $detalleData['unidad_formativa_id'] ?? null,
                    'modulo_id' => $detalleData['modulo_id'] ?? null
                ],
                [
                    'inicio' => $detalleData['inicio'],
                    'fin' => $detalleData['fin'],
                    'calificacion' => $detalleData['calificacion']
                ]
            );
        }
    
        return redirect()->back()->with('success', 'Detalles guardados correctamente.');
    }

    public function guardarNotaModulo(Request $request)
    {
        // Forzar el manejo como JSON
        if (!$request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta solo acepta solicitudes JSON',
                'received_data' => $request->all()
            ], 400);
        }
    
        try {
            $validated = $request->validate([
                'modulo_id' => 'required|exists:modulos,id',
                'alumno_curso_id' => 'required|exists:alumnos_curso,id',
                'curso_academico_id' => 'required|exists:curso_academicos,id',
                'nota' => 'required|numeric|min:0|max:10'
            ]);
    
            $calificacion = Calificacion::updateOrCreate(
                [
                    'alumno_curso_id' => $validated['alumno_curso_id'],
                    'modulo_id' => $validated['modulo_id'],
                    'unidad_formativa_id' => null
                ],
                [
                    'nota' => $validated['nota'],
                    'curso_academico_id' => $validated['curso_academico_id']
                ]
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Nota guardada correctamente',
                'data' => $calificacion
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarDetalleModulo(Request $request, $cursoId)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'campo' => 'required|in:inicio,fin,calificacion',
            'valor' => 'nullable'
        ]);
    
        $detalle = DetalleCurso::updateOrCreate(
            [
                'curso_academico_id' => $cursoId,
                'modulo_id' => $request->modulo_id,
                'unidad_formativa_id' => null
            ],
            [
                $request->campo => $request->valor
            ]
        );
    
        return response()->json([
            'success' => true,
            'detalle_id' => $detalle->id
        ]);
    }

    public function actualizarCurso(Request $request, $id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        // Validación de los campos del formulario
        $request->validate([
            'municipio' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'inicio' => 'nullable|date',
            'fin' => 'nullable|date',
        ]);

        // Actualización de los campos en el modelo
        $cursoAcademico->update([
            'municipio' => strip_tags($request->municipio),
            'provincia' => strip_tags($request->provincia),
            'inicio' => $request->inicio,
            'fin' => $request->fin,
        ]);

        // Redirigir de vuelta con mensaje de éxito
        return redirect()->route('academia.miscursos')->with('success', 'Curso actualizado correctamente.');
    }

    public function crearActualizarDetalle(Request $request)
    {
        $v = $request->validate([
            'detalle_id'            => 'nullable|exists:detalles_curso,id',
            'unidad_formativa_id'   => 'nullable|exists:unidades_formativas,id',
            'modulo_id'             => 'nullable|exists:modulos,id',
            'curso_academico_id'    => 'required|exists:curso_academicos,id',
            'campo'                 => 'required|in:inicio,fin,Examen0,ExamenF',
            'valor'                 => 'nullable|date',
        ]);
    
        // Usar solo estos dos campos como "where" para evitar duplicados
        $attributes = [
            'curso_academico_id'  => $v['curso_academico_id'],
            'unidad_formativa_id' => $v['unidad_formativa_id'] ?? null,
            'modulo_id'           => $v['modulo_id'] ?? null,
        ];
    
        // Y el campo dinámico en "values"
        $values = [
            $v['campo'] => $v['valor']
        ];
    
        $detalle = DetalleCurso::updateOrCreate($attributes, $values);
    
        return response()->json([
            'success' => true,
            'message' => 'Cambios guardados correctamente',
            'detalle' => $detalle->fresh(),
        ]);
    }
    
    public function getAlumnos($id)
    {
        // Obtén el curso académico por ID
        $cursoAcademico = CursoAcademico::find($id);
        
        // Verifica si existe el curso académico
        if (!$cursoAcademico) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
    
        // Obtén los alumnos relacionados con ese curso
        $alumnos = $cursoAcademico->alumnos;
    
        return response()->json($alumnos);
    }

    public function guardarAlumno(Request $request)
    {
          
        $dni = strip_tags($request->dni);
        $nombre = strip_tags($request->nombre);
        $email = strip_tags($request->email);
        $telefono = strip_tags($request->telefono);
        $es_profesor = filter_var($request->es_profesor, FILTER_VALIDATE_BOOLEAN);
        $es_profesor = $request->has('es_profesor') ? 1 : 0;

     
        // Validación de los datos del formulario
        $request->validate([
            'dni' => 'required|string|max:15',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'curso_academico_id' => 'required|exists:curso_academicos,id',
            'es_profesor' => 'nullable|boolean',
        ]);
  
       
        $cursoAcademicoId = $request->curso_academico_id;
  
        // Creación del nuevo alumno en la base de datos
        AlumnoCurso::create([
            'dni' => $dni,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'curso_academico_id' => $cursoAcademicoId,
            'es_profesor' => $es_profesor,
        ]);

        // Cargar el curso académico junto con sus relaciones usando el ID correcto
        $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
            ->findOrFail($cursoAcademicoId);
        
        // Retorna la vista con los detalles del curso
        return view('academia.detalle_curso', compact('cursoAcademico'));
    }

    public function actualizarAlumno(Request $request, $id)
    {
    
        // Limpiar los datos con strip_tags() para eliminar las etiquetas HTML
        $dni = strip_tags($request->dni);
        $nombre = strip_tags($request->nombre);
        $email = strip_tags($request->email);
        $telefono = strip_tags($request->telefono);
        $es_profesor = $request->es_profesor;
        // Validación de los datos (sin permitir HTML)
        $request->validate([
            'dni' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'es_profesor' => 'nullable|boolean',
        ]);

        // Verificar si alguna de las entradas tiene una longitud de solo espacios o está vacía después de eliminar las etiquetas HTML
        if (empty($dni) || empty($nombre) || empty($email)) {
            return redirect()->back()->withErrors(['error' => 'Los campos no pueden contener solo etiquetas HTML.'])->withInput();
        }

        // Intentar actualizar el alumno con los datos limpios
        try {
            $alumno = AlumnoCurso::findOrFail($id);
            $alumno->update([
                'dni' => $dni,
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'es_profesor' => $request->es_profesor,
            ]);
        } catch (\Exception $e) {
            // Si hay un error al actualizar, mostrar un mensaje adecuado
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al intentar actualizar el alumno.'])->withInput();
        }

        // Si todo salió bien, redirigir a la vista del curso actualizado
        $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
            ->findOrFail($request->curso_academico_id);

        return view('academia.detalle_curso', compact('cursoAcademico'));
    }

    public function mostrarDetallesCurso($id)
    {
        $cursoAcademico = CursoAcademico::with(['detallesCurso', 'curso.modulos.unidades'])
            ->findOrFail($id)
            ->refresh(); // Recarga el modelo y sus relaciones
        
        return view('tu_vista', compact('cursoAcademico'));
    }

    public function eliminarAlumno($id)
    {
        // Buscar el alumno por su ID
        $alumno = AlumnoCurso::find($id);

        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }

        // Obtener el ID del curso antes de eliminar el alumno
        $cursoAcademicoId = $alumno->curso_academico_id;

        // Eliminar el alumno
        $alumno->delete();
        
        // Cargar el curso académico junto con sus relaciones necesarias
        $cursoAcademico = CursoAcademico::with(['curso', 'curso.FamiliaProfesional', 'alumnos'])
            ->findOrFail($cursoAcademicoId);
        
        // Retornar la vista con los detalles del curso actualizado
        return view('academia.detalle_curso', compact('cursoAcademico'));
    }

    public function showCalificaciones($cursoAcademicoId)
    {
        $cursoAcademico = CursoAcademico::with([
            'curso', 
            'curso.modulos.unidades',
            'alumnos' => function($query) {
                $query->where('es_profesor', false)
                      ->with('calificaciones');
            }
        ])->findOrFail($cursoAcademicoId);
    
        // Verificar que los datos están disponibles
        if (!$cursoAcademico) {
            return abort(404, "Curso académico no encontrado");
        }
    
        return view('academia.calificaciones', compact('cursoAcademico'));
    }
    
    public function guardarCalificacion(Request $request)
    {
        // Suponiendo que se pasa el ID de alumno, unidad, y calificación
        $detalle = DetalleCurso::where('alumno_id', $request->alumno_id)
            ->where('unidad_formativa_id', $request->unidad_id)
            ->first();

        if ($detalle) {
            // Actualiza la calificación
            $detalle->Examen0 = $request->nota_examen0;
            $detalle->ExamenF = $request->nota_examenF;
            $detalle->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function alumnosRegulares()
    {
        return $this->alumnos()->whereHas('alumno', function($query) {
            $query->where('es_profesor', false);
        })->with('alumno');
    }



}
