<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\FamiliaProfesional;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        // Obtener los cursos que ya están asignados a la academia del usuario autenticado
        $cursosAsignados = CursoAcademico::where('academia_id', auth()->user()->academia_id)
                                        ->pluck('curso_id')->toArray();
    
        // Obtener solo los cursos que NO están asignados
        $cursosDisponibles = Curso::whereNotIn('id', $cursosAsignados)->get();
    
        return view('cursos.index', compact('cursosDisponibles'));
    }
    
    public function create()
    {
        $familias_profesionales = FamiliaProfesional::all(); // Obtener todas las familias profesionales
        return view('admin.curso.create', compact('familias_profesionales'));
    }

    // Método para guardar un curso
    public function store(Request $request)
    {
        try {
            // Verifica qué datos llegan al backend
            //dd($request->all()); // Verifica los datos que están llegando al controlador
   
            // Validación
            $request->validate([
                'familias_profesionales_id' => 'required|exists:familias_profesionales,id',
                'codigo' => 'required|unique:cursos,codigo',
                'nombre' => 'required|max:255',
                'horas' => 'required|integer|min:1|max:1000',
            ]);
            //dd($request);
            // Crear el curso
            Curso::create([
                'familias_profesionales_id' => $request->familias_profesionales_id,
                'codigo' =>strip_tags($request->codigo),
                'nombre' => strip_tags($request->nombre),
                'horas' => strip_tags($request->horas),
            ]);
            
            // Redirigir a alguna página (en este caso al panel de admin)
            return redirect()->route('admin.panel')->with('success', 'Curso creado exitosamente');
        } catch (\Exception $e) {
            // Si ocurre un error, lo registramos
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $curso = Curso::findOrFail($id);
            $curso->delete();

            return redirect()->route('admin.panel')->with('success', 'Curso eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}