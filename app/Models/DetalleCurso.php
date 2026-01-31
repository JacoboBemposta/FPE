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
        'modulo_id',
        'unidad_formativa_id',
        'inicio',
        'fin',
        'Examen0',
        'ExamenF',
    ];
    
    protected $casts = [
        'inicio'         => 'date',
        'fin'            => 'date',
        'Examen0'        => 'date',
        'ExamenF'        => 'date',
    ];
    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class, 'curso_academico_id');
    }
    public function unidadFormativa()
    {
        return $this->belongsTo(UnidadFormativa::class);
    }
}
