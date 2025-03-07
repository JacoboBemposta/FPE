<?php

namespace App\Http\Controllers;

use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;

class FamiliaProfesionalController extends Controller
{
    // Mostrar todas las familias profesionales
    public function index()
    {
        $familias_profesionales = FamiliaProfesional::with('cursos')->get();
        return view('admin.index', compact('familias_profesionales'));
    }
    // Mostrar el formulario para crear una nueva familia profesional
    public function create()
    {
        return view('familias_profesionales.create');
    }

    // Almacenar una nueva familia profesional
    public function store(Request $request)
    {
        // Validación del formulario con el nuevo campo 'codigo'
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:100|unique:familias_profesionales,codigo', // Validar el código
        ]);
    
        // Eliminar cualquier etiqueta HTML del campo 'nombre'
        $nombre = strip_tags($request->nombre);
        $codigo = strip_tags($request->codigo);
    
        // Crear la nueva FamiliaProfesional con el nombre limpio y el código
        FamiliaProfesional::create([
            'nombre' => $nombre,
            'codigo' => $codigo, 
        ]);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.panel')->with('success', 'Familia profesional creada con éxito.');
    }
    
    
    // Mostrar una familia profesional específica
    public function show(FamiliaProfesional $familiaProfesional)
    {
        return view('familias_profesionales.show', compact('familiaProfesional'));
    }

    // Mostrar el formulario para editar una familia profesional existente
    public function edit(FamiliaProfesional $familiaProfesional)
    {
        return view('familias_profesionales.edit', compact('familiaProfesional'));
    }

    // Actualizar una familia profesional
    public function update(Request $request, FamiliaProfesional $familiaProfesional)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $nombre = strip_tags($request->nombre);

        $familiaProfesional->update([
            'nombre' => $nombre,
        ]);

        return redirect()->route('admin.panel')->with('success', 'Familia profesional actualizada con éxito.');
    }

    // Eliminar una familia profesional
    public function destroy($id)
    {
        try {
            $familia = FamiliaProfesional::findOrFail($id);
            $familia->delete();
    
            return redirect()->route('admin.panel')->with('success', 'Familia Profesional eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
