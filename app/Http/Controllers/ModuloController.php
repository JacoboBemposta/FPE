<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Support\Facades\Log;

class ModuloController extends Controller
{
    public function getUnidadesByModulo(Modulo $modulo)
    {
        try {
            $unidades = $modulo->unidades()
                ->orderBy('codigo')
                ->get()
                ->map(function($unidad) {
                    return [
                        'id' => $unidad->id,
                        'codigo' => $unidad->codigo,
                        'nombre' => $unidad->nombre,
                        'horas' => $unidad->horas,
                        'modulo_id' => $unidad->modulo_id
                    ];
                });

            return response()->json($unidades);
        } catch (\Exception $e) {
            Log::error('Error en getUnidadesByModulo: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar las unidades',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
