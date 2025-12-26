<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\AlumnoCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalificacionController extends Controller
{
    public function update(Request $request, Calificacion $calificacion)
    {
        $request->validate([
            'nota' => 'required|numeric|min:0|max:10',
        ]);

        $calificacion->nota = $request->nota;
        $calificacion->save();

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        Log::info('Store calificaciones called');
        Log::info('Request data:', $request->all());
        
        try {
            $validated = $request->validate([
                'alumno_curso_id' => 'required|exists:alumnos_curso,id', // Cambiado de alumno_id
                'curso_academico_id' => 'required|exists:curso_academicos,id',
                'unidad_formativa_id' => 'nullable|exists:unidades_formativas,id',
                'modulo_id' => 'nullable|exists:modulos,id',
                'nota' => 'required|numeric|min:0|max:10'
            ]);

            // Validación adicional
            if (empty($validated['unidad_formativa_id']) && empty($validated['modulo_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar unidad_formativa_id o modulo_id'
                ], 422);
            }

            // Verificar que el alumno pertenezca al curso académico
            $alumno = \App\Models\AlumnoCurso::where('id', $validated['alumno_curso_id'])
                ->where('curso_academico_id', $validated['curso_academico_id'])
                ->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'El alumno no pertenece a este curso académico'
                ], 422);
            }

            // Crear o actualizar la calificación directamente
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
            Log::error('Error de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }
}