<?php

namespace App\Http\Middleware;

use Closure;

class administratorMiddleware
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
        if(isset(auth()->user()->id)){
            if(auth()->user()->u_type == 'administrator'){
                return $next($request);
            }else{
                return redirect()->to("/");
            }
        }else{
            return redirect()->to("/");
        }
       
       
    }
}
