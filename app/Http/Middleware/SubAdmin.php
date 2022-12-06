<?php

namespace App\Http\Middleware;

use Closure;
use Session,Auth,Redirect;
use Log;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;
use View;

class SubAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)  
    {
        $user = Auth::user();
        if (Auth::guest())
		{ 
			return Redirect::to('/sub-admin/login');
		}
		if($user->role_id == 5)
		{
            $userId = $user->id;

            $currentRoute = Route::currentRouteName();
            $permission = Permission::where('name', $currentRoute)->with(['userHasPermission'=> function($q) use($userId){
				$q->where('user_id', $userId);
			}])->first();
            $hasPermission = object_get($permission, 'userHasPermission.user_id', NULL);

            if($hasPermission){
                $permissionData = Permission::with(['userHasPermission'=> function($q) use($userId){
                    $q->where('user_id', $userId);
                }])->get();
                
                $permissions = array();
                foreach($permissionData as $value){
                    $check = object_get($value, 'userHasPermission.user_id', NULL);
                    if(!$check){
                        continue;
                    }
                    array_push($permissions, $value->name);
                }
                View::share('permissionData', $permissions);
                return $next($request);
            }else{
                return redirect()->route('subAdmin.403.forbidden');
            }
		}
		else {
			return Redirect::to('/'); 
		}
    }
}
