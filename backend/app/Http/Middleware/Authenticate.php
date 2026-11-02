<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Las rutas de API nunca deben redirigir a una ruta web inexistente.
        // Sin token deben conservar la respuesta 401 de Sanctum en formato JSON.
        return ($request->expectsJson() || $request->is('api/*')) ? null : route('login');
    }
}
