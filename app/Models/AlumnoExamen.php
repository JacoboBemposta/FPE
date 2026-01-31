<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AlumnoExamen extends Pivot
{
    // protected $table = 'alumno_examen';
    
    // protected $fillable = [
    //     'alumno_id',
    //     'examen_id',
    //     'nota',
    // ];
    
    // // Relación con Alumno (si es necesario)
    // public function alumno()
    // {
    //     return $this->belongsTo(Alumno::class);
    // }
    
    // Relación con Examen (si es necesario)
    // public function examen()
    // {
    //     return $this->belongsTo(Examen::class);
    // }
}
