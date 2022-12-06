<?php

namespace App\Http\Middleware;

use Closure;
use Session,Auth,Redirect;
class adminSecurity
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
			return Redirect::to('/admin/login');
		}
	
		if(Auth::user()->role_id  == 4)
		{
			return $next($request);
		}
		else if(Auth::user()->role_id  == 3)
		{
			return Redirect::to('/vendor');
		}
		else{
			return Redirect::to('/'); 
		}
		
    }
}
