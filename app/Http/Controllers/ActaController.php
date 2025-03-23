<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnidadFormativa;
use App\Models\Calificacion;
use App\Models\AlumnoCurso;
use App\Models\CursoAcademico;
use App\Models\User;
use ZipStream\ZipStream;
use App\Services\Actas\ActaGradoA;
use App\Services\Actas\ActaGradoB;

class ActaController extends Controller
{
    public function generarActas(Request $request, $grado)
    {
        // Depuración: Verifica que el grado sea correcto
        logger("Grado recibido: $grado");
    
        // Obtener el ID del curso académico desde la solicitud
        $cursoAcademicoId = $request->curso_academico_id;
    
        // Verificar si el curso académico existe
        $cursoAcademico = CursoAcademico::find($cursoAcademicoId);
            
        if (!$cursoAcademico) {
            logger("Curso académico no encontrado: $cursoAcademicoId");
            return response()->json(['message' => 'Curso académico no encontrado'], 404);
        }
    
        // Depuración: Verifica que el curso académico se haya encontrado
        logger("Curso académico encontrado: " . $cursoAcademico->id);
    
        // Obtener las unidades seleccionadas
        $unidadesIds = $request->input('unidades', []);
        
        // Verificar que solo se haya seleccionado una unidad formativa para el grado A
        if ($grado === 'gradoA' && count($unidadesIds) > 1) {
            return response()->json(['message' => 'Solo se puede seleccionar una unidad formativa para el grado A'], 400);
        }

        // Obtener los alumnos seleccionados
        $alumnosCursosIds = $request->input('alumnos', []); // IDs de alumnos_curso
        
        // Obtener los datos de la unidad formativa seleccionada
        $unidad = UnidadFormativa::find($unidadesIds[0]); // Solo se usa la primera unidad seleccionada
        
        if (!$unidad) {
            return response()->json(['message' => 'Unidad formativa no encontrada'], 404);
        }
    
        // Obtener las calificaciones de los alumnos seleccionados para la unidad seleccionada
        $calificaciones = Calificacion::where('unidad_formativa_id', $unidad->id)
            ->whereIn('alumno_curso_id', $alumnosCursosIds)
            ->get();
            
        // Agrupar calificaciones por alumno_curso_id
        $datosActas = [];
        foreach ($calificaciones as $calificacion) {
            $datosActas[$calificacion->alumno_curso_id][$calificacion->unidad_formativa_id] = $calificacion->nota;
        }
  
        // Determinar la clase concreta según el grado
        switch ($grado) {
            case 'gradoA':
                $acta = new ActaGradoA();
                break;
            case 'gradoB':
                $acta = new ActaGradoB();
                break;
            default:
                return response()->json(['message' => 'Grado no soportado'], 400);
        }
    
        // Obtener el usuario logueado como centro de formación
        $centroFormacion = auth()->user(); // Usuario logueado
    
        if (!$centroFormacion) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
    
        // Crear un archivo ZIP usando zipstream-php
        return response()->streamDownload(function () use ($datosActas, $acta, $unidad, $cursoAcademico, $centroFormacion, $grado) {
            $zip = new ZipStream(outputName: "actas_grado_$grado.zip");
    
            foreach ($datosActas as $alumnoCursoId => $notas) {
                // Obtener los datos del alumno desde la tabla alumnos_curso
                $alumnoCurso = AlumnoCurso::find($alumnoCursoId);
    
                if (!$alumnoCurso) {
                    logger("AlumnoCurso no encontrado: $alumnoCursoId");
                    continue; // Saltar si no se encuentra el alumno_curso
                }
    
                // Obtener los datos del curso desde la relación con el curso académico
                $curso = $cursoAcademico->curso;
                
                if (!$curso) {
                    logger("Curso no encontrado para el curso académico: " . $cursoAcademico->id);
                    continue; // Saltar si no se encuentra el curso
                }
                
                // Generar el HTML para el PDF
                try {
                    $html = $acta->generarActa($unidad, $alumnoCurso, $curso, $cursoAcademico, $centroFormacion);
                } catch (\Exception $e) {
                    logger("Error al generar el HTML: " . $e->getMessage());
                    continue;
                }
    
                // Crear el PDF
                $pdf = Pdf::loadHTML($html);
    
                // Guardar el PDF en un archivo temporal
                $pdfFileName = "acta_grado{$grado}_alumno_{$alumnoCurso->id}.pdf";
                $pdf->save(storage_path("app/$pdfFileName"));
    
                // Depuración: Verifica que el archivo PDF se haya guardado correctamente
                if (!file_exists(storage_path("app/$pdfFileName"))) {
                    logger("Error: El archivo PDF no se guardó correctamente: $pdfFileName");
                    continue;
                } else {
                    logger("Archivo PDF guardado correctamente: $pdfFileName");
                }
    
                // Agregar el PDF al archivo ZIP
                $zip->addFileFromPath($pdfFileName, storage_path("app/$pdfFileName"));
    
                // Depuración: Verifica que el archivo se haya agregado al ZIP
                logger("Archivo agregado al ZIP: $pdfFileName");
    
                // Eliminar el archivo PDF temporal
                unlink(storage_path("app/$pdfFileName"));
    
                // Depuración: Verifica que el archivo se haya eliminado correctamente
                if (!file_exists(storage_path("app/$pdfFileName"))) {
                    logger("Archivo PDF eliminado correctamente: $pdfFileName");
                }
            }
    
            // Finalizar el archivo ZIP
            $zip->finish();
        }, "actas_grado_$grado.zip");
    }
}