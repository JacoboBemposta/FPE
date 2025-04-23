<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\CursoAcademico;
use App\Models\FamiliaProfesional;
use App\Models\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfesorController extends Controller
{
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
        $query = DB::table('users')
            ->join('curso_academicos', 'users.id', '=', 'curso_academicos.academia_id')
            ->join('cursos', 'curso_academicos.curso_id', '=', 'cursos.id')
            ->where('users.rol', 'academia')
    
            // Filtrar por academia_nombre
            ->when($request->filled('academia_nombre'), fn($q) =>
                $q->where('users.ident', 'like', '%' . strtolower(trim($request->academia_nombre)) . '%')
            )
            // Filtrar por curso_codigo
            ->when($request->filled('curso_codigo'), fn($q) =>
                $q->where('cursos.codigo', 'like', '%' . strtolower(trim($request->curso_codigo)) . '%')
            )
            // Filtrar por curso_nombre
            ->when($request->filled('curso_nombre'), fn($q) =>
                $q->where('cursos.nombre', 'like', '%' . strtolower(trim($request->curso_nombre)) . '%')
            )
            // Filtrar por municipio
            ->when($request->filled('municipio'), fn($q) =>
                $q->where('curso_academicos.municipio', 'like', '%' . strtolower(trim($request->municipio)) . '%')
            )
            // Filtrar por provincia
            ->when($request->filled('provincia'), fn($q) =>
                $q->where('curso_academicos.provincia', 'like', '%' . strtolower(trim($request->provincia)) . '%')
            )
            ->when($request->filled('tiene_docente'), function ($q) use ($request) {
                if ($request->tiene_docente == "1") {
                    $q->whereExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                            ->from('alumnos_curso')
                            ->whereRaw('alumnos_curso.curso_academico_id = curso_academicos.id')
                            ->where('es_profesor', 1);
                    });
                } elseif ($request->tiene_docente == "0") {
                    $q->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                            ->from('alumnos_curso')
                            ->whereRaw('alumnos_curso.curso_academico_id = curso_academicos.id')
                            ->where('es_profesor', 1);
                    });
                }
            })
            // Seleccionar columnas
            ->select([
                'users.id as academia_id',
                'users.ident as academia_nombre',
                'users.email',
                'users.telefono',
                'curso_academicos.id as curso_acad_id',
                'curso_academicos.municipio',
                'curso_academicos.provincia',
                'curso_academicos.inicio',
                'curso_academicos.fin',
                'cursos.nombre as curso_nombre',
                'cursos.codigo as curso_codigo',
            
                // Subconsulta para obtener el nombre del docente
                DB::raw("(
                    SELECT nombre
                    FROM alumnos_curso
                    WHERE curso_academico_id = curso_academicos.id
                    AND es_profesor = 1
                    LIMIT 1
                ) as docente_nombre")
            ])
            ->orderBy('cursos.codigo');
    
        $cursosAcademicos = $query->get();
    
        return view('profesor.academias', compact('cursosAcademicos'));
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

}
