<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class CursoModuloController extends Controller
{
// CursoModuloController.php
public function destroy($cursoId, $moduloId)
{

    
    $curso = Curso::findOrFail($cursoId);
    $modulo = Modulo::findOrFail($moduloId);
    
    // Eliminar la relación en la tabla intermedia
    $curso->modulos()->detach($moduloId);
    
    return redirect()->back()->with('success', 'Modulo eliminado correctamente');
}
    public function store(Request $request, Curso $curso)
    {
        
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'horas' => 'nullable|numeric|min:1|max:1000'
        ]);
    
        try {
           
            $codigo = htmlspecialchars(strip_tags($request->codigo));
            $nombre = htmlspecialchars(strip_tags($request->nombre));
            $horas = $request->horas ? (int)htmlspecialchars(strip_tags($request->horas)) : null;
    
            // Buscar módulo existente (por código exacto)
            $moduloExistente = Modulo::where('codigo', $codigo)->first();
    
            if ($moduloExistente) {
                // Verificar si ya está vinculado al curso actual
                $yaVinculado = $curso->modulos()
                    ->where('modulo_id', $moduloExistente->id)
                    ->exists();
    
                if ($yaVinculado) {
                    return redirect()->back()->with('error', 'Este módulo ya está vinculado a este curso');

                }
    
                // Vincular módulo existente al curso actual
                $curso->modulos()->attach($moduloExistente->id);
                
                return redirect()->back()->with('success', 'Módulo vinculado al curso correctamente');
            }
    
            // Validar unicidad solo para nuevos módulos
            $request->validate([
                'codigo' => 'unique:modulos,codigo'
            ]);
    
            // Crear nuevo módulo
            $modulo = Modulo::create([
                'codigo' => $codigo,
                'nombre' => $nombre,
                'horas' => $horas,
            ]);
    
            // Vincular nuevo módulo al curso
            $curso->modulos()->attach($modulo->id);
    
            return redirect()->back()->with('success', 'Módulo creado correctamente');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());

        }
    }
}