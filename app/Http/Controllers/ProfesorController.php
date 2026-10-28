<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\EnviaEmails;
use Illuminate\Support\Facades\Auth;
use App\Models\CursoAcademico;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;
use App\Helpers\SistemaHelper;

class ProfesorController extends Controller
{
    use EnviaEmails;

    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->rol !== 'profesor') {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Obtener cursos académicos del profesor (usuario actual)
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();

        // Verificar suscripción usando los campos en la tabla users
        $tieneSuscripcionValida = $user->tieneSuscripcionActiva();
        $finSuscripcion = $user->fin_suscripcion;

        // Sistema de suscripciones activo
        $sistema_suscripciones_activo = SistemaHelper::sistemaSuscripcionesActivo();

        return view('profesor.index', [
            'misCursos' => $misCursos,
            'tieneSuscripcionValida' => $tieneSuscripcionValida,
            'finSuscripcion' => $finSuscripcion,
            'sistema_suscripciones_activo' => $sistema_suscripciones_activo,
            'user' => $user
        ]);
    }

    public function cursos()
    {
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
        $cursosDisponibles = Curso::all();

        return view('cursos.index', compact('familias_profesionales', 'cursosDisponibles'));
    }

    public function misCursos()
    {
        return $this->index();
    }

    public function asignarCurso(Request $request, $curso_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        if ($user->rol !== 'profesor') {
            return back()->withErrors(['error' => 'No tienes permisos para asignar cursos.']);
        }

        $curso = Curso::find($curso_id);

        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe.']);
        }

        $cursoAcademico = new CursoAcademico();
        $cursoAcademico->curso_id = $curso->id;
        $cursoAcademico->academia_id = $user->id;
        $cursoAcademico->municipio = $request->input('municipio');
        $cursoAcademico->provincia = $request->input('provincia');
        $cursoAcademico->inicio = $request->input('inicio');
        $cursoAcademico->fin = $request->input('fin');
        $cursoAcademico->save();

        return back()->with('success', 'Curso académico asignado correctamente.');
    }

    public function verAcademias(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $userId = Auth::id();

        $query = DB::table('users')
            ->join('curso_academicos', 'users.id', '=', 'curso_academicos.academia_id')
            ->leftJoin('cursos', 'curso_academicos.curso_id', '=', 'cursos.id')
            ->leftJoin('alumnos_curso', function($join) {
                $join->on('curso_academicos.id', '=', 'alumnos_curso.curso_academico_id')
                    ->where('alumnos_curso.es_profesor', 1);
            })
            ->where('users.rol', 'academia')
            ->where('curso_academicos.archivado', false)
            ->where(function($q) {
                $q->whereDate('curso_academicos.inicio', '>', now()->toDateString())
                ->orWhereNull('curso_academicos.inicio');
            })
            ->when($request->filled('curso_codigo'), function($q) use ($request) {
                $q->where('cursos.codigo', 'like', '%' . strtolower(trim($request->curso_codigo)) . '%');
            })
            ->when($request->filled('curso_nombre'), function($q) use ($request) {
                $q->where('cursos.nombre', 'like', '%' . strtolower(trim($request->curso_nombre)) . '%');
            })
            ->when($request->filled('nivel_cualificacion') && $request->nivel_cualificacion != 'todos', function($q) use ($request) {
                $q->whereNotNull('cursos.cualificacion')
                ->where(function($query) use ($request) {
                    $query->where('cursos.cualificacion', 'LIKE', '%' . $request->nivel_cualificacion);
                });
            })
            ->when($request->filled('municipio'), function($q) use ($request) {
                $q->where('curso_academicos.municipio', 'like', '%' . strtolower(trim($request->municipio)) . '%');
            })
            ->when($request->filled('provincia'), function($q) use ($request) {
                $q->where('curso_academicos.provincia', 'like', '%' . strtolower(trim($request->provincia)) . '%');
            })
            ->when($request->filled('docente_asignado') && $request->docente_asignado != 'todos', function($q) use ($request) {
                if ($request->docente_asignado == 'con') {
                    $q->whereNotNull('alumnos_curso.nombre');
                } elseif ($request->docente_asignado == 'sin') {
                    $q->whereNull('alumnos_curso.nombre');
                }
            });

        $query->select([
                'users.id as academia_id',
                'users.name as academia_nombre',
                'curso_academicos.id as curso_acad_id',
                'curso_academicos.municipio',
                'curso_academicos.provincia',
                'curso_academicos.inicio',
                'curso_academicos.fin',
                'cursos.nombre as curso_nombre',
                'cursos.codigo as curso_codigo',
                'cursos.cualificacion',
                'alumnos_curso.nombre as docente_nombre',
            ])
            ->addSelect(DB::raw('EXISTS(
                SELECT 1 FROM emails_enviados 
                WHERE emails_enviados.curso_id = curso_academicos.id 
                AND emails_enviados.remitente_id = ' . (int) $userId . '
                AND emails_enviados.contexto = "profesor_a_academia"
            ) as ya_enviado_cv'))
            ->orderBy('curso_academicos.inicio', 'desc')
            ->orderBy('cursos.codigo');

        $cursosAcademicos = $query->paginate($perPage)->appends($request->except('page'));
        $sistema_suscripciones_activo = SistemaHelper::sistemaSuscripcionesActivo();
        $user = Auth::user();

        return view('profesor.academias', [
            'cursosAcademicos' => $cursosAcademicos,
            'sistema_suscripciones_activo' => $sistema_suscripciones_activo,
            'user' => $user
        ]);
    }

    public function obtenerEmailAcademia($academiaId)
    {
        try {
            if (Auth::user()->rol !== 'profesor') {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $academia = DB::table('users')
                ->where('id', $academiaId)
                ->where('rol', 'academia')
                ->select('email')
                ->first();

            if (!$academia) {
                return response()->json(['error' => 'Academia no encontrada'], 404);
            }

            return response()->json(['email' => $academia->email]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function destroy($id)
    {
        $curso = CursoAcademico::find($id);

        if (!$curso) {
            return back()->withErrors(['error' => 'Curso no encontrado.']);
        }

        if ($curso->academia_id !== Auth::id()) {
            return back()->withErrors(['error' => 'No tienes permiso para eliminar este curso.']);
        }

        $curso->delete();

        return back()->with('success', 'Curso eliminado correctamente.');
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

        return redirect()->route('profesor.miscursos')->with('success', 'Curso actualizado correctamente.');
    }

    public function enviarCandidatura(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'curso_acad_id' => 'required|integer|exists:curso_academicos,id',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $exito = $this->enviarEmailRegistrado([
            'destinatario_email' => $request->email,
            'asunto' => $request->subject,
            'mensaje' => $request->message,
            'contexto' => 'profesor_a_academia',
            'curso_id' => $request->curso_acad_id,
        ]);

        if ($exito) {
            return redirect()->back()->with('success', 'Candidatura enviada correctamente.');
        } else {
            return redirect()->back()->with('error', 'Error al enviar la candidatura.');
        }
    }
}
