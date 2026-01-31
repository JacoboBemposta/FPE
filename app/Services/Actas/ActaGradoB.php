<?php

namespace App\Services\Actas;

use App\Services\Interfaces\ActaInterface;

class ActaGradoB implements ActaInterface {
    public function generarActa($unidad, $alumnoCurso, $curso, $cursoAcademico, $centroFormacion) {
        // Lógica específica para generar el acta del grado B
        return view('actas.gradoB', compact('unidad', 'alumnoCurso', 'curso', 'cursoAcademico', 'centroFormacion'))->render();
    }
}