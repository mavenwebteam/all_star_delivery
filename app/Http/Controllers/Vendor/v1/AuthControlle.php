<?php

namespace App\Http\Controllers\Vendor\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Validator;
use App\User;
use App\Uniqcode;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config, Input, URL, Mail;
use App\Models\Emailtemplates;
use App\Rules\UserRoleAlreadyExist;
use Illuminate\Support\Str;


class AuthControlle extends Controller
{
    public function signup()
	{
        if (Auth::check())
		{
			return redirect('/vendor');
		}
        $countries = DB::table('countries')->orderBy('name','ASC')->get();
		return view('vendor.auth.signup', compact('countries'));
    }

	/**
     * Vendor Signu Store
     * * @param Http/Request $request
     * * @function custome rule UserRoleAlreadyExist(roleId, email, mobile,'mobile|email')
     * @return Http/Response
     */
    public function signupStore(Request $request)
    {
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		
        $validator = Validator::make($request->all(), [
        'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:15|min:2',
        'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:15|min:2',
        'email' => ['required', 'max:50', 'email', new UserRoleAlreadyExist(3, $request->email, NULL, 'email')],
        'mobile' => ['required','regex:/[0-9]{9}/', new UserRoleAlreadyExist(3, NULL, $request->mobile, 'mobile')],
        'password' => 'required|min:8|custom_password',
        'confirm_password' 	=> 'required|min:8|same:password',
        'country_code' => 'required',
        'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
        ],
        [
        "first_name.max" =>'The first name may not be greater than 15 characters.',
        "last_name.max" =>'The last name may not be greater than 15 characters.',
        "password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters."
        ])->validated();
        
            $otp = Helper::__generateNumericOTP(4);
            $user = new User();
            $user->status = 0;
            $user->role_id = 3;
            $user->uu_id = (string) Str::uuid();
            $user->otp = $otp;
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->country_code = $request->input('country_code');
            $user->mobile = $request->input('mobile');
            $user->password = Hash::make($request->input('password'));
            $user->save();
           
           
            $emailData = Emailtemplates::where('slug', '=', 'user-registration')->first();
            if ($emailData) {
                $textMessage = strip_tags($emailData->description);
                $user->subject = $emailData->subject;
                $activate_url = \App::make('url')->to("account-activate/" . base64_encode($user->id));
                if ($user->email != '') {
                    $textMessage = str_replace(array('{USERNAME}', '{{ACTIVE_URL}}'), array($user->first_name, $activate_url), $textMessage);
                  
                    Mail::raw($textMessage, function ($messages) use ($user) {
                        $to = $user->email;
                        $messages->to($to)->subject($user->subject);
                    });
                }
            }
           
            $message = "Your otp is: ".$otp;
            $email = $request->input('email');
            Helper::__sendOtp($request->input('mobile') , $message);
          
            return redirect()->route('vendor.otp.form', base64_encode($user->id)); 
    }

	public function resendOtp(Request $request)
	{
		if(request('email'))
		{
			$user = DB::table('users')->where('email','=',request('email'))
					->where('role_id', 3)
					->first();
			if($user && $user->otp && $user->mobile)
			{
				$email = $user->email;
				$otp = $user->otp;
				$mobile = $user->mobile;
				$message = "Your otp is: ".$otp;
				Helper::__sendOtp($mobile , $message);
				$request->session()->flash('success', 'Otp has been resend successfully');
				return view('vendor.auth.verifyOtp', compact('otp','email'));
				die;
			}
			$request->session()->flash('fail', 'Resend failed');
			return redirect()->back();
		}
		$request->session()->flash('fail', 'Resend failed');
		return redirect()->back();
	}


    public function formOtp($id)
	{
        if (Auth::check())
		{
			return redirect('/vendor');
		}
        $user = User::find(base64_decode($id));
        if($user)
        {
            $otp = $user->otp;
            $email = $user->email;
            return view('vendor.auth.verifyOtp', compact('otp','email'));
        }else{
           return redirect()->back()->with('msg', 'User not found.');
        }
    }

    public function verifyOtp(Request $request)
	{
        if (Auth::check())
		{
			return redirect('/vendor');
		}
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
        ])->validated();
        $otp = $request->get('otp');
        $email = $request->get('email');
        $user = User::where('email',$email)
				->where('role_id', 3)
				->first();

        if($user->otp == $otp)
        {
			$user->otp = '';
            $user->is_mobile_verify = 1;
            $user->status = '1';
            $user->save();
            $request->session()->flash('success', 'Otp has been verify successfully, Login now');
            return redirect('/vendor/login');
        }
        $request->session()->flash('fail', 'Otp does not match');
		return view('vendor.auth.verifyOtp', compact('otp','email'));
    }

    public function login()
	{
		return view('vendor.login');
	}

    public function checkuserlogin()
	{
		if(!empty(Auth::user()))
		{
			return 1;
		}else{
			return 2;
		}
		
	}	
	
	public function loginpost(Request $request)
	{
	   $validator = Validator::make($request->all(), [ 
            'email' => 'required', 
	        'password' => 'required'
	    ]);
		if ($validator->fails()) 
		{
		 return redirect('/vendor/login')->withErrors($validator)->withInput();
		}else
		{
			$fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
			$user = User::select('email_verify','status','is_mobile_verify')
			->where($fieldType, $request->input('email'))
			->where('role_id',3)
			->first();
		
			if($user)
			{
				if($user->status != 1)
				{
					Session(['msg' => '<strong class="alert alert-danger">Account is not activated.</strong>']);
					return redirect('/vendor/login');
					die;
				}

				if($fieldType == 'email')
				{
					if($user->email_verify != 'yes'){
						Session(['msg' => '<strong class="alert alert-danger">Email id not verify yet.</strong>']);
						return redirect('/vendor/login');
						die;
					}

					$userdata = array(
						$fieldType		=> $request->input('email'),
						'password' 		=> $request->input('password'),
						'is_admin' 		=> '0',
						'role_id'  		=> '3',
						'status' 	    => '1',
						'email_verify'  => 'yes',	
					);
				}else{
					if($user->is_mobile_verify != 1)
					{
						Session(['msg' => '<strong class="alert alert-danger">Mobile number not verify yet.</strong>']);
						return redirect('/vendor/login');
						die;
					}
					$userdata = array(
						$fieldType		=> $request->input('email'),
						'password' 		=> $request->input('password'),
						'is_admin' 		=> '0',
						'role_id'  		=> '3',
						'status' 	    => '1',
                        'is_mobile_verify' => '1'
					);
				}
				$remember_me = $request->has('remember') ? true : false; 
				if (Auth::attempt($userdata, $remember_me)){
					$users = DB::table('users')->where($fieldType,$request->input('email'))->first();	
					User::where('id', $users->id)->update(array('device_token' => $request->input('device_token')));
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
					return redirect('/vendor');
				}else{ 
					Session(['msg' => '<strong class="alert alert-danger">Please enter valid email and password.</strong>']);
					return redirect('/vendor/login');
				}
			}else{
				Session(['msg' => '<strong class="alert alert-danger">Please enter valid email and password.</strong>']);
				return redirect('/vendor/login');
			}
		}
		
	}

    public function logout()
    {
		User::where('id', Auth::user()->id)->update(array('device_token' => "",'device_id'=>""));
		Auth::logout();
		return redirect('/vendor/login');		  
	}
}
