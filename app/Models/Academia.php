<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academia extends Model
{
    protected $fillable = ['cif', 'nombre', 'email', 'telefono', 'direccion', 'logo'];

    // Relación con CursoAcademico
    public function cursosAcademicos()
    {
        return $this->hasMany(CursoAcademico::class, 'academia_id');
    }

    // Relación indirecta con cursos a través de CursoAcademico
    public function cursos()
    {
        return $this->hasManyThrough(Curso::class, CursoAcademico::class, 'academia_id', 'id', 'id', 'curso_id');
    }
}