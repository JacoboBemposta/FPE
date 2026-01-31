<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
        // Añade tus middlewares personalizados AQUÍ
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'alumno' => \App\Http\Middleware\AlumnoMiddleware::class,
            // Agrega otros si los necesitas
        ]);
        
        // Si necesitas middleware global (opcional)
        // $middleware->append(\App\Http\Middleware\CheckUserRole::class);
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        //
    })->create();