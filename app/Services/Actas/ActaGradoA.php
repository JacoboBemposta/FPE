<?php

namespace App\Services\Actas;

use App\Services\Interfaces\ActaInterface;

class ActaGradoA implements ActaInterface {
    public function generarActa($unidad, $alumnoCurso, $curso, $cursoAcademico, $centroFormacion) {
        // Lógica específica para generar el acta del grado A
        return view('actas.gradoA', compact('unidad', 'alumnoCurso', 'curso', 'cursoAcademico', 'centroFormacion'))->render();
    }
}