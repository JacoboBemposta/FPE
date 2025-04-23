<?php

// app/Http/Controllers/UnidadFormativaController.php
namespace App\Http\Controllers;

use App\Models\UnidadFormativa;
use App\Models\Modulo;
use Illuminate\Http\Request;

class UnidadFormativaController extends Controller
{
    public function index($modulo_id)
    {
        // Obtiene todas las unidades formativas asociadas a un módulo específico
        $modulo = Modulo::with('unidades')->findOrFail($modulo_id);
        return view('admin.unidades.index', compact('modulo'));
    }

    public function create($modulo_id)
    {
        $modulo = Modulo::findOrFail($modulo_id);
        return view('admin.unidades.create', compact('modulo'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'modulo_id' => 'required|integer|exists:modulos,id',
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'horas' => 'required|integer|min:1',
        ]);
    
        // Buscar si ya existe una unidad con ese código
        $unidad = UnidadFormativa::where('codigo', $validated['codigo'])->first();
    
        if ($unidad) {
            // Ya existe, solo la vinculamos al módulo si no está ya
            if (!$unidad->modulos()->where('modulo_id', $validated['modulo_id'])->exists()) {
                $unidad->modulos()->attach($validated['modulo_id']);
            }
    
            return redirect()->back()->with('success', 'Unidad existente vinculada al módulo.');
        }
    
        // No existe, la creamos y la vinculamos
        $unidad = UnidadFormativa::create([
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'horas' => $validated['horas'],
        ]);
    
        $unidad->modulos()->attach($validated['modulo_id']);
    
        return redirect()->back()->with('success', 'Unidad creada y vinculada correctamente.');
    }
    
    public function edit($modulo_id, $id)
    {
        $modulo = Modulo::findOrFail($modulo_id);
        $unidad = UnidadFormativa::findOrFail($id);
        return view('admin.unidades.edit', compact('modulo', 'unidad'));
    }

    public function update(Request $request, $modulo_id, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $nombre=strip_tags($request->nombre);
        $descripcion=strip_tags($request->descripcion);

        $unidad = UnidadFormativa::findOrFail($id);
        $unidad->update([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ]);

        return redirect()->route('modulos.unidades.index', $modulo_id)->with('success', 'Unidad formativa actualizada con éxito.');
    }

    public function destroy($id)
    {
        try {
            $unidad = UnidadFormativa::findOrFail($id);
            $unidad->delete();
    
            return redirect()->route('admin.panel')->with('success', 'Unidad Formativa eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
