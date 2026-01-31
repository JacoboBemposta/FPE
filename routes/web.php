<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\SuscripcionConfigController;
use App\Http\Controllers\CursoModuloController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FamiliaProfesionalController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\UnidadFormativaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\StripeSyncController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ActaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');



Route::get('/', function () {
    return view('welcome');
})->name('home');

// Ruta específica para actualizar rol (primera vez)
Route::post('/user/update-role', [UserController::class, 'updateRole'])
    ->name('user.updateRole')
    ->middleware('auth');

// Ruta específica para actualizar perfil completo
Route::put('/user/profile', [UserController::class, 'updateProfile'])
    ->name('user.updateProfile')
    ->middleware('auth');

// Rutas para eliminación de cuenta (GDPR)
Route::middleware(['auth'])->group(function () {
    Route::delete('/user/destroy', [UserController::class, 'destroy'])
        ->name('user.destroy')
        ->middleware('throttle:300,1440'); // Máximo 3 intentos por día
});

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

Auth::routes(['verify' => true]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas que requieren email verificado
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});

// Rutas para el footer
Route::get('/sobre-nosotros', function () {
    return view('about');
})->name('about');

Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

Route::get('/academias', function () {
    return view('academias');
})->name('academias');

Route::get('/docentes', function () {
    return view('docentes');
})->name('docentes');

Route::get('/alumnos', function () {
    return view('alumnos');
})->name('alumnos');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

// ADMIN ROUTES
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('panel'); 

    // Familias Profesionales
    Route::post('/familias-profesionales', [FamiliaProfesionalController::class, 'store'])->name('familias-profesionales.store');
    
    // Cursos
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    
    // Unidades
    Route::post('/unidades', [UnidadFormativaController::class, 'store'])->name('unidades.store');
    Route::post('/cursos/{curso}/modulos', [CursoModuloController::class, 'store'])->name('cursos.modulos.store');
    
    // Eliminar
    Route::delete('/familia/{id}', [FamiliaProfesionalController::class, 'destroy'])->name('familia.destroy');
    Route::delete('/cursos/{curso}/modulos/{modulo}', [CursoModuloController::class, 'destroy'])->name('cursos.modulos.destroy');
    Route::delete('unidades/{unidad}', [UnidadFormativaController::class, 'destroy'])->name('unidades.destroy');

    // Unidades formativas por módulo
    Route::get('/modulo/{modulo}/unidades', [UnidadFormativaController::class, 'index'])->name('modulos.unidades.index');
    Route::get('/modulo/{modulo}/unidades/create', [UnidadFormativaController::class, 'create'])->name('modulos.unidades.create');
    Route::get('/modulo/{modulo}/unidades/{id}/edit', [UnidadFormativaController::class, 'edit'])->name('modulos.unidades.edit');
    Route::put('/modulo/{modulo}/unidades/{id}', [UnidadFormativaController::class, 'update'])->name('modulos.unidades.update');
    
    // Rutas AJAX
    Route::get('/familias/{familia}/cursos', [FamiliaProfesionalController::class, 'getCursosByFamilia'])->name('familias.cursos');
    Route::get('/cursos/{curso}/modulos', [CursoController::class, 'getModulosByCurso'])->name('cursos.modulos');
    Route::get('/modulos/{modulo}/unidades', [ModuloController::class, 'getUnidadesByModulo'])->name('modulos.unidades');

    // Suscripciones
    Route::post('/toggle-suscripciones', [SuscripcionConfigController::class, 'toggleSuscripciones']);
    Route::get('/estado-suscripciones', [SuscripcionConfigController::class, 'getEstado']);

    // Estadísticas
    Route::get('/stats', [AdminStatsController::class, 'index'])->name('stats');
    Route::get('/stats/email/{id}', [AdminStatsController::class, 'detalleEmail'])->name('stats.email');
    Route::get('/stats/emails/contexto/{contexto}', [AdminStatsController::class, 'detalleContexto'])->name('stats.contexto');
    Route::get('/stats/emails/buscar', [AdminStatsController::class, 'buscarEmails'])->name('stats.buscar-emails');
    Route::get('/stats/datos-graficos', [AdminStatsController::class, 'datosGraficos'])->name('stats.datos-graficos');
});

