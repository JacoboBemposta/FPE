<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\AlumnoCurso;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function update(Request $request, Calificacion $calificacion)
    {
        try {
            if ($request->has('nota') && is_string($request->nota)) {
                $request->merge(['nota' => str_replace(',', '.', $request->nota)]);
            }

            $request->validate([
                'nota' => 'required|numeric|min:0|max:10',
            ]);

            $cursoAcademico = CursoAcademico::find($calificacion->curso_academico_id);
            if ($cursoAcademico && (int) $cursoAcademico->academia_id !== (int) Auth::id()) {
                return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
            }

            $calificacion->nota = $request->nota;
            $calificacion->save();

            return response()->json([
                'success' => true,
                'data' => $calificacion,
                'message' => 'Nota actualizada correctamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en update calificacion: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la nota'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if ($request->has('nota') && is_string($request->nota)) {
                $request->merge(['nota' => str_replace(',', '.', $request->nota)]);
            }

            $validated = $request->validate([
                'calificacion_id' => 'nullable|integer',
                'alumno_curso_id' => 'required|exists:alumnos_curso,id',
                'curso_academico_id' => 'required|exists:curso_academicos,id',
                'unidad_formativa_id' => 'nullable|exists:unidades_formativas,id',
                'modulo_id' => 'nullable|exists:modulos,id',
                'nota' => 'required|numeric|min:0|max:10'
            ]);

            if (empty($validated['unidad_formativa_id']) && empty($validated['modulo_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar unidad_formativa_id o modulo_id'
                ], 422);
            }

            // Verificar que el curso pertenezca a la academia actual
            $cursoAcademico = CursoAcademico::findOrFail($validated['curso_academico_id']);
            if ((int) $cursoAcademico->academia_id !== (int) Auth::id()) {
                return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
            }

            // Verificar que el alumno pertenezca al curso académico
            $alumno = AlumnoCurso::where('id', $validated['alumno_curso_id'])
                ->where('curso_academico_id', $validated['curso_academico_id'])
                ->first();

            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'El alumno no pertenece a este curso académico'
                ], 422);
            }

            $calificacion = null;

            if (!empty($validated['calificacion_id'])) {
                $calificacion = Calificacion::where('id', $validated['calificacion_id'])
                    ->where('alumno_curso_id', $validated['alumno_curso_id'])
                    ->first();
            }

            if (!$calificacion) {
                if (!empty($validated['unidad_formativa_id'])) {
                    $calificacion = Calificacion::where('alumno_curso_id', $validated['alumno_curso_id'])
                        ->where('unidad_formativa_id', $validated['unidad_formativa_id'])
                        ->first();
                } else {
                    $calificacion = Calificacion::where('alumno_curso_id', $validated['alumno_curso_id'])
                        ->where('modulo_id', $validated['modulo_id'])
                        ->whereNull('unidad_formativa_id')
                        ->first();
                }
            }

            if ($calificacion) {
                $calificacion->nota = $validated['nota'];
                $calificacion->curso_academico_id = $validated['curso_academico_id'];
                $calificacion->save();

                // Asegurar consistencia si existieran registros duplicados previos para la misma unidad/módulo
                if (!empty($validated['unidad_formativa_id'])) {
                    Calificacion::where('alumno_curso_id', $validated['alumno_curso_id'])
                        ->where('unidad_formativa_id', $validated['unidad_formativa_id'])
                        ->where('id', '!=', $calificacion->id)
                        ->update(['nota' => $validated['nota']]);
                } else {
                    Calificacion::where('alumno_curso_id', $validated['alumno_curso_id'])
                        ->where('modulo_id', $validated['modulo_id'])
                        ->whereNull('unidad_formativa_id')
                        ->where('id', '!=', $calificacion->id)
                        ->update(['nota' => $validated['nota']]);
                }
            } else {
                $calificacion = Calificacion::create([
                    'alumno_curso_id' => $validated['alumno_curso_id'],
                    'unidad_formativa_id' => $validated['unidad_formativa_id'] ?? null,
                    'modulo_id' => $validated['modulo_id'] ?? null,
                    'curso_academico_id' => $validated['curso_academico_id'],
                    'nota' => $validated['nota']
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $calificacion,
                'message' => 'Nota guardada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en calificaciones:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en store calificaciones: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }
}