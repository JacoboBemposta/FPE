<?php

namespace App\Http\Controllers;

use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FamiliaProfesionalController extends Controller
{
    // public function index()
    // {
    //     // Mostrar todas las familias profesionales
    //     $familias_profesionales = FamiliaProfesional::all();
    //     return view('admin.familias.index', compact('familias_profesionales'));
    // }

    protected function sanitizeInput(array $data): array
    {
        return array_map(function($item) {
            return is_string($item) 
                ? htmlspecialchars(strip_tags(trim($item)), ENT_QUOTES, 'UTF-8')
                : $item;
        }, $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:familias_profesionales,codigo',
            'nombre' => 'required|string|max:100'
        ]);

        $sanitizedData = $this->sanitizeInput($validated);

        FamiliaProfesional::create($sanitizedData);

        return back()->with('success', 'Registro creado con éxito');
    }

    public function getCursosByFamilia($familiaId)
    {
        try {
            $familia = FamiliaProfesional::findOrFail($familiaId);
            $cursos = $familia->cursos()->withCount('modulos')->get();
            return response()->json($cursos);
        } catch (\Exception $e) {
            \Log::error('Error en getCursosByFamilia: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}