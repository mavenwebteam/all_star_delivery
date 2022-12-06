<?php

namespace App\Http\Middleware;

use Closure;
use Session,Auth,Redirect;
class outletSecurity
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
			return Redirect::to('/outlet/login');
		}
        
		
		if(Auth::user()->type != 4){
			
			if(Auth::user()->type  == 5 || Auth::user()->type == 6)
			{
				return Redirect::to('/admin');
			}elseif(Auth::user()->type  == 1){
				
				return Redirect::to('/vendor');
			}else{
				return Redirect::to('/'); 
			}
			
		}
        return $next($request);
		
    }
}
