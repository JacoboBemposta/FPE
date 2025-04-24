<?php


namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modulo;

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
            'horas' => 'nullable|integer',
            'cualificacion' => 'nullable|string|max:255',
            'familia_profesional_id' => 'required|exists:familias_profesionales,id'
        ]);
    
        // Sanitizar los inputs antes de guardar
        $cursoData = [
            'codigo' => htmlspecialchars(strip_tags($request->input('codigo'))),
            'nombre' => htmlspecialchars(strip_tags($request->input('nombre'))),
            'horas' => htmlspecialchars(strip_tags($request->input('horas'))),
            'cualificacion' => htmlspecialchars(strip_tags($request->input('cualificacion'))),
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
   
        // Aquí puedes cargar los módulos asociados al curso si es necesario
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
            'nombre' => 'required|string|max:255',
            'horas' => 'nullable|integer|min:0',
            'cualificacion' => 'nullable|string|max:255',
            'familia_profesional_id' => 'required|exists:familias_profesionales,id'
        ]);
        
        $curso->update($validated);
 
        // Sincronizar módulos
        $curso->modulos()->sync($request->modulos ?? []);
    
        return redirect()->back()->with('success', 'Curso actualizado correctamente');
    }
}
