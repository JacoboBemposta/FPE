<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnidadFormativa;
use App\Models\Modulo; // Asegúrate de que el modelo Modulo existe
use App\Models\Calificacion;
use App\Models\AlumnoCurso;
use App\Models\CursoAcademico;

class ActaController extends Controller
{
    public function generarActas(Request $request, $grado)
    {
        
        // Obtener el ID del curso académico
        $cursoAcademicoId = $request->curso_academico_id;
        $cursoAcademico = CursoAcademico::findOrFail($cursoAcademicoId);

        // Validar módulos seleccionados
        $modulosIds = $request->input('modulos', []);

        // Validación específica para Grado A
        if ($grado === 'gradoA') {
            if (count($modulosIds) !== 1) {
                return response()->json(['message' => 'Debe seleccionar exactamente 1 módulo para Grado A'], 400);
            }

            // Obtener el módulo y sus unidades
            $modulo = Modulo::with('unidades')->findOrFail($modulosIds[0]);
            $unidades = $modulo->unidades;

            // Obtener alumnos seleccionados
            $alumnosIds = $request->alumnos ?? [];
 
            $alumnos = AlumnoCurso::whereIn('id', $alumnosIds) 
            ->with('alumno') // Aquí debe ser "alumno", no "alumnos_curso"
            ->get();

            // Recolectar calificaciones por unidad
            $calificaciones = [];
            foreach ($alumnos as $alumno) {
                foreach ($unidades as $unidad) {
                    $calificaciones[$alumno->id][$unidad->id] = $request->input(
                        "calificaciones.{$alumno->id}.unidad.{$unidad->id}", 
                        0
                    );
                }
            }


            $unidadId = $unidades->last()->id; // o la unidad que estés utilizando

            // Obtener la fecha de finalización de la tabla detalles_curso
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->where('unidad_formativa_id', $unidadId)
                ->first();
            
            // Pasar la fecha de finalización a la vista
            $fechaFinal = $detalleCurso ? $detalleCurso->fin : null; //

            $unidadId = $unidades->first()->id; // o la unidad que estés utilizando

            // Obtener la fecha de finalización de la tabla detalles_curso
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->where('unidad_formativa_id', $unidadId)
                ->first();
                        
            $fechaInicial = $detalleCurso ? $detalleCurso->inicio : null; //

            $unidadId = $unidades->last()->id; // o la unidad que estés utilizando

            // Obtener la fecha de finalización de la tabla detalles_curso
            $detalleCurso = \App\Models\DetalleCurso::where('curso_academico_id', $cursoAcademicoId)
                ->where('unidad_formativa_id', $unidadId)
                ->first();
                        
            $examenFinal = $detalleCurso ? $detalleCurso->ExamenF : null; //


            // Generar HTML de ambas vistas
            $htmlAnverso = view('actas.gradoA', [
                'modulo' => $modulo,
                'unidades' => $unidades,
                'alumnos' => $alumnos,
                'calificaciones' => $calificaciones,
                'cursoAcademico' => $cursoAcademico,
                'centroFormacion' => auth()->user(),
                'fechaFinal' => $fechaFinal,
                'fechaInicial' => $fechaInicial,
                'examenFinal' => $examenFinal
            ])->render(); // <-- Usa render() para obtener el HTML

            $htmlReverso = view('actas.gradoAreverso', [
                'modulo' => $modulo,
                'unidades' => $unidades
            ])->render(); // <-- Usa render() aquí también

            // Combina los HTMLs y genera el PDF
            $pdf = Pdf::loadHTML($htmlAnverso . $htmlReverso);
            return $pdf->download("acta_gradoA_{$modulo->codigo}.pdf");
        }

        // Lógica para otros grados (B y C) aquí...
    }
}