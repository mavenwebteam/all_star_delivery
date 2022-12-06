<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Facades\URL;
use DB;
use Log;
use Illuminate\Support\Facades\Event;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // Log::debug('boot-call');
      // Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
      //     // time in MS
      //     if (!str_contains($query->sql, 'oauth')) {
      //         Log::debug($query->sql . ' - '.$query->time);
      //     }
      // });

		// URL::forceScheme('https');
    
    }
}
