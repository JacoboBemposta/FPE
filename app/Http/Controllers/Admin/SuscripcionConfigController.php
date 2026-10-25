<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SuscripcionConfigController extends Controller
{
    public function toggleSuscripciones(Request $request)
    {
        $request->validate([
            'activo' => 'required|boolean'
        ]);
        
        try {
            DB::table('configuraciones_sistema')
                ->where('clave', 'sistema_suscripciones_activo')
                ->update([
                    'valor' => $request->activo ? '1' : '0',
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sistema ' . ($request->activo ? 'activado' : 'desactivado'),
                'activo' => $request->activo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getEstado()
    {
        try {
            $valor = DB::table('configuraciones_sistema')
                ->where('clave', 'sistema_suscripciones_activo')
                ->value('valor');
            
            return response()->json([
                'activo' => $valor === '1'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'activo' => false
            ]);
        }
    }
}