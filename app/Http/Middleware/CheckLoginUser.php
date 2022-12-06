<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckLoginUser
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
        if (Auth::check())
		{
            if(Auth::user()->role_id == 4){
                return redirect('/admin');
            }else{
                return redirect('/vendor');
            }
		}
       
        return $next($request);
    }
}
