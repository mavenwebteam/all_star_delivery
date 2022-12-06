<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\User;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use Excel;
use App\Models\Emailtemplates;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail,Input;
use Illuminate\Support\Str;
use App\Rules\UserRoleAlreadyExist;
use App\Constants\Constant;

class VendorController extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('admin')['id'])
		{
			$admindata = Admin::find(session('admin')['id']);
			$this->user = $admindata;
		}
    }

	public function index(Request $request)
	{	
		$userdata = User::where('is_admin','0')->where('role_id','3')->orderBy("created_at","DESC");
		$type=$request->input('type');
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');
		$first_name=$request->input('first_name');
		$last_name=$request->input('last_name');
		$email=$request->input('email');
		$mobile=$request->input('mobile');
		$uu_id=$request->input('uu_id');
		$status=$request->input('status');
		$perpage=$request->input('perpage');
		$rating=$request->input('rating');

		if($uu_id!="")
		{ 
			$userdata=$userdata->where('uu_id',$uu_id);
			
		}
		if($rating!="")
		{ 
			$userdata=$userdata->where('rating',$rating);
			
		}
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
			$userdata=$userdata->where('first_name','like',"%$first_name%");
			
		}
		if($last_name!="")
		{ 
			$userdata=$userdata->where('last_name','like',"%$last_name%");
			
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
			 $userdata = $userdata->paginate(Constant::VENDOR_RECORD_PER_PAGE);
		   }
		if ($request->ajax()) 
		{
			return view('admin.vendor.search', compact('userdata'));  
        }
		$admindata = Admin::find(session('admin')['id']);
        //echo '<pre>'; print_r($userdata); die;
		// dd($userdata);
		
        return view('admin.vendor.show', compact('userdata','admindata'));
	}
	
	public function adduser(Request $request)
	{   
	
		$countrydata = DB::table('countries')->get();
		$countrycode_box=array(''=>'Select Country Code');
		foreach($countrydata as $key=>$value){
			$countrycode_box[$value->phonecode]= $value->name.'('.$value->phonecode.')';
		} 
		return view('admin.vendor.add',compact('countrycode_box'));
	} 
	public function adduserpost(Request $request)
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
			
			'first_name1' => 'required|max:50',
			'last_name1' => 'required|max:50',
			'address' => 'required',
			'email' => ["required", "max:50", "email", new UserRoleAlreadyExist(3, $request->email, NULL, 'email')],
			'mobile' => ['required','regex:/[0-9]{9}/', new UserRoleAlreadyExist(3, NULL, $request->mobile, 'mobile')],
			'password' => 'required|min:8|custom_password',
			'confirm_password' 	=> 'required|min:8|same:password',
			'country_code' => 'required',
			'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
			],
			[
			"first_name1.required" =>'The first name field is required.',
			"first_name1.max" =>'The first name may not be greater than 50 characters.',
			"last_name1.required" =>'The last name field is required.',
			"last_name1.max" =>'The last name may not be greater than 50 characters.',
			"password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters."
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$user = new User();
				
				$first_name =  $request->input('first_name1');
				$last_name =  $request->input('last_name1');
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

				$user->role_id = 3;
				$user->email_verify = 'yes';
				$user->is_mobile_verify = 1;
				$user->uu_id = (string) Str::uuid();
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->email = $email;
				
				$user->country_code = $country_code;
				$user->mobile = $mobile;
				$user->password = Hash::make($password);
				$user->address = $request->input('address');
				$user->latitude = $request->input('latitude');
				$user->longitude = $request->input('longitude');
				$user->save();
				
				// --------send a mail with credential------------
				$emailData = Emailtemplates::where('slug', '=', 'login-details-driver')->first();
				if ($emailData) {
					$textMessage = strip_tags($emailData->description);
					$login_url = \App::make('url')->to('/vendor/login');

					$textMessage = str_replace(array('{USERNAME}','{Email}', '{Password}','{{LOGIN_URL}}'), array($user->first_name,$user->email,$password,$login_url),$textMessage);
					
					$user->subject = $emailData->subject;
					if ($user->email != '') {
						Mail::raw($textMessage, function ($messages) use ($user) {
							$to = $user->email;
							$messages->to($to)->subject($user->subject);
						});
					}
				}
				// --------send a mail with credential end------------
		
				echo json_encode(array('class'=>'success','message'=>'Vendor Added successfully.'));die;

			}
		
	} 
	
	public function edituser($id)

	{
		$userId = base64_decode($id);
		$userdata = User::find($userId);
		$countrydata = DB::table('countries')->get();
		$countrycode_box=array(''=>'Select Country Code');
		foreach($countrydata as $key=>$value){
			$countrycode_box[$value->phonecode]= $value->name.'('.$value->phonecode.')';
		} 
		return view('admin.vendor.edit',["userdata" => $userdata,'countryCode'=> $countrydata]);

	}
	public function viewuser($id)
	{
		$userId = base64_decode($id);
		$userdata = User::find($userId);
		return view('admin.vendor.view',["userdata" => $userdata]);

	}
	

	public function edituserpost(Request $request)
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
			
			'first_name1' => 'required|max:50',
			'last_name1' => 'required|max:50',
			'email' => ['required','max:50','email', new UserRoleAlreadyExist(3, $request->email, NULL, 'email', $user_id)],
			'mobile' => ['required','regex:/[0-9]{9}/', new UserRoleAlreadyExist(3, NULL, $request->mobile, 'mobile', $user_id)],
			'country_code' => 'required|integer|min:0',
			'password' => 'nullable||min:8|custom_password',
			'confirm_password' 	=> 'nullable|min:8|same:password',
			'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
			// 'profile_img' => 'max:2048|mimes:jpg,jpeg,gif,png',
				'image' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
			 ],
			[
			"first_name1.required" =>'The first name field is required.',
			"first_name1.max" =>'The first name may not be greater than 50 characters.',
			"last_name1.required" =>'The last name field is required.',
			"last_name1.max" =>'The last name may not be greater than 50 characters.',
			"password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters."
			]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$user = User::find($user_id);
				
				$first_name =  $request->input('first_name1');
				$last_name =  $request->input('last_name1');
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

				
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->country_code = $country_code;
				$user->email = $email;
				$user->mobile = $mobile;
				
				if(!empty($password))
				{
					$user->password =Hash::make($password);
				}
				
				$user->save();
				// if(!empty($password))
				// {
				// 	$emailData = Emailtemplates::where('slug','=','login-details-|-bringoo')->first();
				// 			$settingsEmail 		= Config::get("Site.email");	
				// 			$full_name = $request->input('first_name');
				// 		  if($emailData){ //echo 'asd'; die;
				// 			$messageBody = strip_tags($emailData->description);
				// 			$subject = $emailData->subject;
				// 			//$user->subject = $emailData->subject;
				// 			//$user->from = 'dinesh.singh@octalinfosolution.com';
				// 			//$activate_url =\App::make('url')->to("user-account-activate/".$user->validate_string);
				// 			$login_url = \App::make('url')->to('/outlate/login');

				// 			if($user->email!='')
				// 			{
				// 				$messageBody = str_replace(array('{USERNAME}','{Email}', '{Password}','{{LOGIN_URL}}'), array($user->first_name,$user->email,$password,$login_url),$messageBody);
				// 				//$this->sendMail($user->email,$full_name,$subject,$messageBody,$settingsEmail);
				// 			}
							
				// 		}
				// }	
				echo json_encode(array('class'=>'success','message'=>'Vendor Edit successfully.'));die;

			}	

	}



	public function userstatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$userdata = User::find($id);
		
		if($userdata->status=="1")
		{
			$userdata->status = "0";
			$userdata->save();
			echo json_encode(array('class'=>'success','message'=>' User Deactive successfully.'));die;

			

		}else
		{
			$userdata->status = "1";
			$userdata->save();
			echo json_encode(array('class'=>'success','message'=>' User Active successfully.'));die;

		
			  
		}
		
	}

		

		public function usersdelete($id)

	   {

		$id = base64_decode($id);

		$user = User::find($id);

		$user->delete();

		 Session::put('msg', '<strong class="alert alert-success"> User successfully deleted.</strong>');

		 return redirect('/admin/user-management/users');	

	   }
	
}

?>