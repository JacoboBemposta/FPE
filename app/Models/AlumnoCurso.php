<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoCurso extends Model
{
    use HasFactory;
    protected $table = 'alumnos_curso';
    protected $fillable = ['curso_academico_id','dni', 'nombre','email', 'telefono','es_profesor'];


    //Relación con CursoAcademico
    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class, 'curso_academico_id');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'alumno_curso_id');
    }
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }    
}
