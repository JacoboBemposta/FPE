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
        $fecha = now(); // o now()->format('Y-m-d')

        $query = CursoAcademico::where(function($query) use ($fecha) {
            $query->whereDate('inicio', '>', $fecha)
                  ->orWhereNull('inicio');
        })
        ->whereDoesntHave('users', function($query) {
            $query->where('rol', 'profesor'); // Filtra solo por rol profesor
        })
        ->get();
        // Filtros
        if ($request->filled('academia')) {
            $query->whereHas('academia', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->academia . '%');
            });
        }
    
        if ($request->filled('codigo')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->codigo . '%');
            });
        }
    
        if ($request->filled('nombre_curso')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->nombre_curso . '%');
            });
        }
    
        if ($request->filled('provincia')) {
            $query->where('provincia', 'like', '%' . $request->provincia . '%');
        }
    
        if ($request->filled('municipio')) {
            $query->where('municipio', 'like', '%' . $request->municipio . '%');
        }
    
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

}
