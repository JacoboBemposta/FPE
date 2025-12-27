<?php

// app/Http/Controllers/UnidadFormativaController.php
namespace App\Http\Controllers;

use App\Models\UnidadFormativa;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    // Validar los campos básicos (sin unique en código)
    $validated = $request->validate([
        'modulo_id' => 'required|exists:modulos,id',
        'codigo' => 'required|string|max:20',
        'nombre' => 'required|string|max:255',
        'horas' => 'required|integer|min:1',
    ]);

    try {
        $modulo = Modulo::findOrFail($request->modulo_id);

        // Buscar unidad por código
        $unidadExistente = UnidadFormativa::where('codigo', $request->codigo)->first();

        if ($unidadExistente) {
            // Verificar si ya está asociada al módulo
            $yaAsociada = $modulo->unidades()->where('unidad_formativa_id', $unidadExistente->id)->exists();

            if ($yaAsociada) {
                return redirect()->back()->with('error', 'Esta unidad ya está asociada a este módulo.');
            }

            // Asociar la unidad existente al módulo
            $modulo->unidades()->attach($unidadExistente->id);

            return redirect()->back()->with('success', 'Unidad existente asociada al módulo correctamente.');
        }

        // Si no existe, crear la unidad (con el código único)
        // Aquí debemos asegurarnos de que el código sea único, pero como ya verificamos que no existe, podemos crearla.
        $unidadFormativa = UnidadFormativa::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'horas' => $request->horas,
        ]);

        // Asociar la nueva unidad al módulo
        $modulo->unidades()->attach($unidadFormativa->id);

        return redirect()->back()->with('success', 'Unidad creada y asociada al módulo correctamente.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
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
