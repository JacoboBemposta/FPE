<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Traits\EnviaEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\CursoAcademico;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailBase;
use App\Models\AlumnoCurso;
use Illuminate\Support\Facades\Storage;




class ProfesorController extends Controller
{
    use EnviaEmails;

    public function index()
    {
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();

        // Pasar la variable a la vista
        return view('profesor.index', compact('familias_profesionales'));
    }

    public function cursos()
    {
        // Obtener todas las familias profesionales con sus cursos, módulos y unidades formativas asociadas
        $familias_profesionales = FamiliaProfesional::with('cursos.modulos.unidades')->get();
    
        // Obtener todos los cursos disponibles
        $cursosDisponibles = Curso::all();
    
        return view('cursos.index', compact('familias_profesionales', 'cursosDisponibles'));
    }
    
    
    public function misCursos()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Verificar si el usuario tiene el rol de academia
        if ($user->rol !== 'profesor') {
            return back()->withErrors(['error' => 'No tienes permisos para acceder a esta sección.']);
        }
    
        // Obtener los cursos académicos del usuario
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();
        
        return view('profesor.index', compact('misCursos'));
    }
    

    public function asignarCurso(Request $request, $curso_id)
    {

        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }
    
        // Verificar si el usuario tiene el rol de academia
        if ($user->rol !== 'profesor') {
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




public function verAcademias(Request $request)
{
    $perPage = $request->get('per_page', 10);

    $query = DB::table('users')
        ->join('curso_academicos', 'users.id', '=', 'curso_academicos.academia_id')
        ->join('cursos', 'curso_academicos.curso_id', '=', 'cursos.id')
        ->leftJoin('alumnos_curso', function($join) {
            $join->on('curso_academicos.id', '=', 'alumnos_curso.curso_academico_id')
                 ->where('alumnos_curso.es_profesor', 1);
        })
        ->where('users.rol', 'academia')
    
        // Filtrar por academia_nombre
        ->when($request->filled('academia_nombre'), function($q) use ($request) {
            $q->where('users.ident', 'like', '%' . strtolower(trim($request->academia_nombre)) . '%');
        })
        // Filtrar por curso_codigo
        ->when($request->filled('curso_codigo'), function($q) use ($request) {
            $q->where('cursos.codigo', 'like', '%' . strtolower(trim($request->curso_codigo)) . '%');
        })
        // Filtrar por curso_nombre
        ->when($request->filled('curso_nombre'), function($q) use ($request) {
            $q->where('cursos.nombre', 'like', '%' . strtolower(trim($request->curso_nombre)) . '%');
        })
        // Filtrar por municipio
        ->when($request->filled('municipio'), function($q) use ($request) {
            $q->where('curso_academicos.municipio', 'like', '%' . strtolower(trim($request->municipio)) . '%');
        })
        // Filtrar por provincia
        ->when($request->filled('provincia'), function($q) use ($request) {
            $q->where('curso_academicos.provincia', 'like', '%' . strtolower(trim($request->provincia)) . '%');
        })
        // Filtrar por docente asignado (con/sin)
        ->when($request->filled('docente_asignado') && $request->docente_asignado != 'todos', function($q) use ($request) {
            if ($request->docente_asignado == 'con') {
                $q->whereNotNull('alumnos_curso.nombre');
            } elseif ($request->docente_asignado == 'sin') {
                $q->whereNull('alumnos_curso.nombre');
            }
        })
        // Seleccionar columnas
        ->select([
            'users.id as academia_id',
            'users.ident as academia_nombre',
            'curso_academicos.id as curso_acad_id',
            'curso_academicos.municipio',
            'curso_academicos.provincia',
            'curso_academicos.inicio',
            'curso_academicos.fin',
            'cursos.nombre as curso_nombre',
            'cursos.codigo as curso_codigo',
            // Agregar el docente desde alumnos_curso
            'alumnos_curso.nombre as docente_nombre',
        ])
        ->orderBy('users.inicio_suscripcion', 'desc')
        ->orderBy('cursos.codigo');
    
    $cursosAcademicos = $query->paginate($perPage);

    return view('profesor.academias', compact('cursosAcademicos'));
}





public function obtenerEmailAcademia($academiaId)
    {
        try {
            // Verificar que el usuario es profesor
            if (Auth::user()->rol !== 'profesor') {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            // Obtener el email de la academia
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

        // Validar que el curso le pertenece al usuario (opcional pero recomendado)
        if ($curso->academia_id !== auth()->id()) {
            return back()->withErrors(['error' => 'No tienes permiso para eliminar este curso.']);
        }

        $curso->delete();

        return back()->with('success', 'Curso eliminado correctamente.');
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
        


        $user = Auth::user();
        $misCursos = CursoAcademico::where('academia_id', $user->id)->get();
  
   
        
        return view('profesor.index', compact('misCursos'));
    }



    public function docente()
    {
        return $this->hasOne(AlumnoCurso::class, 'curso_academico_id')
                    ->where('es_profesor', 1);
    }

    

    public function enviarCandidatura(Request $request)
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
            'contexto' => 'profesor_a_academia',
        ]);

        if ($exito) {
            return redirect()->back()->with('success', 'Candidatura enviada correctamente.');
        } else {
            return redirect()->back()->with('error', 'Error al enviar la candidatura.');
        }
    }
}
