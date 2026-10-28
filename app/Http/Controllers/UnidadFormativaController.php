<?php

namespace App\Http\Controllers;

use App\Models\UnidadFormativa;
use App\Models\Modulo;
use Illuminate\Http\Request;

class UnidadFormativaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
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
                $yaAsociada = $modulo->unidades()->where('unidad_formativa_id', $unidadExistente->id)->exists();

                if ($yaAsociada) {
                    return redirect()->back()->with('error', 'Esta unidad ya está asociada a este módulo.');
                }

                $modulo->unidades()->attach($unidadExistente->id);

                return redirect()->back()->with('success', 'Unidad existente asociada al módulo correctamente.');
            }

            $unidadFormativa = UnidadFormativa::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'horas' => $request->horas,
            ]);

            $modulo->unidades()->attach($unidadFormativa->id);

            return redirect()->back()->with('success', 'Unidad creada y asociada al módulo correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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
