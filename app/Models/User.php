<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ident',
        'name',
        'email',
        'password',
        'rol',
        'activo',
        'premium',
        'google_id',
        'avatar',
        'provider',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'premium' => 'boolean',
            'inicio_suscripcion' => 'datetime', 
            'fin_suscripcion' => 'datetime',
        ];
    }

    /**
     * Verifica si el usuario es una academia.
     */
    public function isAcademia()
    {
        return $this->rol === 'academia';
    }

    /**
     * Verifica si el usuario es un profesor.
     */
    public function isProfesor()
    {
        return $this->rol === 'profesor';
    }

    /**
     * Verifica si el usuario es un alumno.
     */
    public function isAlumno()
    {
        return $this->rol === 'alumno';
    }

    public function hasrol($rol)
    {
        return $this->rol === $rol;
    }

    // Relación de un profesor con los cursos académicos que imparte
    public function cursosAcademicos()
    {
        return $this->belongsToMany(
            CursoAcademico::class,
            'user_curso',
            'user_id',
            'curso_academico_id'
        )->withTimestamps();
    }

    // Alias para compatibilidad
    public function cursos()
    {
        return $this->cursosAcademicos();
    }

    // Relación de una academia con los cursos académicos que gestiona
    public function cursosAcademicosComoAcademia()
    {
        return $this->hasMany(CursoAcademico::class, 'academia_id');
    }

    // Método para obtener la fecha de fin de suscripción formateada
    public function getFinSuscripcionFormatted()
    {
        if ($this->fin_suscripcion) {
            return $this->fin_suscripcion->format('d/m/Y');
        }
        
        return null;
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    /**
     * Verificar si tiene suscripción activa
     */
    public function tieneSuscripcionActiva()
    {
        if ($this->premium && $this->fin_suscripcion && $this->fin_suscripcion->isFuture()) {
            return true;
        }
        
        return $this->suscripciones()
            ->where('activa', 1)
            ->where('fecha_fin', '>', now())
            ->exists();
    }

    /**
     * Obtener suscripción activa
     */
    public function suscripcionActiva()
    {
        return $this->suscripciones()
            ->where('activa', 1)
            ->where('fecha_fin', '>', now())
            ->latest()
            ->first();
    }
}
