<?php

namespace App\Http\Middleware;

use Closure;

class MainMiddleware
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
        if($request->user()->id_almacen != 0){
            return redirect()->route('main'); 
        }
        return $next($request);
    }
}
