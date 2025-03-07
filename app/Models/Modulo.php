<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['curso_id', 'codigo', 'nombre', 'horas'];
    
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function unidades()
    {
        return $this->hasMany(UnidadFormativa::class, 'modulo_id');
    }

}
