<?php

namespace App\Http\Middleware;

use Closure;

class AperturaCajaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->id_almacen == 0 && (auth()->user()->idrole != 1 && auth()->user()->idrole != 4) ){
            return redirect()->route('contenido'); 
        }
        return $next($request);
    }
}
