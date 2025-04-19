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

class AcademiaController extends Controller
{
    public function index()
    {
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
        
        // Pasar la variable a la vista
        return view('academia.index', compact('familias_profesionales'));
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
        // Inicializamos la consulta de docentes con rol 'profesor'
        $docentesQuery = User::where('rol', 'profesor');
    
        // Aplicamos los filtros de búsqueda a la consulta de docentes, si se proporcionan
        if ($request->filled('codigo')) {
            $docentesQuery->whereHas('cursoAcademico.curso', function ($query) use ($request) {
                $query->where('codigo', 'like', '%' . $request->codigo . '%');
            });
        }
    
        if ($request->filled('nombre')) {
            $docentesQuery->whereHas('cursoAcademico.curso', function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->nombre . '%');
            });
        }
    
        if ($request->filled('municipio')) {
            $docentesQuery->whereHas('cursoAcademico', function ($query) use ($request) {
                $query->where('municipio', 'like', '%' . $request->municipio . '%');
            });
        }
    
        if ($request->filled('provincia')) {
            $docentesQuery->whereHas('cursoAcademico', function ($query) use ($request) {
                $query->where('provincia', 'like', '%' . $request->provincia . '%');
            });
        }
    
        if ($request->filled('docente_nombre')) {
            $docentesQuery->where('name', 'like', '%' . $request->docente_nombre . '%');
        }
    
        // Ejecutamos la consulta y obtenemos los docentes filtrados
        $docentes = $docentesQuery->get();
    
        // Creamos un array para almacenar los docentes y sus cursos
        $docentesConCursos = [];
    
        // Recorremos los docentes
        foreach ($docentes as $docente) {
            // Obtenemos los cursos académicos asociados a este docente
            $cursosDelDocente = $docente->cursoAcademico;
    
            // Si el docente tiene cursos asignados, los agregamos a la lista
            if ($cursosDelDocente->isNotEmpty()) {
                foreach ($cursosDelDocente as $cursoAcademico) {
                    // Cargamos la información del curso relacionado
                    $curso = $cursoAcademico->curso;
    
                    // Agregamos el docente y sus cursos asociados a la lista
                    $docentesConCursos[] = [
                        'docente' => $docente,
                        'curso' => $cursoAcademico, // Información del CursoAcademico
                        'curso_nombre' => $curso->nombre, // Nombre del curso
                        'curso_codigo' => $curso->codigo, // Código del curso
                    ];
                }
            }
        }
    
        // Pasamos los datos a la vista
        return view('academia.docentes', compact('docentesConCursos'));
    }
    
    

    public function asignarCurso(Request $request, $curso_id)
    {
        
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }
    
        // Verificar si el usuario tiene el rol de academia
        if ($user->rol !== 'academia') {
            return back()->withErrors(['error' => 'No tienes permisos para asignar cursos.']);
        }
    
        // Verificar si el curso existe
        $curso = Curso::find($curso_id);
        
        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe.']);
        }
    
        // Crear el curso académico
        $cursoAcademico = new CursoAcademico();
        $cursoAcademico->curso_id = $curso->id;
        $cursoAcademico->academia_id = $user->id; // Asocia el curso con el usuario academia
        $cursoAcademico->municipio = $request->input('municipio');
        $cursoAcademico->provincia = $request->input('provincia');
        $cursoAcademico->inicio = $request->input('inicio');
        $cursoAcademico->fin = $request->input('fin');
        $cursoAcademico->save();
    
        return back()->with('success', 'Curso académico asignado correctamente.');
    }
    
    public function asignarProfesor(Request $request, $cursoAcademico_id)
    {
        // Asegurarse de que el usuario está autenticado
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }
    
        // Verificar si el usuario tiene el rol de 'profesor'
        if ($user->rol !== 'profesor') {
            return back()->withErrors(['error' => 'No tienes permisos para asignarte a este curso.']);
        }
    
        // Verificar si el curso académico existe
        $cursoAcademico = CursoAcademico::find($cursoAcademico_id);
        
        if (!$cursoAcademico) {
            return back()->withErrors(['error' => 'El curso académico no existe.']);
        }
    
        // Verificar si el profesor ya está asignado a este curso
        if ($cursoAcademico->profesores->contains($user)) {
            return back()->withErrors(['error' => 'Ya estás asignado a este curso académico.']);
        }
    
        // Asignar el profesor al curso académico (relación N:M a través de la tabla user_curso)
        $cursoAcademico->profesores()->attach($user->id);
    
        return back()->with('success', 'Te has asignado correctamente al curso académico.');
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
        
        return view('academia.cursos', compact('familias_profesionales'));
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
        $cursoAcademico = CursoAcademico::with('curso.modulos.unidades')->findOrFail($id);

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

        // Validar los datos recibidos en el formulario
        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.inicio' => 'nullable|date',
            'detalles.*.fin' => 'nullable|date',
            'detalles.*.calificacion' => 'nullable|numeric|min:0|max:10', // Ajustar a tus necesidades
        ]);

        // Actualizar cada detalle de curso
        foreach ($request->detalles as $detalleId => $values) {
            $detalle = DetalleCurso::findOrFail($detalleId);
            $detalle->update([
                'inicio' => $values['inicio'],
                'fin' => $values['fin'],
                'calificacion' => $values['calificacion'],
            ]);
        }

        // Redirigir con éxito
        return redirect()->route('academia.detallesCurso', $cursoAcademico->id)->with('success', 'Detalles guardados correctamente.');
    }


    public function actualizarCurso(Request $request, $id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        // Validación de los campos del formulario
        $request->validate([
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
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
        


        $user = Auth::user();
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();
        $alumnos = AlumnoCurso::whereIn('curso_academico_id', $misCursos->pluck('id'))->get();
   
        
        return view('academia.index', compact('misCursos','alumnos'));
    }

    public function detallesCurso($id)
    {
        // Obtener el curso académico por ID
        $cursoAcademico = CursoAcademico::findOrFail($id);
        return view('academia.detalles', compact('cursoAcademico'));
    }
    public function guardarCursoAcademico(Request $request)
    {
        // Validamos que se reciba un curso académico válido
        $request->validate([
            'curso_academico_id' => 'required|exists:curso_academicos,id',  // Verificamos que el curso exista en la base de datos
        ]);

        // Guardamos la relación en la tabla alumnos_curso
        $alumnoCurso = new AlumnoCurso();
        $alumnoCurso->curso_academico_id = $request->curso_academico_id;  // Asignamos el curso académico
        $alumnoCurso->save();  // Guardamos la relación

        $user = Auth::user();
        $misCursos = $user->misCursos()->with(['curso', 'curso.FamiliaProfesional'])->get();
        $alumnos = AlumnoCurso::whereIn('curso_academico_id', $misCursos->pluck('id'))->get();

        
        return view('academia.index', compact('misCursos','alumnos'));
    }

    public function actualizarDetalle(Request $request)
    {
        $request->validate([
            'detalle_id' => 'required|exists:detalles_curso,id',
            'campo' => 'required|in:inicio,fin,Examen0,ExamenF',
            'valor' => 'nullable|date', // Ajusta según el tipo de dato, si es numérico, cambia la validación
        ]);
        
        $detalle = \App\Models\DetalleCurso::findOrFail($request->detalle_id);
        $campo = $request->campo;
        $detalle->{$campo} = $request->valor;
        $detalle->save();
    
        return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);
    }

    public function crearDetalle(Request $request)
    {
        $request->validate([
            'unidad_formativa_id' => 'required|exists:unidades_formativas,id', // <-- Aquí estaba el error
            'curso_academico_id' => 'required|exists:curso_academicos,id',
        ]);
    
        $detalle = \App\Models\DetalleCurso::create([
            'curso_academico_id' => $request->curso_academico_id,
            'unidad_formativa_id' => $request->unidad_formativa_id,
            'codigo' => '', 
            'nombre' => '', 
            'inicio' => null,
            'fin' => null,
            'Examen0' => null,
            'ExamenF' => null,
        ]);
    
        return response()->json(['success' => true, 'detalle_id' => $detalle->id]);
    }
    


    public function showCursoDetalles($cursoAcademicoId)
    {
        // Cargamos el curso académico con sus módulos, unidades y detalles
        $cursoAcademico = CursoAcademico::with([
            'curso.modulos.unidades', // Cargar módulos y unidades
            'detallesCurso',           // Cargar los detalles asociados
        ])->find($cursoAcademicoId);

        // Verificar si el curso académico existe
        if (!$cursoAcademico) {
            return redirect()->route('academia.miscursos')->with('error', 'Curso no encontrado');
        }

        // Pasar los datos del curso académico a la vista
        return view('academia.cursoDetalles', compact('cursoAcademico'));
    }


    public function guardarAlumno(Request $request)
    {
        $dni = strip_tags($request->dni);
        $nombre = strip_tags($request->nombre);
        $email = strip_tags($request->email);
        $telefono = strip_tags($request->telefono);
        
        // Validación de los datos del formulario
        $request->validate([
            'dni' => 'required|string|max:15',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'curso_academico_id' => 'required|exists:curso_academicos,id',
        ]);


        $cursoAcademicoId = $request->curso_academico_id;

        // Creación del nuevo alumno en la base de datos
        AlumnoCurso::create([
            'dni' => $dni,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'curso_academico_id' => $cursoAcademicoId,
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
    
        // Validación de los datos (sin permitir HTML)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
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
            'alumnos.calificaciones'
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

    public function updateCalificacion(Request $request, $calificacionId)
    {
        $calificacion = Calificacion::findOrFail($calificacionId);
        $calificacion->nota = $request->nota;
        $calificacion->save();
    
        return response()->json(['success' => true]);
    }
    
    public function storeCalificacion(Request $request)
    {
        dd($request->all());
        $request->validate([
            'alumno_id' => 'required|integer',
            'unidad_formativa_id' => 'required|integer',
            'nota' => 'required|numeric',
        ]);
    
        // Buscar si ya existe una calificación para este alumno y unidad formativa
        $calificacion = Calificacion::where('alumno_curso_id', $request->alumno_id)
                                      ->where('unidad_formativa_id', $request->unidad_formativa_id)
                                      ->first();
    
        if ($calificacion) {
            // Si la calificación existe, actualiza la nota
            $calificacion->nota = $request->nota;
            $calificacion->save();
        } else {
            // Si no existe, crea una nueva calificación
            $calificacion = new Calificacion();
            $calificacion->alumno_curso_id = $request->alumno_id;
            $calificacion->unidad_formativa_id = $request->unidad_formativa_id;
            $calificacion->nota = $request->nota;
            $calificacion->save();
        }
    
        return response()->json(['success' => true]);
    }
    

}
