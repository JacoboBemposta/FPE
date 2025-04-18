<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['familias_profesionales_id', 'codigo','nombre','horas'];

    public function FamiliaProfesional()
    {
        return $this->belongsTo(FamiliaProfesional::class, 'familias_profesionales_id');
    }

    public function familia()
    {
        return $this->belongsTo(FamiliaProfesional::class, 'familias_profesionales_id'); // Asegúrate de que 'familia_id' es el campo correcto
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }


    public function cursosAcademicos()
    {
        return $this->hasMany(CursoAcademico::class, 'curso_id');
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }


}
