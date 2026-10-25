<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'alumno_curso_id',
        'unidad_formativa_id',
        'modulo_id',
        'curso_academico_id',
        'nota',
    ];

    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class);
    }

    public function unidadFormativa()
    {
        return $this->belongsTo(UnidadFormativa::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function alumnoCurso()
    {
        return $this->belongsTo(AlumnoCurso::class, 'alumno_curso_id');
    }
}