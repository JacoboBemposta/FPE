<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliaProfesional extends Model
{
    use HasFactory;

    protected $table = 'familias_profesionales';
    protected $fillable = ['codigo','nombre'];

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'familia_profesional_id');
    }



}
