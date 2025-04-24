<?php


// app/Http/Controllers/ModuloController.php
namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Curso;
use App\Models\UnidadFormativa;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function index()
    {
        // Obtiene todos los módulos con sus unidades asociadas
        $modulos = Modulo::with('unidades')->get();
        return view('admin.modulos.index', compact('modulos'));
    }

    public function show($id)
    {
        // Obtiene un módulo específico y sus unidades asociadas
        $modulo = Modulo::with('unidades')->findOrFail($id);
        return view('admin.modulos.show', compact('modulo'));
    }

    // public function create()
    // {
    //     return view('admin.modulos.create');
    // }


    public function store(Request $request, Curso $curso)
    {
        dd($request);
        // Validación de los datos
        $request->validate([
            'modulo_existente_id' => 'nullable|exists:modulos,id',
            'codigo' => 'required_if:modulo_existente_id,new|string|max:50',
            'nombre' => 'required_if:modulo_existente_id,new|string|max:255',
            'horas' => 'nullable|numeric'
        ]);
        
        // Manejo de módulo existente o nuevo
        if ($request->modulo_existente_id && $request->modulo_existente_id !== 'new') {
            // Vincular módulo existente
            $modulo = Modulo::findOrFail($request->modulo_existente_id);
            
            // Verificar si la relación ya existe
            if (!$curso->modulos()->where('modulo_id', $modulo->id)->exists()) {
                $curso->modulos()->attach($modulo->id);
                return redirect()->back()->with('success', 'Módulo existente vinculado al curso correctamente');
            }
            
            return redirect()->back()->with('info', 'Este módulo ya estaba vinculado al curso');
        } else {
            // Crear nuevo módulo
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
        dd($curso);
        $validated = $request->validate([
            'modulo_existente_id' => 'nullable|in:new,' . implode(',', $modulosDisponibles->pluck('id')->toArray()), // Permite 'new' o módulos existentes
            'codigo' => 'required_if:modulo_existente_id,new|string|max:50',
            'nombre' => 'required_if:modulo_existente_id,new|string|max:255',
            'horas' => 'nullable|numeric'
        ]);
    
        // Si se seleccionó crear un nuevo módulo, crearlo
        if ($request->modulo_existente_id === 'new') {
            $modulo = Modulo::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'horas' => $request->horas,
            ]);
        } else {
            // Si se seleccionó un módulo existente, buscarlo
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
}
