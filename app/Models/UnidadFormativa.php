<?php

// app/Models/UnidadFormativa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadFormativa extends Model
{
    use HasFactory;


    protected $table = 'unidades_formativas'; 

 
    protected $fillable = ['modulo_id','nombre', 'codigo','horas'];

    // Define the inverse of the relationship with Modulo
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
    public function detallesCurso()
    {
        return $this->hasMany(DetalleCurso::class); // Relación de una unidad formativa con muchos detalles de curso
    }

}
