<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Constants\Constant;
use Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
	{
		$userId = base64_decode($request->user);
		$count = User::where(['id'=>$userId, 'role_id'=>5])->count();
		if($count == 1){
			$permissions = Permission::with(['userHasPermission'=> function($q) use($userId){
				$q->where('user_id', $userId);
			}]);
			if(!empty($request->title)){
				$permissions = $permissions->where('title', 'LIKE', '%'.$request->title.'%');
			}
			$permissions = $permissions->paginate(Constant::ADMIN_RECORD_PER_PAGE);
			if($request->ajax()){
				return view('admin.permission.search', compact('permissions','userId'));
			}
			return view('admin.permission.index', compact('permissions','userId'));
		}else{
			return redirect()->route('admin.sub-admin.index');
		}
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [		
			'permission_id' => 'required|exists:permissions,id',
			'user_id' => 'required|exists:users,id',
		]);
		if ($validator->fails()) 
		{
			return response()->json(['success'=>false ,'errors'=>$validator->errors()->first()]);
		}else
		{
			$exist = self::hasPermission($request->user_id, $request->permission_id);
			if($exist){
				RoleHasPermission::where(
					[
						'user_id' => $request->user_id,
						'permission_id' => $request->permission_id
					]
				)->delete();
				return response()->json(['success'=>true ,'message'=>'Permission removed!']);
			}else{
				RoleHasPermission::create(
					[
						'user_id' => $request->user_id,
						'permission_id' => $request->permission_id,
						'role_id'=>5
					]);
					return response()->json(['success'=>true ,'message'=>'Permission granted!']);
			}
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userId = base64_decode($id);
		$userdata = User::find($userId);
		return view('admin.sub-admin.view',["userdata" => $userdata]);
    }


	/**
     * Used to check permissin granted or not for specific role 
     * @return true||false
    */
    private function hasPermission(int $user_id, int $permission_id)
    {
        $exist = RoleHasPermission::where('user_id',$user_id)
            ->where('permission_id',$permission_id)
            ->count('id');
        
        return $exist === 1 ? true : false;
    }
}
