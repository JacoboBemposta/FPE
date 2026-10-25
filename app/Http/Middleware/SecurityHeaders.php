<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Cabeceras de seguridad
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');

        // Content-Security-Policy (CSP) 

$csp = "default-src 'self'; " .
       "script-src 'self' https://cdn.jsdelivr.net https://code.tidio.co 'unsafe-inline'; " .
       "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com 'unsafe-inline'; " .
       "font-src * data:; " .
       "img-src 'self' data: https://code.tidio.co https://developers.google.com; " .
       "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare wss://socket.tidio.co https://socket.tidio.co; " . // Añadido WebSocket
       "media-src 'self' https://code.tidio.co; " .      // Añadido para sonidos
       "frame-src 'self' https://code.tidio.co; " .      // Añadido para iframes de Tidio
       "frame-ancestors 'self'; " .
       "object-src 'none'; " .
       "base-uri 'self'";

        $response->headers->set('Content-Security-Policy', $csp);

        // (Opcional) Ocultar versión del servidor
        $response->headers->remove('Server');
        $response->headers->set('Server', 'FPE-Server');

        return $response;
    }
}