<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\FamiliaProfesionalController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\UnidadFormativaController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\CursoAcademicoController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ProfesorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfesorCursoController;
use App\Http\Controllers\CursoModuloController;

use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


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


Route::get('/dashboard', function () {
    return view('dashboard'); // Asegúrate de que la vista "dashboard.blade.php" existe
})->middleware(['auth'])->name('dashboard');





// Grupo protegido por middleware y con prefijo
Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    
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
});





Route::middleware(['auth', 'rol:academia'])
    ->prefix('academia')
    ->name('academia.')
    ->group(function () {
        Route::get('/', [AcademiaController::class, 'index'])->name('index');
        Route::get('/mis-cursos', [AcademiaController::class, 'misCursos'])->name('miscursos');
        Route::post('/asignar-curso/{curso}', [AcademiaController::class, 'asignarCurso'])->name('asignar_curso');
        Route::get('/mis-cursos/{cursoAcademico}', [AcademiaController::class, 'detalleCurso'])->name('detalleCurso');
        Route::put('curso/{id}/editar', [AcademiaController::class, 'actualizarCurso'])->name('curso.update');
        Route::get('/cursos', [AcademiaController::class, 'cursos'])->name('cursos');
        //Route::put('/curso/{id}/editar', [AcademiaController::class, 'actualizarCurso'])->name('actualizarCurso');
        //Route::get('/agregar-alumno', [AcademiaController::class, 'agregarAlumno'])->name('agregarAlumno');
        Route::post('/agregar-alumno', [AcademiaController::class, 'guardarAlumno'])->name('guardarAlumno');
        // Route::get('/curso/{id}/alumnos', [AcademiaController::class, 'getAlumnos'])->name('getAlumnos');
        Route::put('/alumno/{id}/editar', [AcademiaController::class, 'actualizarAlumno'])->name('editarAlumno'); 
        Route::delete('/eliminar-alumno/{id}', [AcademiaController::class, 'eliminarAlumno'])->name('eliminarAlumno');
        // Route::get('/curso/{cursoAcademico}/detalles', [AcademiaController::class, 'detallesCurso'])->name('academia.detallesCurso');
        // Route::get('/curso/{cursoAcademicoId}/detalles', [AcademiaController::class, 'showCursoDetalles'])->name('academia.showCursoDetalles');
        // Route::get('/curso/{id}/detalles', [AcademiaController::class, 'verDetalles'])->name('detalles');
        // Route::post('/curso/{id}/detalles', [AcademiaController::class, 'guardarDetallesCurso'])->name('guardarDetalles');
        Route::post('/actualizar-detalle', [AcademiaController::class, 'actualizarDetalle'])->name('actualizarDetalle');
        Route::post('/crear-detalle', [AcademiaController::class, 'crearDetalle'])->name('crearDetalle');
        Route::get('/calificaciones/{cursoAcademicoId}', [AcademiaController::class, 'showCalificaciones'])->name('calificaciones');
        // Route::post('/calificaciones', [AcademiaController::class, 'storeCalificacion'])->name('calificaciones.store');
        Route::get('ver-docentes', [AcademiaController::class, 'verDocentes'])->name('ver_docentes');
        Route::delete('/academia/curso/{id}', [AcademiaController::class, 'destroyCursoAcademico'])->name('curso_academico.destroy');

        // Para unidades formativas
    Route::post('/guardar-nota', [AcademiaController::class, 'guardarNota'])->name('guardarNota');

    // Para módulos sin UF
    //Route::post('/calificaciones/guardar-modulo', [AcademiaController::class, 'guardarNotaModulo'])->name('guardarNotaModulo');
    Route::post('/guardar-nota', [AcademiaController::class, 'guardarNotaModulo'])->name('guardarNotaModulo');
    Route::post('/eliminar-calificacion', [AcademiaController::class, 'eliminarCalificacion'])->name('eliminarCalificacion');

    Route::post('/detalle/guardar', [AcademiaController::class,'crearActualizarDetalle'])->name('crearActualizarDetalle');

});




//Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
//Route::get('/calificaciones/{curso_academico_id}', [CalificacionController::class, 'showCalificaciones'])->name('calificaciones');
Route::put('/calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');

//Route::post('/calificaciones/guardar', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');
//Route::post('/calificaciones', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');
Route::post('/generar-actas/{grado}', [ActaController::class, 'generarActas'])->name('generar.actas');


Route::middleware(['auth', 'rol:profesor'])
    ->prefix('profesor')
    ->name('profesor.') 
    ->group(function () {
        Route::get('/mis-cursos', [ProfesorController::class, 'misCursos'])->name('miscursos');
        Route::get('/cursos', [ProfesorController::class, 'cursos'])->name('cursos');
        //Route::get('/cursos', [AcademiaController::class, 'cursos'])->name('academia.cursos');
        Route::post('/asignar-curso/{curso}', [ProfesorController::class, 'asignarCurso'])->name('asignar_curso');
        Route::delete('/curso/{id}', [ProfesorController::class, 'destroy'])->name('curso.destroy');
        Route::get('/ver-academias', [ProfesorController::class, 'verAcademias'])->name('ver_academias');

        Route::put('curso/{id}/editar', [ProfesorController::class, 'actualizarCurso'])->name('curso.update');
        Route::get('curso/{id}', [ProfesorController::class, 'detalleCurso'])->name('detalleCurso');
       // Route::put('curso/{id}/editar', [ProfesorController::class, 'update'])->name('curso.update');

    });





Route::post('/calificaciones', [CalificacionController::class, 'storeCalificacion'])->name('calificaciones.store');


