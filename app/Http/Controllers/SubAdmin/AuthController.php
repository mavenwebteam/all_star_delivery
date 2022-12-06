<?php

namespace App\Http\Controllers\SubAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth, Hash;
use Mail;
use Cookie;
use App\User;


/**
 * Class AuthController
 * @package App\Http\Controllers\SubAdmin
 * @version August 30, 2021, 12:14 pm IST
*/
class AuthController extends Controller
{
    /**
     * login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('sub_admin.login');
    }

    /**
     * login post.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required', 
            'password' => 'required',
        ]);
        if ($validator->fails()) 
        {
            return redirect()->route('subAdmin.login')->withErrors($validator)->withInput();
            die;
        }
        // if (Auth::check())
        // {
        //     Auth::logout();
        // }
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $userdata = array(
            $fieldType 	=> $request->input('email'),
            'password' 	=> $request->input('password'),
            'role_id' 	=>'5', //sub-admin
        );
        $remember_me = $request->has('remember') ? true : false; 
        if (Auth::attempt($userdata, $remember_me)){	
        
            if($remember_me)
            {
                setcookie ("email",$request->email,time()+ (86400 * 30));
                setcookie ("password",$request->password,time()+ (86400 * 30));
            }else{ 
                    unset($_COOKIE['email']);
                    setcookie('email', '', 1);
                    unset($_COOKIE['password']);
                    setcookie('password', '', 1);
            }
            return redirect()->route('subAdmin.dashboard');;
        }else{ 
            Session(['msg' => '<strong class="alert alert-danger">Invalid email and password.</strong>']);
            return redirect()->route('subAdmin.login');
        }
    }

    public function checkuserlogin()
	{
		if(!empty(Auth::user()) && Auth::user()->role_id == 5)
		{
			return 1;
		}else{
			return 2;
		}
		
	}

    public function logout()
    { 
		User::where('id', Auth::user()->id)->update(array('device_token' => "",'device_id'=>""));
		Auth::logout();
		return redirect('/sub-admin/login');
	}

    public function changePassword()
	{
		$admindata = Auth::user();
		return view('sub_admin.changePassword',['admindata'=>$admindata]);
	}
				
	public function changePasswordPost(Request $request)
	{
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$validator = Validator::make($request->all(), [
			'current_password' => 'required',
			'new_password' => 'required|custom_password|min:6|max:16',
			'password_confirmation' => 'required|min:6|max:16|same:new_password',
		],
		[
		"new_password.custom_password"	=>	trans('vendor.password_must_contain')
		]);
		if ($validator->fails()) {
			return response()->json(['toster_class'=>'error', 'msg'=> $validator->errors()->first()],400);
		}

		$user = User::find(Auth::user()->id);
		if(!Hash::check($request->current_password, Auth::user()->password)){
			return response()->json(['toster_class'=>'error', 'msg'=> trans('vendor.current_password_not_match')],400);
		} else {
			$password =  $request->new_password;
			$user->password = Hash::make($password);
			$user->save();
			return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.password_match_success')],200);
		}			
	}

    public function sendemail(Request $request)
	{
		$validator = Validator::make($request->all(), [
		'email' => 'required|email',
		'uniqcode' => 'required',
		]);
		if ($validator->fails()) {
		return redirect('/sub-admin/code-generate')
					->withErrors($validator)
					->withInput();

		}else{
			$email =  $request->input('email');
			$uniqcodeval =  $request->input('uniqcode');
			$data = array( 'email' => $email, 'from' => 'noreply@demoasite1.com', 'from_name' => 'Manta Play', 'data' => $uniqcodeval );
			Mail::send( 'admin.emailtemp', $data, function( $message ) use ($data)
			{
				$message->to( $data['email'] )->from( $data['from'], "manta play" )->subject( 'Sign Up Code' );
			});
			Session::put('msg', '<strong class="alert alert-success">Mail successfully sent.</strong>');
			return redirect('/admin');

		}
	}

	public function forbidden()
	{

		return view('sub_admin.403');
	}
}