// ACADEMIA ROUTES
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
        Route::get('ver-docentes', [AcademiaController::class, 'verDocentes'])->name('ver_docentes');
        Route::put('/curso/{id}', [AcademiaController::class, 'actualizarCurso'])->name('curso_academico.update');
        Route::delete('/curso/{id}', [AcademiaController::class, 'destroyCursoAcademico'])->name('curso_academico.destroy');
        


        Route::post('/guardar-nota', [AcademiaController::class, 'guardarNotaModulo'])->name('guardarNotaModulo');
        Route::post('/eliminar-calificacion', [AcademiaController::class, 'eliminarCalificacion'])->name('eliminarCalificacion');
        Route::post('/detalle/guardar', [AcademiaController::class,'crearActualizarDetalle'])->name('crearActualizarDetalle');
            // Calificaciones
        Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
        Route::put('/calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');
        Route::get('/calificaciones/{cursoAcademicoId}', [AcademiaController::class, 'showCalificaciones'])->name('calificaciones');
        Route::post('/generar-actas/{grado}', [ActaController::class, 'generarActas'])->name('generar.actas');

        
        // Comunicación
        Route::get('/obtener-email-docente/{docenteId}', [AcademiaController::class, 'obtenerEmailDocente'])->name('obtener-email-docente');
        Route::post('/enviar-mensaje-docente', [AcademiaController::class, 'enviarMensajeDocente'])->name('enviar_mensaje_docente');
        
        // Archivar/restaurar
        Route::put('/curso_academico/{id}/archive', [AcademiaController::class, 'archive'])->name('curso_academico.archive');
        Route::put('/curso_academico/{id}/restore', [AcademiaController::class, 'restore'])->name('curso_academico.restore');
        Route::get('/cursos_archivados', [AcademiaController::class, 'cursosArchivados'])->name('cursos_archivados');

        // Suscripciones
        Route::get('/verificar-sistema-suscripciones', function() {
            $sistemaActivo = \App\Helpers\SistemaHelper::sistemaSuscripcionesActivo();
            
            return response()->json([
                'sistema_activo' => $sistemaActivo
            ]);
        });
        
        Route::get('/verificar-mi-suscripcion', function() {
            $user = Auth::user();
            
            return response()->json([
                'tiene_suscripcion_valida' => $user->tieneSuscripcionActiva(),
                'premium' => $user->premium,
                'fin_suscripcion' => $user->fin_suscripcion,
                'inicio_suscripcion' => $user->inicio_suscripcion,
            ]);
        })->name('profesor.verificar_suscripcion');

        // Stripe Sync
        Route::get('/stripe/sync', function() {
            return view('admin.sync-stripe');
        })->name('admin.stripe.sync');
        
        Route::post('/stripe/sync-all', [StripeSyncController::class, 'syncAllSubscriptions'])
            ->name('admin.stripe.sync-all');
        
        Route::get('/stripe/sync-status', [StripeSyncController::class, 'checkSyncStatus'])
            ->name('admin.stripe.sync-status');
        
        Route::post('/stripe/sync/{id}', [StripeSyncController::class, 'syncSubscription'])
            ->name('admin.stripe.sync-single');        
});

// PROFESOR ROUTES
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':profesor'])
    ->prefix('profesor')
    ->name('profesor.')
    ->group(function () {
        Route::get('/', [ProfesorController::class, 'index'])->name('index');
        Route::get('/mis-cursos', [ProfesorController::class, 'misCursos'])->name('miscursos');
        Route::get('/cursos', [ProfesorController::class, 'cursos'])->name('cursos');
        Route::post('/asignar-curso/{curso}', [ProfesorController::class, 'asignarCurso'])->name('asignar_curso');
        Route::delete('/curso/{id}', [ProfesorController::class, 'destroy'])->name('curso.destroy');
        Route::get('/ver-academias', [ProfesorController::class, 'verAcademias'])->name('ver_academias');
        Route::put('curso/{id}/editar', [ProfesorController::class, 'actualizarCurso'])->name('curso.update');
        Route::get('curso/{id}', [ProfesorController::class, 'detalleCurso'])->name('detalleCurso');
        Route::post('/enviar-candidatura', [ProfesorController::class, 'enviarCandidatura'])->name('enviar_candidatura');
        
        Route::get('/obtener-email/{academiaId}', [ProfesorController::class, 'obtenerEmailAcademia'])
            ->name('obtener-email');

        Route::get('/verificar-mi-suscripcion', function() {
            $user = Auth::user();
            
            $tieneSuscripcionValida = false;
            
            if ($user->suscripcion && $user->suscripcion->estaActiva()) {
                $tieneSuscripcionValida = true;
            }
            
            return response()->json([
                'tiene_suscripcion_valida' => $tieneSuscripcionValida
            ]);
        })->name('profesor.verificar_suscripcion');
});

// ALUMNO ROUTES  
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':alumno'])
    ->prefix('alumno')
    ->name('alumno.')
    ->group(function () {
        Route::get('/', [AlumnoController::class, 'index'])->name('index');
        Route::get('/academias', [AlumnoController::class, 'listarAcademias'])->name('academias');
        Route::get('/academia/{id}', [AlumnoController::class, 'verAcademia'])->name('academia.ver');
        Route::post('/contactar-academia', [AlumnoController::class, 'enviarEmailAcademia'])->name('academia.enviar_email');
        Route::get('/obtener-email/{id}', [AlumnoController::class, 'obtenerEmailAcademia'])->name('obtener.email');
});

// Rutas de Google OAuth
Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);




Route::middleware('auth')->group(function () {
    Route::get('/suscripcion', [SuscripcionController::class, 'index'])->name('suscripciones.index');
    Route::get('/suscripcion/detalles', [SuscripcionController::class, 'detalles'])->name('suscripcion.detalles');
    Route::post('/suscripcion/checkout', [SuscripcionController::class, 'checkout'])->name('suscripcion.checkout');
    Route::get('/suscripcion/success', [SuscripcionController::class, 'success'])->name('suscripcion.success');
    Route::get('/suscripcion/cancel', [SuscripcionController::class, 'cancel'])->name('suscripcion.cancel');
    Route::post('/suscripcion/cancelar', [SuscripcionController::class, 'cancelarSuscripcion'])->name('suscripcion.cancelar');
    Route::get('/suscripcion/planes', [SuscripcionController::class, 'detalles'])->name('suscripcion.planes');
});

// Webhook de Stripe (sin CSRF)
Route::post('/stripe/webhook', [SuscripcionController::class, 'handleWebhook'])->name('stripe.webhook');

Route::get('/suscripcion/test', [SuscripcionController::class, 'testSuccess'])
    ->middleware('auth')
    ->name('suscripcion.test');


   
        Route::post('/generar-actas/{grado}', [ActaController::class, 'generarActas'])->name('generar.actas');




Route::get('/test', function () {
    return view('test');
});