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
        'direccion', 
        'codigo_postal',
        'localidad', 
        'provincia', 
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

    // public function cursoAcademico()
    // {
    //     return $this->hasMany(CursoAcademico::class, 'academia_id'); // Usamos 'academia_id' aquí
    // }

    // Relación de un profesor con los cursos académicos que imparte
    public function cursosAcademicos()
    {
        return $this->belongsToMany(
            CursoAcademico::class,
            'user_curso',
            'user_id',
            'curso_academico_id'
        )
        ->withTimestamps(); // si tus migraciones tienen timestamps
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

    public function asignaciones()
    {
        return $this->hasMany(AlumnoCurso::class, 'email', 'email');
    }

    // Sólo donde es_profesor = true
    public function asignacionesComoProfesor()
    {
        return $this->asignaciones()->where('es_profesor', 1);
    }
}

