<?php


// app/Http/Controllers/ModuloController.php
namespace App\Http\Controllers;

use App\Models\Modulo;
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

    public function create()
    {
        return view('admin.modulos.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',  // Validación para el campo 'nombre'
            'codigo' => 'required|string|max:255',  // Validación para el campo 'codigo'
            'horas' => 'required|integer',          // Validación para el campo 'horas'
            'curso_id' => 'required|exists:cursos,id',  // Validación para el campo 'curso_id'
        ]);
        //dd($request);
        // Eliminar cualquier etiqueta HTML de los campos 'nombre', 'codigo', y 'horas'
        $nombre = strip_tags($request->input('nombre')); 
        $codigo = strip_tags($request->input('codigo')); 
        $horas = strip_tags($request->input('horas'));   
    
        // Crear el módulo
        $modulo = Modulo::create([
            'nombre' => $nombre,
            'codigo' => $codigo,
            'horas' => $horas,
            'curso_id' => $request->curso_id, // Relación con el curso seleccionado
        ]);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.panel')->with('success', 'Módulo creado con éxito.');
    }
    
    

    public function edit($id)
    {
        $modulo = Modulo::findOrFail($id);
        return view('admin.modulos.edit', compact('modulo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
        ]);

        $modulo = Modulo::findOrFail($id);

        $nombre = strip_tags($request->input('nombre')); 
        $codigo = strip_tags($request->input('codigo')); 
        
        $modulo->update([
            'nombre' => $nombre,
            'codigo' => $codigo,
        ]);

        return redirect()->route('admin.panel')->with('success', 'Módulo actualizado con éxito.');
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
