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
        
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'codigo' => 'required|string|max:20|unique:unidades_formativas',
            'nombre' => 'required|string|max:255',
            'horas' => 'required|integer|min:1',
        ]);
    
        $modulo_id=strip_tags($request->modulo_id);
        $codigo=strip_tags($request->codigo);
        $nombre=strip_tags($request->nombre);
        $horas=strip_tags($request->horas);
        // Crear la unidad formativa
        UnidadFormativa::create([
            'modulo_id' => $modulo_id,
            'codigo' => $codigo,
            'nombre' => $nombre,
            'horas' => $horas,
        ]);
        
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.panel')->with('success', 'Unidad formativa creada con éxito.');

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
