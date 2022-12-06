<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use Redirect;
class Checkuser
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
			return Redirect::to('/');
		}
		if(Auth::user()->type != 0){
			
			if(Auth::user()->type  == 4 || Auth::user()->type == 5)
			{
				return Redirect::to('/admin');
			}else{
				return Redirect::to('/vendor'); 
			}
			
		}
        return $next($request);
		
    }
	
}
