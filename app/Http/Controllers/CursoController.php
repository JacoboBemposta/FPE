<?php


namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    public function index()
    {
        $familiasProfesionales = FamiliaProfesional::with(['cursos.modulos'])->get();
        $modulosDisponibles = Modulo::all();
        
        return view('admin.index', compact('familiasProfesionales', 'modulosDisponibles'));
    }

    public function create($familia_id = null)
    {
        $familias_profesionales = FamiliaProfesional::all();
        $familia_seleccionada = null;
    
        if ($familia_id) {
            $familia_seleccionada = FamiliaProfesional::findOrFail($familia_id);
        }
    
        return view('admin.cursos.create', compact('familias_profesionales', 'familia_seleccionada'));
    }
    
    public function store(Request $request)
    {

        $validated = $request->validate([
            'codigo' => 'required',
            'nombre' => 'required',
            'cualificacion' => 'required',
            'horas' => 'nullable|integer',
            'familia_profesional_id' => 'required|exists:familias_profesionales,id'
        ]);
    
        // Sanitizar los inputs antes de guardar
        $cursoData = [
            'codigo' => htmlspecialchars(strip_tags($request->input('codigo'))),
            'nombre' => htmlspecialchars(strip_tags($request->input('nombre'))),
            'cualificacion' => htmlspecialchars(strip_tags($request->input('cualificacion'))),
            'horas' => htmlspecialchars(strip_tags($request->input('horas'))),
            'familia_profesional_id' => $validated['familia_profesional_id'] // ✅ Correcto
        ];
        
    
        $curso = Curso::create($cursoData);

        // Asociar módulos si fueron seleccionados
        if ($request->has('modulos')) {
            $curso->modulos()->attach($request->modulos);
        }
    
        return redirect()->back()->with('success', 'Curso creado correctamente');
    }

    public function edit(Curso $curso)
    {
        $familiasProfesionales = FamiliaProfesional::all();
        return view('admin.cursos.edit', compact('curso', 'familiasProfesionales'));
    }
    
    public function destroy(Curso $curso)
    {
        try {
            $curso->delete();
            return redirect()->back()->with('success', 'Curso eliminado correctamente');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el curso'], 500);
        }
    }

    public function update(Request $request, Curso $curso)
    {
     
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:cursos,codigo,'.$curso->id,
            'nombre' => 'required|string|max:100',
            'cualificacion' => 'required|string|max:100',
            'horas' => 'nullable|integer|min:0',
            'familia_profesional_id' => 'required|exists:familias_profesionales,id'
        ]);
        
        $curso->update($validated);
 
        // Sincronizar módulos
        $curso->modulos()->sync($request->modulos ?? []);
    
        return redirect()->back()->with('success', 'Curso actualizado correctamente');
    }

    public function getModulosByCurso(Curso $curso)
    {
        try {
            $modulos = $curso->modulos()
                ->withCount('unidades')
                ->get()
                ->map(function($modulo) {
                    return [
                        'id' => $modulo->id,
                        'codigo' => $modulo->codigo,
                        'nombre' => $modulo->nombre,
                        'horas' => $modulo->horas,
                        'unidades_count' => $modulo->unidades_count,
                        'curso_id' => $modulo->curso_id
                    ];
                });

            return response()->json($modulos);
        } catch (\Exception $e) {
            Log::error('Error en getModulosByCurso: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar los módulos',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
