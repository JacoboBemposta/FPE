<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    // Relación con los cursos
    public function cursosAcademicos()
    {
        return $this->belongsToMany(CursoAcademico::class, 'alumnos_curso', 'alumno_id', 'curso_academico_id');
    }
}