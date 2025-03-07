<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoCurso extends Model
{
    use HasFactory;
    protected $table = 'alumnos_curso';
    protected $fillable = ['curso_academico_id','dni', 'nombre','email', 'telefono'];


    // Definir relación con CursoAcademico
    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class, 'curso_academico_id');
    }

    // Si tienes un modelo de Alumno, podrías definir la relación aquí también
    // public function alumno()
    // {
    //     return $this->belongsTo(Alumno::class, 'alumno_id');
    // }
}
