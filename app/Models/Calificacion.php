<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    // Definir los campos que son asignables en masa (mass assignable)
    protected $fillable = [
        'alumno_curso_id',
        'unidad_formativa_id',
        'modulo_id',
        'curso_academico_id',
        'nota',
    ];

    // Relación con el curso académico
    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class);
    }

    // Relación con el alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    // Relación con la unidad formativa
    public function unidadFormativa()
    {
        return $this->belongsTo(UnidadFormativa::class);
    }

    // Relación con el módulo (en caso de que sea por módulo en vez de por unidad)
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
    public function alumnoCurso()
    {
        return $this->belongsTo(AlumnoCurso::class, 'alumno_curso_id');
    }
}
