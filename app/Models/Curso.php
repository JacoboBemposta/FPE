<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['familia_profesional_id', 'codigo','nombre','horas'];

    public function FamiliaProfesional()
    {
        return $this->belongsTo(FamiliaProfesional::class, 'familia_profesional_id');
    }



    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'curso_modulo')
                    ->withTimestamps(); 
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
