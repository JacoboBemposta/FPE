<?php

namespace App\Services\Interfaces;

interface ActaInterface {
    public function generarActa($unidad, $alumnoCurso, $curso, $cursoAcademico, $centroFormacion);
}