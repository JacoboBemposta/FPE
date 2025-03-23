<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CursoAcademico extends Model
{
    protected $table = 'curso_academicos';
    protected $fillable = ['academia_id', 'curso_id', 'familias_profesionales_id', 'codigo', 'nombre', 'municipio', 'provincia', 'inicio', 'fin', 'active'];

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación con Academia
    public function academia()
    {
        return $this->belongsTo(Academia::class, 'academia_id');
    }

    // Relación con Usuarios (Profesores y Alumnos)
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_curso', 'curso_academico_id', 'user_id');
    }

    // Relación con Profesores
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'curso_academico_profesor');
    }

    // Relación con Alumnos
    public function alumnos()
    {
        return $this->hasMany(AlumnoCurso::class, 'curso_academico_id');
    }

    public function detallesCurso()
    {
        return $this->hasMany(DetalleCurso::class); // Relación de un curso académico con muchos detalles de curso
    }


}