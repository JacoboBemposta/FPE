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
        // Middlewares con alias (para rutas específicas)
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'alumno' => \App\Http\Middleware\AlumnoMiddleware::class,
        ]);

        // Middleware global: TU PROPIO SecurityHeaders
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Si tenías AddReferrerPolicy, elimínalo (ahora está incluido en SecurityHeaders)
        // $middleware->append(\App\Http\Middleware\AddReferrerPolicy::class);

        // Si el paquete make-dev/laravel-security registraba su middleware, asegúrate de comentarlo:
        // $middleware->append(\MakeDev\LaravelSecurity\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        //
    })->create();