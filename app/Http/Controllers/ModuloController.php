<?php


// app/Http/Controllers/ModuloController.php
namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Curso;
use App\Models\UnidadFormativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuloController extends Controller
{
    public function index()
    {
       
        $modulos = Modulo::with('unidades')->get();
        return view('admin.modulos.index', compact('modulos'));
    }

    public function show($id)
    {
        
        $modulo = Modulo::with('unidades')->findOrFail($id);
        return view('admin.modulos.show', compact('modulo'));
    }

    public function create()
    {
        return view('admin.modulos.create');
    }


    public function store(Request $request, Curso $curso)
    {
        
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'modulo_existente_id' => 'nullable|exists:modulos,id',
            'codigo' => 'required_if:modulo_existente_id,new|string|max:50',
            'nombre' => 'required_if:modulo_existente_id,new|string|max:255',
            'horas' => 'nullable|numeric'
        ]);

       
        if ($request->modulo_existente_id && $request->modulo_existente_id !== 'new') {
           
            $modulo = Modulo::findOrFail($request->modulo_existente_id);
            
            // Verificar si la relación ya existe
            if (!$curso->modulos()->where('modulo_id', $modulo->id)->exists()) {
                $curso->modulos()->attach($modulo->id);
                return redirect()->back()->with('success', 'Módulo existente vinculado al curso correctamente');
            }
            
            return redirect()->back()->with('info', 'Este módulo ya estaba vinculado al curso');
        } else {
            
            $modulo = Modulo::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'horas' => $request->horas
            ]);
            
            // Vincular al curso
            $curso->modulos()->attach($modulo->id);
            
            return redirect()->back()->with('success', 'Nuevo módulo creado y vinculado al curso correctamente');
        }
    }
    public function edit($id)
    {
        $modulo = Modulo::findOrFail($id);
        return view('admin.modulos.edit', compact('modulo'));
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'modulo_existente_id' => 'nullable|in:new,' . implode(',', $curso->modulos->pluck('id')->toArray()), // Permite 'new' o módulos existentes
            'codigo' => 'required_if:modulo_existente_id,new|string|max:50',
            'nombre' => 'required_if:modulo_existente_id,new|string|max:255',
            'horas' => 'nullable|numeric'
        ]);
    
        
        if ($request->modulo_existente_id === 'new') {
            $modulo = Modulo::create([
                'curso_id' => $curso->id,
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'horas' => $request->horas,
            ]);
        } else {
           
            $modulo = Modulo::find($request->modulo_existente_id);
        }
    
        // Sincronizar el módulo con el curso
        $curso->modulos()->sync([$modulo->id]);
    
        return redirect()->back()->with('success', 'Curso actualizado correctamente');
    }
    

    public function destroy($id)
    {
        
        try {
            $modulo = Modulo::findOrFail($id);
            $modulo->delete();
    
            return redirect()->route('admin.panel')->with('success', 'Módulo eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

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
