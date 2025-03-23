<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCurso extends Model
{
    use HasFactory;

    protected $table = 'detalles_curso';
    
    protected $fillable = [
        'curso_academico_id',
        'unidad_formativa_id',
        'codigo',
        'nombre',
        'inicio',
        'fin',
        'Examen0',   // Asegúrate de que estos campos existen en la tabla detalles_curso
        'ExamenF',
    ];

    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class);
    }

    public function unidadFormativa()
    {
        return $this->belongsTo(UnidadFormativa::class);
    }
}
