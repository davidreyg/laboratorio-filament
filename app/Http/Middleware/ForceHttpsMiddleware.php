<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /* Para que funcione el SPA. y para que carguen los ASSETS */
        if (app()->isProduction()) {
            $request->server->set('HTTPS', 'on');
            \URL::forceScheme('https');
        }

        return $next($request);
    }
}
