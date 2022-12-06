<?php

namespace App\Http\Middleware;

use Closure;
use App;

class localization
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
        if($request->hasHeader('x-localization'))
        {
            // Check header request and determine localizaton
            $local = ($request->hasHeader('x-localization')) ? $request->header('x-localization') : 'en';
            // set laravel localization
            app()->setLocale($local);
        }
        if(session()->has('lang'))
        {
            App::setLocale(session()->get('lang'));
        }
        
        // continue request
        return $next($request);
    }
}
