<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnidadFormativa;
use App\Models\Modulo;
use App\Models\Calificacion;
use App\Models\AlumnoCurso;
use App\Models\CursoAcademico;
use Illuminate\Support\Facades\Auth;

class ActaController extends Controller
{
    public function generarActas(Request $request, $grado)
    {
        // Obtener el ID del curso académico
        $cursoAcademicoId = $request->curso_academico_id;
        $cursoAcademico = CursoAcademico::with('curso.modulos.unidades')->findOrFail($cursoAcademicoId);

        // Validar módulos seleccionados
        $modulosIds = $request->input('modulos', []);

        // Validar alumnos seleccionados
        $alumnosIds = $request->input('alumnos', []);

        // Validación específica para Grado B
        if ($grado === 'gradoB') {
            if (count($modulosIds) !== 1) {
                return back()->with('error', 'Debe seleccionar exactamente 1 módulo para Grado B');
            }

            if (empty($alumnosIds)) {
                // Si no hay alumnos seleccionados, usar todos los alumnos del curso
                $alumnosIds = $cursoAcademico->alumnos->where('es_profesor', false)->pluck('id')->toArray();
            }

            // Obtener el módulo y sus unidades
            $modulo = Modulo::with('unidades')->findOrFail($modulosIds[0]);
            $unidades = $modulo->unidades;

            // Obtener alumnos seleccionados
            $alumnos = AlumnoCurso::whereIn('id', $alumnosIds)
                ->where('curso_academico_id', $cursoAcademicoId)
                ->where('es_profesor', false)
                ->with('calificaciones')
                ->get();

            // Recolectar calificaciones por unidad
            $calificaciones = [];
            foreach ($alumnos as $alumno) {
                foreach ($unidades as $unidad) {
                    // Buscar calificación existente en la base de datos
                    $calificacion = $alumno->calificaciones
                        ->where('unidad_formativa_id', $unidad->id)
                        ->where('curso_academico_id', $cursoAcademicoId)
                        ->first();
                    
                    $calificaciones[$alumno->id][$unidad->id] = $calificacion ? $calificacion->nota : 0;
                }
            }

            $unidadId = $unidades->last()->id; 

            // Obtener la fecha de finalización de la tabla detalles_curso
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->where('unidad_formativa_id', $unidadId)
                ->first();
            
            // Pasar la fecha de finalización a la vista
            $fechaFinal = $detalleCurso ? $detalleCurso->fin : null;

            $unidadId = $unidades->first()->id;

            // Obtener la fecha de inicio
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->where('unidad_formativa_id', $unidadId)
                ->first();
                        
            $fechaInicial = $detalleCurso ? $detalleCurso->inicio : null;

            $unidadId = $unidades->last()->id;
                        
            $examenFinal = $detalleCurso ? $detalleCurso->ExamenF : null;

            // Generar HTML de ambas vistas
            $htmlAnverso = view('actas.gradoB', [
                'modulo' => $modulo,
                'unidades' => $unidades,
                'alumnos' => $alumnos,
                'calificaciones' => $calificaciones,
                'cursoAcademico' => $cursoAcademico,
                'centroFormacion' => Auth::user(),
                'fechaFinal' => $fechaFinal,
                'fechaInicial' => $fechaInicial,
                'examenFinal' => $examenFinal
            ])->render();

            $htmlReverso = view('actas.gradoBreverso', [
                'modulo' => $modulo,
                'unidades' => $unidades
            ])->render();

            // Combina los HTMLs y genera el PDF
            $pdf = Pdf::loadHTML($htmlAnverso . $htmlReverso);
            return $pdf->download("acta_gradoB_{$modulo->codigo}.pdf");
        }

        // Lógica para Grado C (todos los módulos, solo un alumno)
        elseif ($grado === 'gradoC') {
            // Validación: debe seleccionar exactamente un alumno
            if (count($alumnosIds) !== 1) {
                return back()->with('error', 'Debe seleccionar exactamente 1 alumno para Grado C');
            }

            // Si no hay módulos seleccionados, usar todos los módulos del curso
            if (empty($modulosIds)) {
                $modulosIds = $cursoAcademico->curso->modulos->pluck('id')->toArray();
            }

            // Obtener el alumno seleccionado
            $alumno = AlumnoCurso::where('id', $alumnosIds[0])
                ->where('curso_academico_id', $cursoAcademicoId)
                ->where('es_profesor', false)
                ->with(['calificaciones', 'alumno'])
                ->firstOrFail();

            // Obtener los módulos seleccionados con sus unidades
            $modulos = Modulo::whereIn('id', $modulosIds)
                ->with('unidades')
                ->get();

            // Preparar datos para la vista
            $calificacionesPorModulo = [];
            $calificacionesPorUnidad = [];
            
            foreach ($modulos as $modulo) {
                // Obtener calificación del módulo (si no tiene unidades)
                if ($modulo->unidades->count() === 0) {
                    $calificacionModulo = $alumno->calificaciones
                        ->where('modulo_id', $modulo->id)
                        ->whereNull('unidad_formativa_id')
                        ->where('curso_academico_id', $cursoAcademicoId)
                        ->first();
                    
                    $calificacionesPorModulo[$modulo->id] = $calificacionModulo ? $calificacionModulo->nota : 0;
                } 
                // Obtener calificaciones por unidad
                else {
                    foreach ($modulo->unidades as $unidad) {
                        $calificacionUnidad = $alumno->calificaciones
                            ->where('unidad_formativa_id', $unidad->id)
                            ->where('curso_academico_id', $cursoAcademicoId)
                            ->first();
                        
                        $calificacionesPorUnidad[$modulo->id][$unidad->id] = $calificacionUnidad ? $calificacionUnidad->nota : 0;
                    }
                }
            }

            // Obtener fechas del curso
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->first();
            
            $fechaInicial = $detalleCurso ? $detalleCurso->inicio : null;
            $fechaFinal = $detalleCurso ? $detalleCurso->fin : null;

            // Generar el PDF para grado C
            $pdf = Pdf::loadView('actas.gradoC', [
                'alumno' => $alumno,
                'modulos' => $modulos,
                'calificacionesPorModulo' => $calificacionesPorModulo,
                'calificacionesPorUnidad' => $calificacionesPorUnidad,
                'cursoAcademico' => $cursoAcademico,
                'centroFormacion' => Auth::user(),
                'fechaInicial' => $fechaInicial,
                'fechaFinal' => $fechaFinal,
            ]);

            $nombreArchivo = "acta_gradoC_{$alumno->dni}_{$cursoAcademico->curso->nombre}.pdf";
            return $pdf->download($nombreArchivo);
        }

        // Lógica para Grado A (por si acaso)
        elseif ($grado === 'gradoA') {
            // Aquí puedes implementar la lógica para Grado A si es necesario
            return back()->with('error', 'Funcionalidad para Grado A no implementada aún');
        }

        return back()->with('error', 'Tipo de acta no válido');
    }
}