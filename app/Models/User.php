<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
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
        'telefono',
        'password',
        'rol',
        'direccion', // <-- Asegúrate de incluir este campo
        'codigo_postal', // <-- Asegúrate de incluir este campo
        'localidad', // <-- Asegúrate de incluir este campo
        'provincia', // <-- Asegúrate de incluir este campo
        'numero_censo',
        'activo',
        'premium'
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

    public function cursoAcademico()
    {
        return $this->hasMany(CursoAcademico::class, 'academia_id'); // Usamos 'academia_id' aquí
    }

    // Relación de un profesor con los cursos académicos que imparte
    public function cursosAcademicos()
    {
        // Relación para academia y profesores
        return $this->belongsToMany(CursoAcademico::class, 'curso_academico_user', 'user_id', 'curso_academico_id')
                    ->withPivot('rol'); // Aquí añadimos el campo 'role' para saber si es academia o profesor
    }
    // Relación de una academia con los cursos académicos que gestiona
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'user_curso', 'curso_academico_id', 'user_id')
            ->where('rol', 'profesor');
    }

    // Relación con los cursos de un  profesor
    public function cursos()
    {
        return $this->belongsToMany(CursoAcademico::class, 'user_curso', 'user_id', 'curso_academico_id');
    }
    
    public function misCursos()
    {
        return $this->belongsToMany(CursoAcademico::class, 'user_curso', 'user_id', 'curso_academico_id')
                    ->with('curso');  // Relación con Curso
    }
    

    
    public function hasrol($rol)
    {
        return $this->rol === $rol;
    }    
}

