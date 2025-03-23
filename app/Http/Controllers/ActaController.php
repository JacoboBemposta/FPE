<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnidadFormativa;
use App\Models\Calificacion;
use App\Models\AlumnoCurso; // Modelo para la tabla alumnos_curso
use App\Models\CursoAcademico;

class ActaController extends Controller
{
    public function generarActas(Request $request, $grado)
    {
        // Obtener el ID del curso académico desde la solicitud
        $cursoAcademicoId = $request->curso_academico_id;

        // Verificar si el curso académico existe
        $cursoAcademico = CursoAcademico::find($cursoAcademicoId);

        if (!$cursoAcademico) {
            return response()->json(['message' => 'Curso académico no encontrado'], 404);
        }

        // Obtener las unidades y alumnos seleccionados
        $unidadesIds = $request->input('unidades', []);
        $alumnosCursosIds = $request->input('alumnos', []); // IDs de alumnos_curso

        // Obtener los datos de las unidades formativas
        $unidades = UnidadFormativa::whereIn('id', $unidadesIds)->get();

        // Obtener las calificaciones de los alumnos seleccionados para las unidades seleccionadas
        $calificaciones = Calificacion::whereIn('unidad_formativa_id', $unidadesIds)
            ->whereIn('alumno_curso_id', $alumnosCursosIds)
            ->get();

        // Agrupar calificaciones por alumno_curso_id
        $datosActas = [];
        foreach ($calificaciones as $calificacion) {
            $datosActas[$calificacion->alumno_curso_id][$calificacion->unidad_formativa_id] = $calificacion->nota;
        }

        // Determinar la vista según el grado
        $vista = "actas.$grado"; // Ejemplo: actas.gradoA

        // Crear un PDF por cada alumno
        foreach ($datosActas as $alumnoCursoId => $notas) {
            // Obtener los datos del alumno desde la tabla alumnos_curso
            $alumnoCurso = AlumnoCurso::find($alumnoCursoId);
    
            if (!$alumnoCurso) {
                continue; // Saltar si no se encuentra el alumno_curso
            }

            // Obtener los datos del alumno (asumiendo que hay una relación con la tabla alumnos)
            $alumno = $alumnoCurso;

            // Obtener los datos del curso (asumiendo que hay una relación con la tabla cursos)
            $curso = $cursoAcademico->curso;

            if (!$curso) {
                return response()->json(['message' => 'Curso no encontrado para el curso académico'], 404);
            }

            // Generar el HTML para el PDF
            $html = view($vista, compact('unidades', 'notas', 'alumno', 'curso', 'cursoAcademico', 'alumnoCurso'))->render();

            // Crear el PDF
            $pdf = Pdf::loadHTML($html);

            // Descargar el PDF
            return $pdf->download("acta_gradoA_alumno_{$alumno->id}.pdf");
        }

        // Retornar una respuesta si no hay PDFs generados
        return response()->json(['message' => 'No se generaron actas']);
    }
}