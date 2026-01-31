<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['curso_id', 'codigo', 'nombre', 'horas'];
    
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_modulo');
    }
    
    

    public function unidades()
    {
        return $this->belongsToMany(UnidadFormativa::class, 'modulo_unidad', 'modulo_id', 'unidad_formativa_id');
    }



}
