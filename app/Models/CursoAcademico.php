<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CursoAcademico extends Model
{
    protected $table = 'curso_academicos';
    protected $fillable = ['familias_profesionales_id', 'codigo', 'nombre', 'municipio','provincia', 'inicio', 'fin', 'active'];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
    
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_curso', 'curso_academico_id', 'user_id');
    }

    
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'curso_academico_profesor');
    }

    public function alumnos()
    {
        return $this->hasMany(AlumnoCurso::class, 'curso_academico_id');
    }

    
}