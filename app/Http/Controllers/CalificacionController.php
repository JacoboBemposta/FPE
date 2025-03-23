<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    // public function showCalificaciones($curso_academico_id)
    // {
    //     $cursoAcademico = CursoAcademico::with(['curso.modulos.unidades', 'alumnos'])->findOrFail($curso_academico_id);
    //     return view('calificaciones.index', compact('cursoAcademico'));
    // }

    // public function updateCalificacion(Request $request, $calificacion_id)
    // {
    //     $calificacion = Calificacion::findOrFail($calificacion_id);
    //     $calificacion->nota = $request->nota;
    //     $calificacion->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Calificación actualizada correctamente',
    //         'nota' => $calificacion->nota
    //     ]);
    // }
    public function update(Request $request, Calificacion $calificacion)
    {
        $request->validate([
            'nota' => 'required|numeric|min:0|max:10',
        ]);

        $calificacion->nota = $request->nota;
        $calificacion->save();

        return response()->json(['success' => true]);
    }
    // Crear una nueva calificación
    public function store(Request $request)
    {
        
        // Validar los datos de entrada
        $request->validate([
            'alumno_curso_id' => 'required|exists:alumnos_curso,id', // Cambia 'alumnos' por 'alumnos_curso'
            'unidad_formativa_id' => 'required|exists:unidades_formativas,id',
            'nota' => 'required|numeric|min:0|max:10',
        ]);
        
        // Crear la calificación
        $calificacion = Calificacion::create([
            'alumno_curso_id' => $request->alumno_curso_id,
            'unidad_formativa_id' => $request->unidad_formativa_id,
            'nota' => $request->nota,
        ]);
          
        // Devolver una respuesta JSON
        return response()->json([
            'success' => true,
            'calificacion_id' => $calificacion->id,
        ]);
    }
}
