<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\FamiliaProfesionalController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\UnidadFormativaController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Middleware\AdminMiddleware;
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

Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('panel');

    // Rutas para crear
    Route::post('/familias_profesionales', [FamiliaProfesionalController::class, 'store'])->name('familia.store');
    Route::post('/curso/store', [CursoController::class, 'store'])->name('curso.store');
    Route::post('/modulos', [ModuloController::class, 'store'])->name('modulo.store');
    Route::post('/unidad', [UnidadFormativaController::class, 'store'])->name('unidad.store');

    // Rutas para eliminar
    Route::delete('/familia/{id}', [FamiliaProfesionalController::class, 'destroy'])->name('familia.destroy');
    Route::delete('/curso/{id}', [CursoController::class, 'destroy'])->name('curso.destroy');
    Route::delete('/modulo/{id}', [ModuloController::class, 'destroy'])->name('modulo.destroy');
    Route::delete('/unidad/{id}', [UnidadFormativaController::class, 'destroy'])->name('unidad.destroy');
});



Route::get('/cursos/{familiaId}', [CursoController::class, 'getCursosByFamilia']);


Route::middleware(['auth', 'rol:academia'])
    ->prefix('academia')
    ->name('academia.')
    ->group(function () {
        Route::get('/', [AcademiaController::class, 'index'])->name('index');
        Route::get('/cursos', [AcademiaController::class, 'cursos'])->name('cursos');
        Route::get('/mis-cursos', [AcademiaController::class, 'misCursos'])->name('miscursos');
        Route::post('/asignar-curso/{curso}', [AcademiaController::class, 'asignarCurso'])->name('asignar_curso');
        Route::get('/mis-cursos/{cursoAcademico}', [AcademiaController::class, 'detalleCurso'])->name('detalleCurso');
        Route::put('/curso/{id}/editar', [AcademiaController::class, 'actualizarCurso'])->name('actualizarCurso');
        Route::get('/agregar-alumno', [AcademiaController::class, 'agregarAlumno'])->name('agregarAlumno');
        Route::post('/agregar-alumno', [AcademiaController::class, 'guardarAlumno'])->name('guardarAlumno');
        Route::get('/curso/{id}/alumnos', [AcademiaController::class, 'getAlumnos'])->name('getAlumnos');
        Route::put('/alumno/{id}/editar', [AcademiaController::class, 'actualizarAlumno'])->name('editarAlumno'); // Cambiar a PUT
        Route::delete('/eliminar-alumno/{id}', [AcademiaController::class, 'eliminarAlumno'])->name('eliminarAlumno');
        Route::get('/curso/{cursoAcademico}/detalles', [AcademiaController::class, 'detallesCurso'])->name('academia.detallesCurso');
        Route::get('/curso/{cursoAcademicoId}/detalles', [AcademiaController::class, 'showCursoDetalles'])->name('academia.showCursoDetalles');
        Route::get('/curso/{id}/detalles', [AcademiaController::class, 'verDetalles'])->name('detalles');
        Route::post('/curso/{id}/detalles', [AcademiaController::class, 'guardarDetallesCurso'])->name('guardarDetalles');
        Route::post('/actualizar-detalle', [AcademiaController::class, 'actualizarDetalle'])->name('actualizarDetalle');
        Route::post('/crear-detalle', [AcademiaController::class, 'crearDetalle'])->name('crearDetalle');
        Route::get('/calificaciones/{cursoAcademicoId}', [AcademiaController::class, 'showCalificaciones'])->name('calificaciones');
        Route::post('/calificaciones', [AcademiaController::class, 'storeCalificacion'])->name('calificaciones.store');

        
    });

Route::middleware(['auth', 'rol:academia'])->group(function () {
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');

});
Route::get('/calificaciones/{curso_academico_id}', [CalificacionController::class, 'showCalificaciones'])->name('calificaciones');

Route::put('/calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');
Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
