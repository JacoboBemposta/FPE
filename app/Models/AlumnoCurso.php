<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AlumnoCurso extends Model
{
    use HasFactory;

    protected $table = 'alumnos_curso';

    protected $fillable = [
        'curso_academico_id',
        'dni',
        'nombre',
        'email',
        'telefono',
        'es_profesor'
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔐 CIFRADO DE DATOS PERSONALES
    |--------------------------------------------------------------------------
    */

    // DNI
    public function setDniAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['dni'] = Crypt::encryptString($value);
        }
    }

    public function getDniAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Nombre
    public function setNombreAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['nombre'] = Crypt::encryptString($value);
        }
    }

    public function getNombreAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Email
    public function setEmailAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['email'] = Crypt::encryptString($value);
        }
    }

    public function getEmailAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Teléfono (nullable)
    public function setTelefonoAttribute($value)
    {
        $this->attributes['telefono'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

    public function getTelefonoAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | 🔗 RELACIONES
    |--------------------------------------------------------------------------
    */

    public function cursoAcademico()
    {
        return $this->belongsTo(CursoAcademico::class, 'curso_academico_id');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'alumno_curso_id');
    }
}
