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

    public function storeCalificacion(Request $request)
    {
        // Verificar si es una petición AJAX o si el cliente acepta JSON
        if (!$request->ajax() && !$request->wantsJson()) {

            return response()->json([
                'success' => false,
                'message' => 'Solo se aceptan solicitudes AJAX/JSON',
                'received_headers' => $request->headers->all()
            ], 400);
        }
    
        // Forzar el tratamiento como JSON
        $request->headers->set('Accept', 'application/json');
    
        try {
            $validated = $request->validate([
                'alumno_curso_id' => 'required|exists:alumnos_curso,id',
                'unidad_formativa_id' => 'nullable|exists:unidades_formativas,id',
                'modulo_id' => 'nullable|exists:modulos,id',
                'nota' => 'required|numeric|min:0|max:10',
                'curso_academico_id' => 'required|exists:curso_academicos,id'
            ]);
    
            // Validación adicional
            if (empty($validated['unidad_formativa_id']) && empty($validated['modulo_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar unidad_formativa_id o modulo_id'
                ], 422);
            }
    
            $calificacion = Calificacion::updateOrCreate(
                [
                    'alumno_curso_id' => $validated['alumno_curso_id'],
                    'unidad_formativa_id' => $validated['unidad_formativa_id'],
                    'modulo_id' => $validated['modulo_id'],
                    'curso_academico_id' => $validated['curso_academico_id']
                ],
                ['nota' => $validated['nota']]
            );
    
            return response()->json([
                'success' => true,
                'data' => $calificacion,
                'message' => 'Nota guardada correctamente'
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
    
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }
}
