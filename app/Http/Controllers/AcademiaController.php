<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use App\Models\CursoAcademico;
use App\Models\AlumnoCurso;
use App\Models\User;
use App\Models\DetalleCurso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\EnviaEmails;
use App\Helpers\SistemaHelper;

class AcademiaController extends Controller
{
    use EnviaEmails;

    public function index()
    {
        return $this->misCursos();
    }

    public function misCursos()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->rol !== 'academia') {
            return back()->withErrors([
                'error' => 'No tienes permisos para acceder a esta sección.'
            ]);
        }

        $misCursos = CursoAcademico::where('academia_id', Auth::id())
            ->where('archivado', false)
            ->with('curso')
            ->orderBy('inicio', 'desc')
            ->get();

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
            ->when($request->filled('docente_nombre'), function($q) use ($request) {
                $search = trim($request->docente_nombre);
                $q->whereRaw("MATCH(users.name) AGAINST(? IN BOOLEAN MODE)", ["*{$search}*"]);
            })
            ->when($request->filled('nombre'), function($q) use ($request) {
                $search = trim($request->nombre);
                $q->whereRaw("MATCH(cursos.nombre) AGAINST(? IN BOOLEAN MODE)", ["*{$search}*"]);
            })
            ->when($request->filled('codigo'), function($q) use ($request) {
                $search = trim($request->codigo);
                $q->whereRaw("MATCH(cursos.codigo) AGAINST(? IN BOOLEAN MODE)", ["*{$search}*"]);
            })
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
            ->orderBy('cursos.nombre')
            ->orderBy('users.inicio_suscripcion', 'desc')
            ->orderBy('users.name');

        $docentesConCursos = $query->paginate($perPage);
        $sistema_suscripciones_activo = SistemaHelper::sistemaSuscripcionesActivo();

        return view('academia.docentes', [
            'docentesConCursos' => $docentesConCursos,
            'sistema_suscripciones_activo' => $sistema_suscripciones_activo,
            'user' => Auth::user()
        ]);
    }

    public function obtenerEmailDocente($docenteId)
    {
        try {
            if (Auth::user()->rol !== 'academia') {
                return response()->json(['error' => 'No autorizado'], 403);
            }

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

        if (!$user || $user->rol !== 'academia') {
            return redirect()->route('login')->withErrors(['error' => 'Acceso no autorizado']);
        }

        $curso = Curso::find($curso_id);
        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe']);
        }

        $cursoAcademico = CursoAcademico::create([
            'curso_id' => $curso->id,
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
        if ($curso->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este curso.');
        }
        $curso->delete();

        return redirect()->back()->with('success', 'Curso eliminado correctamente.');
    }

    public function cursos()
    {
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
        $cursosDisponibles = Curso::all();

        return view('cursos.index', compact('familias_profesionales', 'cursosDisponibles'));
    }

    public function detalleCurso($id)
    {
        $cursoAcademico = CursoAcademico::with([
            'curso.modulos.unidades',
            'detallesCurso',
            'curso.FamiliaProfesional',
            'alumnos'
        ])->findOrFail($id);

        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para acceder a este curso.');
        }

        return view('academia.detalle_curso', compact('cursoAcademico'));
    }

    public function actualizarCurso(Request $request, $id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);
        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para modificar este curso.');
        }

        $request->validate([
            'municipio' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'inicio' => 'nullable|date',
            'fin' => 'nullable|date',
        ]);

        $cursoAcademico->update([
            'municipio' => strip_tags($request->municipio),
            'provincia' => strip_tags($request->provincia),
            'inicio' => $request->inicio,
            'fin' => $request->fin,
        ]);

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

        $cursoAcademico = CursoAcademico::findOrFail($v['curso_academico_id']);
        if ($cursoAcademico->academia_id != Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $attributes = [
            'curso_academico_id'  => $v['curso_academico_id'],
            'unidad_formativa_id' => $v['unidad_formativa_id'] ?? null,
            'modulo_id'           => $v['modulo_id'] ?? null,
        ];

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

    public function guardarAlumno(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|max:15',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'curso_academico_id' => 'required|exists:curso_academicos,id',
            'es_profesor' => 'nullable|boolean',
        ]);

        $cursoAcademico = CursoAcademico::findOrFail($request->curso_academico_id);
        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para añadir participantes a este curso.');
        }

        $dni = strip_tags($request->dni);
        $nombre = strip_tags($request->nombre);
        $email = strip_tags($request->email);
        $telefono = strip_tags((string) $request->telefono);
        $es_profesor = $request->has('es_profesor') ? 1 : 0;

        AlumnoCurso::create([
            'dni' => $dni,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'curso_academico_id' => $cursoAcademico->id,
            'es_profesor' => $es_profesor,
        ]);

        return redirect()
            ->route('academia.detalleCurso', $cursoAcademico->id)
            ->with('success', 'Participante guardado correctamente.');
    }

    public function actualizarAlumno(Request $request, $id)
    {
        $dni = strip_tags($request->dni);
        $nombre = strip_tags($request->nombre);
        $email = strip_tags($request->email);
        $telefono = strip_tags((string) $request->telefono);
        $es_profesor = $request->has('es_profesor') ? 1 : 0;

        $request->validate([
            'dni' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'es_profesor' => 'nullable|boolean',
        ]);

        if (empty($dni) || empty($nombre) || empty($email)) {
            return redirect()->back()->withErrors(['error' => 'Los campos no pueden contener solo etiquetas HTML.'])->withInput();
        }

        $alumno = AlumnoCurso::findOrFail($id);
        $cursoAcademico = CursoAcademico::findOrFail($alumno->curso_academico_id);
        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para modificar este participante.');
        }

        try {
            $alumno->update([
                'dni' => $dni,
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'es_profesor' => $es_profesor,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al intentar actualizar el alumno.'])->withInput();
        }

        return redirect()
            ->route('academia.detalleCurso', $cursoAcademico->id)
            ->with('success', 'Participante actualizado correctamente.');
    }

    public function eliminarAlumno($id)
    {
        $alumno = AlumnoCurso::findOrFail($id);
        $cursoAcademico = CursoAcademico::findOrFail($alumno->curso_academico_id);

        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este participante.');
        }

        $alumno->delete();

        return redirect()
            ->route('academia.detalleCurso', $cursoAcademico->id)
            ->with('success', 'Participante eliminado correctamente.');
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

        if ($cursoAcademico->academia_id != Auth::id()) {
            abort(403, 'No tienes permiso para ver las calificaciones de este curso.');
        }

        return view('academia.calificaciones', compact('cursoAcademico'));
    }

    // Método para mostrar cursos archivados
    public function cursosArchivados()
    {
        $cursosArchivados = CursoAcademico::where('academia_id', Auth::id())
            ->where('archivado', true)
            ->with(['curso', 'alumnos' => function($query) {
                $query->where('es_profesor', 1);
            }])
            ->orderBy('archivado_en', 'desc')
            ->get();

        return view('academia.cursos_archivados', compact('cursosArchivados'));
    }

    // Método para archivar un curso
    public function archive($id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        if ($cursoAcademico->academia_id != Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para archivar este curso.');
        }

        $cursoAcademico->archivado = true;
        $cursoAcademico->archivado_en = now();
        $cursoAcademico->save();

        return redirect()->back()->with('success', 'Curso archivado correctamente.');
    }

    // Método para restaurar un curso
    public function restore($id)
    {
        $cursoAcademico = CursoAcademico::findOrFail($id);

        if ($cursoAcademico->academia_id != Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para restaurar este curso.');
        }

        $cursoAcademico->archivado = false;
        $cursoAcademico->archivado_en = null;
        $cursoAcademico->save();

        return redirect()->back()->with('success', 'Curso restaurado correctamente.');
    }
}
