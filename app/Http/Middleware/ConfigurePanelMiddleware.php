<?php

namespace App\Http\Middleware;

use App\Events\PanelConfigured;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurePanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        PanelConfigured::dispatch();

        return $next($request);
    }
}
