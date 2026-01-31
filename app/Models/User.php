<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\SuscripcionTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SuscripcionTrait;

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

public function updateRole(Request $request)
{
 

    // Validación que excluye 'admin'
    $request->validate([
        'rol' => 'required|in:academia,profesor,alumno' // Solo estos roles permitidos
    ]);

    try {
        // Usar Query Builder directamente para evitar problemas con Eloquent
        $affected = DB::table('users')
                    ->where('id', Auth::id())
                    ->update([
                        'rol' => $request->rol,
                        'updated_at' => now()
                    ]);

        if ($affected === 0) {
            throw new \Exception('No se pudo actualizar el usuario en la base de datos');
        }

 

        // Limpiar sesión del modal
        session()->forget('show_role_modal');

        // Redirigir según el rol
        return $this->redirectByRole($request->rol)
            ->with('success', 'Rol actualizado correctamente. ¡Bienvenido a Formación Plus!');

    } catch (\Exception $e) {

        return redirect('/home')->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
    }
}

private function redirectByRole($rol)
{
   
    
    switch ($rol) {
        case 'academia':
            return redirect()->route('academia.index');
        case 'profesor':
            return redirect()->route('profesor.miscursos');
        case 'alumno':
            return redirect('/home')->with('info', 'Bienvenido como alumno');
        default:
            return redirect('/home');
    }
}    

// En App\Models\User.php

// Relación de academia con los cursos académicos que gestiona
// En App\Models\User.php, añade:
public function cursosAcademicosComoAcademia()
{
    return $this->hasMany(CursoAcademico::class, 'academia_id');
}
// Otra opción (si prefieres no usar wherePivot)
public function cursosGestionados()
{
    return $this->belongsToMany(
        CursoAcademico::class,
        'academia_curso', // Si tienes una tabla específica para academia-curso
        'user_id',
        'curso_academico_id'
    )
    ->withTimestamps();
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
    // Primero verificar si tiene campo premium (si ya lo tienes)
    if ($this->premium && $this->fin_suscripcion && $this->fin_suscripcion->isFuture()) {
        return true;
    }
    
    // Luego verificar en tabla suscripciones - CAMBIA ESTO:
    return $this->suscripciones()
        ->where('activa', 1)  // ← Cambiar de 'estado' a 'activa'
        ->where('fecha_fin', '>', now())
        ->exists();
}

/**
 * Obtener suscripción activa
 */
public function suscripcionActiva()
{
    return $this->suscripciones()
        ->where('activa', 1)  // ← Cambiar de 'estado' a 'activa'
        ->where('fecha_fin', '>', now())
        ->latest()
        ->first();
}
}

