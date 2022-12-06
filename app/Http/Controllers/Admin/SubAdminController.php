<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Constants\Constant;
use DB;
use Session,Input;
use Mail, Hash;
use Illuminate\Support\Str;
use App\Rules\UserRoleAlreadyExist;
use Validator;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
	{	
		$userdata = User::where('is_admin','0')->where('is_deleted',0)->where('role_id','5')->orderBy("created_at","DESC");
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');
		$first_name=$request->input('first_name');
		$last_name=$request->input('last_name');
		$email=$request->input('email');
		$mobile=$request->input('mobile');
		$status=$request->input('status');
		$perpage=$request->input('perpage');

		if($email!="")
		{ 
			$userdata=$userdata->where('email',$email);
		}
		if($mobile!="")
		{ 
			$userdata=$userdata->where('mobile','LIKE',"%$mobile%");
		}
		if($status!="")
		{ 
			$userdata=$userdata->where('status',$status);
		}
		if($first_name!="")
		{ 
			$userdata=$userdata->where('users.first_name','like',"%$first_name%");
		}
		if($last_name!="")
		{ 
			$userdata=$userdata->where('users.last_name','like',"%$last_name%");
		}
		if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$userdata = $userdata->whereBetween('created_at', [$start_date, $end_date]);
		} else if ($start_date!="" && $end_date=="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$userdata =$userdata->where('created_at',">=",$start_date);
		} else if ($start_date=="" && $end_date!="") {
			$end_date = date('Y-m-d H:i:s', strtotime($end_date));
			$userdata = $userdata->where('created_at',"<=",$end_date);
		}
		if(!empty($perpage)){
		    $userdata = $userdata->paginate($perpage);
		} else {
			$userdata = $userdata->paginate(Constant::ADMIN_RECORD_PER_PAGE);
		}
		if ($request->ajax()) 
		{
			return view('admin.sub-admin.search', compact('userdata'));
			die;
        }
        return view('admin.sub-admin.show', compact('userdata'));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countrydata = DB::table('countries')->get();
		$countrycode_box=array(''=>'Select Country Code');
		foreach($countrydata as $key=>$value){
			$countrycode_box[$value->phonecode]=$value->name.'('.$value->phonecode.')';
		} 
		return view('admin.sub-admin.add',compact('countrycode_box'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Input::replace($this->arrayStripTags(Input::all()));
		$formData	=	Input::all();
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		
		$validator = Validator::make($request->all(), [
		
		'first_name1' => 'required|alpha|max:15|min:2',
		'last_name' => 'required|alpha|max:15|min:2',
		'email' => ['required','max:50','email',new UserRoleAlreadyExist(5, $request->email, NULL, 'email')],
		'username' => 'required|max:50|min:4|unique:users,username',
		'mobile' => ['required','regex:/[0-9]{9}/','min:7','max:12', new UserRoleAlreadyExist(5, NULL, $request->mobile, 'mobile')],
		'password' => 'required|min:8|custom_password|max:16',
		'confirm_password' 	=> 'required|min:8|same:password',
		'country_code' => 'required|integer|min:0',
		'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
		],
		[
		"password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters.",
		"first_name1.required" =>'The first name field is required.',
		"first_name1.alpha" =>'The first name may only contain letters.',
		"first_name1.max" =>'The first name may not be greater than 15 characters.',
		]);
		if ($validator->fails()) 
		{
			return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
		}else
		{
			$user = new User();
			
			$first_name =  $request->input('first_name1');
			$last_name =  $request->input('last_name');
			$email =  $request->input('email');
			$mobile =  $request->input('mobile');
			$password =  $request->input('password');
			$country_code =  $request->input('country_code');
			$image = $request->file('profile_pic');

			if(isset($image))
            {
                $imageName = time().rand(0,999).$image->getClientOriginalExtension();
                $image->move(public_path().'/media/users', $imageName);
                $user->profile_pic = $imageName;
            }

			$user->role_id = 5;
			
			$user->status = '1';
			$user->email_verify = 'yes';
			$user->is_mobile_verify = 1;
			$user->uu_id = (string) Str::uuid();
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->email = $email;
			$user->username = strtolower($request->username);
			
			$user->country_code = $country_code;
			$user->mobile = $mobile;
			$user->password = Hash::make($password);
			$user->address = $request->input('address');
			$user->save();
			/*$emailData = Emailtemplates::where('slug','=','login-details-for-delivery-boy-&-picker-|-bringoo')->first();
						$settingsEmail 		= Config::get("Site.email");	
						$full_name = $request->input('first_name');
						if($emailData){ //echo 'asd'; die;
						$messageBody = $emailData->description;
						$subject = $emailData->subject;
						
						if($user->email!='')
						{
							$messageBody = str_replace(array('{USERNAME}','{Email}', '{Password}'), array($user->first_name,$user->email,$password),$messageBody);
							$this->sendMail($user->email,$full_name,$subject,$messageBody,$settingsEmail);
						}
						
					}*/
			echo json_encode(array('class'=>'success','message'=>$first_name.' Added successfully.'));die;
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userId = base64_decode($id);
		$userdata = User::find($userId);
		$countrydata = DB::table('countries')->get();
		$countrycode_box=array(''=>'Select Country Code');
		foreach($countrydata as $key=>$value){
			$countrycode_box[$value->phonecode]=$value->name.'('.$value->phonecode.')';
		} 
		return view('admin.sub-admin.edit',["userdata" => $userdata,"countrycode_box" => $countrycode_box]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Input::replace($this->arrayStripTags(Input::all()));
		$formData	=	Input::all();
			Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$userId = $request->input('user_id');
		$user_id =  base64_decode($userId); 
		$validator = Validator::make($request->all(), [
			'first_name1' => 'required|alpha|max:15|min:2',
			'last_name' => 'required|alpha|max:15|min:2',
			'email' => ['required','max:50','email',new UserRoleAlreadyExist(5, $request->email, NULL, 'email', $id)],
			'username' => 'required|max:50|min:4|unique:users,username,'.$id,
			'mobile' => ['required','regex:/[0-9]{9}/','min:7','max:15', new UserRoleAlreadyExist(5, NULL, $request->mobile, 'mobile', $id)],
			'country_code' => 'required|integer|min:0',
			'password' => 'nullable||min:8|custom_password|max:16',
			'confirm_password' 	=> 'nullable|min:8|same:password',
			'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
			'latitude' => 'required',
			 ],
			[
			"password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters.",
			"first_name1.required" =>'The first name field is required.',
			"first_name1.alpha" =>'The first name may only contain letters.',
			"first_name1.max" =>'The first name may not be greater than 15 characters.'
			]);

			if ($validator->fails()){
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}
			else{
				$user = User::find($user_id);
				$first_name =  $request->input('first_name1');
				$last_name =  $request->input('last_name');
				$email =  $request->input('email');
				$mobile =  $request->input('mobile');
				$password =  $request->input('password');
				$country_code =  $request->input('country_code');
				$image = $request->file('profile_pic');


				if(isset($image))
                {
                    $imageName = time().$image->getClientOriginalName();
                    $imageName =str_replace(" ", "", $imageName);
                    $image->move(public_path().'/media/users', $imageName);
                    $user->profile_pic = $imageName;
                }
					
				$user->role_id = 5;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->country_code = $country_code;
				$user->email = $email;
				$user->username = strtolower($request->username);
				$user->mobile = $mobile;
				$user->address = $request->input('address');
				
				if(!empty($password))
				{
					$user->password =Hash::make($password);
				}
				$user->save();
				echo json_encode(array('class'=>'success','message'=>$request->first_name1.' has been updated successfully.'));die;
			}	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
		$user = User::find($id);
		$user->is_deleted = 1;
		$user->device_token = '';
		$user->device_id = '';
		$user->save();
		return response()->json(['class'=>'success','message'=>'User Delete Successfully.']);
    }

    public function updateStatus(Request $request)
	{
		$id = base64_decode($request->input('id'));
		$userdata = User::find($id);
		
		if($userdata->status=="1")
		{
			$userdata->status = "0";
			$userdata->save();
			echo json_encode(array('class'=>'success','message'=>' User Deactive successfully.'));
            die;
		}else
		{
			$userdata->status = "1";
			$userdata->save();
			//parent::sendMailOnUserStatusUpdate($id, 'Activet');
			echo json_encode(array('class'=>'success','message'=>' User Active successfully.'));
            die;
		}
		
	}
}
