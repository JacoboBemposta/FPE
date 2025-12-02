<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\FamiliaProfesionalController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\UnidadFormativaController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\CursoAcademicoController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EmailStatsController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfesorCursoController;
use App\Http\Controllers\CursoModuloController;
use App\Http\Controllers\Auth\GoogleLoginController;
use Illuminate\Support\Facades\Auth;


    Route::get('/', function () {
        $user = Auth::user();
        
        // Si no hay usuario autenticado, mostrar welcome normal
        if (!$user) {
            return view('welcome');
        }

        // Si el usuario no tiene rol, mostrar welcome con modal
        if (is_null($user->rol)) {
            session(['show_role_modal' => true]);
            return view('welcome', [
                'user' => $user,
                'userRole' => $user->rol,
                'userName' => $user->name,
                'showRoleModal' => true,
            ]);
        }

        // Si el usuario es admin, redirigir a admin.panel
        if ($user->rol === 'admin') {
            return redirect()->route('admin.panel');
        }

        // Para los demás roles, mostrar welcome personalizado
        return view('welcome', [
            'user' => $user,
            'userRole' => $user->rol,
            'userName' => $user->name,
            'showRoleModal' => false,
        ]);
    });

    Auth::routes();


    Route::get('/home', function () {
        return redirect('/');
    })->name('home');  


// Rutas para cada panel según el rol
Route::get('/academia/dashboard', function () {
    return view('academia.dashboard');  // Ruta para el panel de academia
})->name('academia.dashboard');

Route::get('/profesor/dashboard', function () {
    return view('profesor.dashboard');  // Ruta para el panel de profesor
})->name('profesor.dashboard');

Route::get('/alumno/dashboard', function () {
    return view('alumno.dashboard');  // Ruta para el panel de alumno
})->name('alumno.dashboard');







// Grupo protegido por middleware y con prefijo
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Ruta al panel (index principal del admin)
    Route::get('/', [AdminController::class, 'index'])->name('panel'); 

    // Familias Profesionales
    Route::post('/familias-profesionales', [FamiliaProfesionalController::class, 'store'])->name('familias-profesionales.store');
    
    // Cursos
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    
    // Rutas para crear

    Route::post('/unidades', [UnidadFormativaController::class, 'store'])->name('unidades.store');

    Route::post('/cursos/{curso}/modulos', [CursoModuloController::class, 'store'])->name('cursos.modulos.store');
    

    // Rutas para eliminar
    Route::delete('/familia/{id}', [FamiliaProfesionalController::class, 'destroy'])->name('familia.destroy');
    Route::delete('/cursos/{curso}/modulos/{modulo}', [CursoModuloController::class, 'destroy'])->name('cursos.modulos.destroy');
    Route::delete('unidades/{unidad}', [UnidadFormativaController::class, 'destroy'])->name('unidades.destroy');

    // Rutas para ver y editar unidades formativas por módulo
    Route::get('/modulo/{modulo}/unidades', [UnidadFormativaController::class, 'index'])->name('modulos.unidades.index');
    Route::get('/modulo/{modulo}/unidades/create', [UnidadFormativaController::class, 'create'])->name('modulos.unidades.create');
    Route::get('/modulo/{modulo}/unidades/{id}/edit', [UnidadFormativaController::class, 'edit'])->name('modulos.unidades.edit');
    Route::put('/modulo/{modulo}/unidades/{id}', [UnidadFormativaController::class, 'update'])->name('modulos.unidades.update');

    
    // Rutas para AJAX
    Route::get('/familias/{familia}/cursos', [App\Http\Controllers\FamiliaProfesionalController::class, 'getCursosByFamilia'])->name('familias.cursos');
    Route::get('/cursos/{curso}/modulos', [App\Http\Controllers\CursoController::class, 'getModulosByCurso'])->name('cursos.modulos');
    Route::get('/modulos/{modulo}/unidades', [App\Http\Controllers\ModuloController::class, 'getUnidadesByModulo'])->name('modulos.unidades');

    Route::get('/email-stats', [EmailStatsController::class, 'index'])->name('email.stats');
    Route::get('/email-stats/{contexto}', [EmailStatsController::class, 'detalleContexto'])->name('email.stats.contexto'); // ← AÑADE ESTA
    Route::get('/email-search', [EmailStatsController::class, 'buscar'])->name('email.search'); 
});



Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':academia'])
    ->prefix('academia')
    ->name('academia.')
    ->group(function () {
        Route::get('/', [AcademiaController::class, 'index'])->name('index');
        Route::get('/mis-cursos', [AcademiaController::class, 'misCursos'])->name('miscursos');
        Route::post('/asignar-curso/{curso}', [AcademiaController::class, 'asignarCurso'])->name('asignar_curso');
        Route::get('/mis-cursos/{cursoAcademico}', [AcademiaController::class, 'detalleCurso'])->name('detalleCurso');
        Route::get('/cursos', [AcademiaController::class, 'cursos'])->name('cursos');
        Route::post('/agregar-alumno', [AcademiaController::class, 'guardarAlumno'])->name('guardarAlumno');
        Route::put('/alumno/{id}/editar', [AcademiaController::class, 'actualizarAlumno'])->name('editarAlumno'); 
        Route::delete('/eliminar-alumno/{id}', [AcademiaController::class, 'eliminarAlumno'])->name('eliminarAlumno');
        Route::post('/actualizar-detalle', [AcademiaController::class, 'actualizarDetalle'])->name('actualizarDetalle');
        Route::post('/crear-detalle', [AcademiaController::class, 'crearDetalle'])->name('crearDetalle');
        Route::get('/calificaciones/{cursoAcademicoId}', [AcademiaController::class, 'showCalificaciones'])->name('calificaciones');
        Route::get('ver-docentes', [AcademiaController::class, 'verDocentes'])->name('ver_docentes');
        Route::put('/curso/{id}', [AcademiaController::class, 'actualizarCurso'])->name('curso_academico.update');
        Route::delete('/curso/{id}', [AcademiaController::class, 'destroyCursoAcademico'])->name('curso_academico.destroy');
        // Para unidades formativas
        Route::post('/guardar-nota', [AcademiaController::class, 'guardarNota'])->name('guardarNota');

   
    //Route::post('/calificaciones/guardar-modulo', [AcademiaController::class, 'guardarNotaModulo'])->name('guardarNotaModulo');
    Route::post('/guardar-nota', [AcademiaController::class, 'guardarNotaModulo'])->name('guardarNotaModulo');
    Route::post('/eliminar-calificacion', [AcademiaController::class, 'eliminarCalificacion'])->name('eliminarCalificacion');
    Route::post('/detalle/guardar', [AcademiaController::class,'crearActualizarDetalle'])->name('crearActualizarDetalle');
    Route::get('/obtener-email-docente/{docenteId}', [AcademiaController::class, 'obtenerEmailDocente'])->name('obtener-email-docente');
    Route::post('/enviar-mensaje-docente', [AcademiaController::class, 'enviarMensajeDocente'])->name('enviar_mensaje_docente');    
});




//Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
//Route::get('/calificaciones/{curso_academico_id}', [CalificacionController::class, 'showCalificaciones'])->name('calificaciones');
Route::put('/calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');

//Route::post('/calificaciones/guardar', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');
//Route::post('/calificaciones', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');
Route::post('/generar-actas/{grado}', [ActaController::class, 'generarActas'])->name('generar.actas');


Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':profesor'])
    ->prefix('profesor')
    ->name('profesor.') 
    ->group(function () {
        Route::get('/mis-cursos', [ProfesorController::class, 'misCursos'])->name('miscursos');
        Route::get('/cursos', [ProfesorController::class, 'cursos'])->name('cursos');
        Route::post('/asignar-curso/{curso}', [ProfesorController::class, 'asignarCurso'])->name('asignar_curso');
        Route::delete('/curso/{id}', [ProfesorController::class, 'destroy'])->name('curso.destroy');
        Route::get('/ver-academias', [ProfesorController::class, 'verAcademias'])->name('ver_academias');
        Route::put('curso/{id}/editar', [ProfesorController::class, 'actualizarCurso'])->name('curso.update');
        Route::get('curso/{id}', [ProfesorController::class, 'detalleCurso'])->name('detalleCurso');
        Route::post('/enviar-candidatura', [ProfesorController::class, 'enviarCandidatura'])->name('enviar_candidatura');
  
        // CORRECCIÓN: Quita '/profesor/' porque ya está en el prefijo
        Route::get('/obtener-email/{academiaId}', [ProfesorController::class, 'obtenerEmailAcademia'])->name('obtener-email');
    });


    // Rutas de Google OAuth
    Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);


    Route::post('/calificaciones', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');


    Route::post('/user/update-role', [App\Http\Controllers\UserController::class, 'updateRole'])
        ->name('user.updateRole')
        ->middleware('auth');



    Route::get('/debug-middleware', function() {
        return [
            'kernel_exists' => file_exists(app_path('Http/Kernel.php')),
            'middlewares' => [
                'TrustProxies' => file_exists(app_path('Http/Middleware/TrustProxies.php')),
                'CheckUserRole' => file_exists(app_path('Http/Middleware/CheckUserRole.php')),

            ]
        ];
    });

    // Rutas para alumnos
    Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':alumno'])->group(function () {
        Route::get('/alumno', [AlumnoController::class, 'index'])->name('alumno.index');
        Route::get('/alumno/academias', [AlumnoController::class, 'listarAcademias'])->name('alumno.academias');
        Route::get('/alumno/academia/{id}', [AlumnoController::class, 'verAcademia'])->name('alumno.academia.ver');
        Route::post('/alumno/contactar-academia', [AlumnoController::class, 'enviarEmailAcademia'])->name('alumno.academia.enviar_email');
        Route::get('/alumno/obtener-email/{id}', [AlumnoController::class, 'obtenerEmailAcademia'])->name('alumno.obtener.email');
    });








