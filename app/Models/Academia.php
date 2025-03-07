<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academia extends Model
{
    protected $fillable = ['cif', 'nombre','email', 'telefono', 'direccion', 'logo'];

    // Relación many-to-many con cursos
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'user_id', 'curso_id');
    }
}
