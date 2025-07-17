<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle unauthenticated requests.
     */
    protected function redirectTo($request): ?string
    {
        // Para APIs (retorna null para que Sanctum responda con JSON)
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }

        // Solo para aplicaciones web (opcional, si usas web)
        return route('login');
    }
}