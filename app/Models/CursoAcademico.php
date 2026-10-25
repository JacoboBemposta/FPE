<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoAcademico extends Model
{
    protected $table = 'curso_academicos';
    protected $fillable = ['academia_id', 'curso_id', 'municipio', 'provincia', 'inicio', 'fin', 'active', 'archivado', 'archivado_en'];

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación con Academia
    public function academia()
    {
        return $this->belongsTo(User::class, 'academia_id');
    }

    // Relación con Usuarios (Profesores y Alumnos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_curso', 'curso_academico_id', 'user_id');
    }
    
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'user_curso', 'curso_academico_id', 'user_id')
            ->where('rol', 'profesor');
    }

    // Relación con Alumnos
    public function alumnos()
    {
        return $this->hasMany(AlumnoCurso::class, 'curso_academico_id');
    }
    
    public function detallesCurso()
    {
        return $this->hasMany(DetalleCurso::class, 'curso_academico_id');
    }

    public function detallesCursos()
    {
        return $this->detallesCurso();
    }
}