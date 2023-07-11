<?php

namespace App\Http\Middleware;

use Closure;

class superadministratorMiddleware
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
        if(auth()->user()->u_type == 'superadministrator'){
            return $next($request);
        }else{
            return redirect()->to("/");
        }
    }
}
