<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use view;
class CheckCompanyuser
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
		if ($request->apikey != "12345") {
            return "a";
			exit;
        }
         return $next($request);
        
    }
	
}
