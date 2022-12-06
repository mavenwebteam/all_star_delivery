<?php

namespace App\Http\Middleware;

use Closure;
use Session,Auth,Redirect;
class vendorSecurity
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
        if (Auth::guest())
		{ 
			return Redirect::to('/vendor/login');
		}
		if(Auth::user()->role_id != 3){
			Auth::logout();
			return Redirect::to('/vendor');
		}

		if(Auth::user()->status != 1){
			Auth::logout();
			return Redirect::to('/vendor');
		}
        return $next($request);
		
    }
}
