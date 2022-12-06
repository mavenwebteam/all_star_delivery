<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App\User;
use App\Models\Contact;
use App\Models\Stores;
use App\Models\AssignCompany;
use App\Models\Login;
use App\Models\Agent;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use URL;
use Mail;
use Redirect;
class homeController extends Controller {

	
	public function index()
	{
		return view('front.home.index');	
	}
	public function login()
	{
		if (Auth::check())
		{
			return redirect('/myaccount');
		}else{
			return view('front.home.login');
		}	
	}
	
	public function signup()
	{
		if (Auth::check())
		{
			return redirect('/myaccount');
		}else{
			return view('front.home.signup');
		}

	}
	
	
	

	

public function signuppost(Request $request)
{
	$validator = Validator::make($request->all(), [
	'cin' => 'required|unique:users|max:150',
	'company_name' => 'required|max:150',
	'listed' => 'required|max:50',
	'type_of_securities' => 'required|max:150',
	'date_of_incorporation' => 'required|max:150',
	'contact_name' => 'required|max:150',
	'registered_address' => 'required|max:255',
	'email' => 'required|email|unique:users',
	'mobile_number' => 'required|unique:users',
	'password' => 'required|min:6|max:16|confirmed',
	'password_confirmation' => 'required|min:6|max:16',
	]);

	if ($validator->fails()) {
	return redirect('/signup')->withErrors($validator)->withInput();
	}else{
	$user = new User;
	$cin =  $request->input('cin');
	$company_name =  $request->input('company_name');
	$listed =  $request->input('listed');
	$type_of_securities =  $request->input('type_of_securities');
	$date_of_incorporation =  $request->input('date_of_incorporation');
	$registered_address =  $request->input('registered_address');
	$contact_name =  $request->input('contact_name');
	$email =  $request->input('email');
	$mobile_number =  $request->input('mobile_number');
	$password =  $request->input('password');
	
	$user->cin = $cin;
	$user->company_name = $company_name;
	$user->listed = $listed;
	$user->type_of_securities = $type_of_securities;
	$user->date_of_incorporation = $date_of_incorporation;
	$user->registered_address = $registered_address;
	$user->contact_name = $contact_name;
	$user->mobile_number = $mobile_number;
	$user->email = $email;
	$user->password = Hash::make($password);
	$user->save();
	Session::put('msg', '<strong class="alert alert-success">Your account successfully created, We will cantact you soon.</strong>');
	return redirect('/signin');
	}
}

public function companysignup()
	{
		if (Auth::check())
		{
			return redirect('/myaccount');
		}else{
			return view('pages.signup-company');
		}

	}

	public function confirmsignup()
	{
	  if (Session::has('otp'))
		{
			return view('pages.otp-signup');
		}else{
			return redirect('/confirm-signup');
		}

	}
	
	public function confirmsignin()
	{
	  if (Session::has('otp'))
		{
			return view('pages.otp-signin');
		}else{
			return redirect('/signin');
		}

	}
	

public function companysignuppost(Request $request)
{
	$validator = Validator::make($request->all(), [
	'cin' => 'required|unique:company|max:150',
	'company_name' => 'required|max:150',
	'listed' => 'required|max:50',
	'type_of_company' => 'required|max:150',
	'date_of_incorporation' => 'required|max:150',
	'registered_address' => 'required|max:255',
	'email' => 'required|email|unique:user_login',
	'phone_no' => 'required',
	'gst_no' => 'required|unique:company',
	'pan_no' => 'required|unique:company',
	'contact_person_name' => 'required',
	'contact_person_designation' => 'required',
	'contact_person_mobile' => 'required|unique:company',
	'contact_person_email' => 'required',
	'password' => 'required|min:6|max:16|confirmed',
	'password_confirmation' => 'required|min:6|max:16',
	]);

	if ($validator->fails()) {
	return redirect('/company-signup')->withErrors($validator)->withInput();
	}else{
	$cin =  $request->input('cin');
	$company_name =  $request->input('company_name');
	$listed =  $request->input('listed');
	$type_of_company =  $request->input('type_of_company');
	$date_of_incorporation =  $request->input('date_of_incorporation');
	$registered_address =  $request->input('registered_address');
	$email =  $request->input('email');
	$phone_no =  $request->input('phone_no');
	$website =  $request->input('website');	
	$gst_no =  $request->input('gst_no');
	$pan_no =  $request->input('pan_no');
	$contact_person_designation =  $request->input('contact_person_designation');
	$contact_person_mobile =  $request->input('contact_person_mobile');
	$contact_person_email =  $request->input('contact_person_email');
	$contact_person_name =  $request->input('contact_person_name');
	$password =  $request->input('password');
    $company = new Company;
	$company->cin = $cin;
	$company->company_name = $company_name;
	$company->listed = $listed;
	$company->type_of_company = $type_of_company;
	$company->date_of_incorporation = $date_of_incorporation;
	$company->registered_address = $registered_address;
	$company->email = $email;	
	$company->phone_no = $phone_no;
	$company->website = $website;
	$company->gst_no = $gst_no;
	$company->pan_no = $pan_no;
	$company->contact_person_designation = $contact_person_designation;
	$company->contact_person_mobile = $contact_person_mobile;
	$company->contact_person_email = $contact_person_email;
	$company->contact_person_name = $contact_person_name;
	$company->password = Hash::make($password);
	$company->save();
		
	$login_user = new Login;
	$login_user->email = $email;
	$login_user->password = Hash::make($password);
	$login_user->status = "0";
	$login_user->user_type = "company";
	$login_user->user_id = $company->id;	
	$login_user->save();
	$helpers = new Helpers;	
	$otp =  $helpers->genrateotp();
	Session::put('otp',$otp);
	Session::put('com_id',$company->id);		
	$msg = "Hi OTP is ".$otp." to access your SAGRTA Account. For security reason do not share this OTP with anyone - Team SAGRTA";
	$sendsms =  $helpers->sendsms($contact_person_mobile,$msg);
	Session::put('msg', '<strong class="alert alert-success"> OTP sent to your mobile number.</strong>');
	return redirect('/confirm-signup');
	}
}

	public function confirmsignuppost(Request $request)
{
	$validator = Validator::make($request->all(), [
	'otp' => 'required',
	]);

	if ($validator->fails()) {
	return redirect('/confirm-signup')->withErrors($validator)->withInput();
	}else{
	$com_id = Session::get('com_id');		
	$company =  Company::find($com_id);
	$com_id = 	$sesson_otp = Session::get('com_id');
	$otp =  $request->input('otp');
	$sesson_otp = Session::get('otp');	
	if($otp!=$sesson_otp){
	Session::put('msg', '<strong class="alert alert-danger">Invalid OTP.</strong>');
	return redirect('/confirm-signup');	
	}else{
	$company->confirm_mobile = 1;
	$company->save();
	Session::forget('otp');	

	//Mail code start

	$activate_url = \App::make('url')->to("company-account-activate?activate=".base64_encode(1)."&companykey=".base64_encode($company->id));
	$deactivate_url =\App::make('url')->to("company-account-activate?activate=".base64_encode(2)."&companykey=".base64_encode($company->id));

		$to="info@sagrta.com";
		  //$to="yogi.lalit2391@gmail.com";

		$data = array( 'email' => $to ,'from' => 'info@sagrta.com', 'from_name' => 'SAG RTA', 'data' => array("activate_url"=>$activate_url,"deactivate_url"=>$deactivate_url,'cin'=>$company->cin,'company_name'=>$company->company_name,'date_of_incorporation'=>$company->date_of_incorporation,'phone_no'=>$company->phone_no,'email'=>$company->email,'contact_person_mobile'=>$company->contact_person_mobile,'listed'=>$company->listed,'registered_address'=>$company->registered_address,'contact_person_name '=>$company->contact_person_name ));

		Mail::send( 'pages.email.companyconfirmsignup',$data, function( $message ) use ($data)
		{
			$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject('SAG RTA New Signup');

		}); 
   //mail code end

	Session::forget('com_id');
	Session::forget('user');
	Session::put('msg', '<strong class="alert alert-success">Registration credential has been sent to RTA admin for approval kindly contact on 0141-4727374 for login approval.</strong>');
	return redirect('/signin');
		}
	}
}

public function accountactivate($id)
{
 
 
if ($id=="") {
	return redirect('/');
	}else{

$id = base64_decode($id);
$user =  User::find($id);
	if(!empty($user))
				{	
$user->email_verify = 'yes';

$user->save();	
// $signin_url = \App::make('url')->to("signin");
	
//      $to=$userLogin->email;
// 		  //$to="yogi.lalit2391@gmail.com";

// 		$data = array( 'email' => $to ,'from' => 'info@sagrta.com', 'from_name' => 'SAG RTA', 'data' => array("signin_url"=>$signin_url,"status"=>$status));

// 		Mail::send( 'pages.email.activateAccount',$data, function( $message ) use ($data)
// 		{
// 			$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject('SAG RTA Account Confirmation');

// 		}); 	
Session::put('msg', '<strong class="alert alert-success"> Account has been verified successfully .</strong>');
}else{
	Session::put('msg', '<strong class="alert alert-success"> Invalid Url .</strong>');
}
	return redirect('/thanks');	
}
}
public function confirmsigninpost(Request $request)
{
	$validator = Validator::make($request->all(), [
	'otp' => 'required',
	]);

	if ($validator->fails()) {
	return redirect('/confirm-signin')->withErrors($validator)->withInput();
	}else{
	$userdata = Session::get('userdata');
	
	$otp =  $request->input('otp');
	$sesson_otp = Session::get('otp');
	$reurl = Session::get('reurl');
	if($otp!=$sesson_otp){
	Session::put('msg', '<strong class="alert alert-danger">Invalid OTP.</strong>');
	return redirect('/confirm-signin');	
	}else{
		$email = $userdata['email'];
		$password = $userdata['password'];
	if (Auth::attempt(['email' => $email, 'password' => $password]))
	{
	Session::forget('otp');	
	Session::forget('userdata');
	Session::forget('reurl');
	Session::put('login_as', 'self');	
	return redirect("$reurl");	
						   }else{
			Session::put('msg', '<strong class="alert alert-danger">Invalid Email or Password.</strong>');
		return redirect('/signin');				   
						   }
	}
	}
}

public function signin(Request $request)
{
	if (Auth::check())
	{
		return redirect('/myaccount');
	}else{
		if(!Session::has('reurl')){
		Session::put('reurl',"/myaccount");
		}
		return view('pages.signin');
	}
}


public function signinpost(Request $request)
{
	$validator = Validator::make($request->all(), [
	'email' => 'required',
	'password' => 'required',
	]);
	if ($validator->fails()) {
		return redirect('/signin')
		->withErrors($validator)
		->withInput();
	}else{
		$email =  $request->input('email');
		$password =  $request->input('password');
		$login_user = Login::whereEmail($email)->whereStatus("1")->first();
		if (!isset($login_user->id))
		{
		Session::put('msg', '<strong class="alert alert-danger">Invalid Email or Password.</strong>');
		return redirect('/signin');
		exit();	
		}
		
		
		if (Hash::check($password, $login_user->password))
		{  
		if($login_user->login_with_otp=="0"){
	$reurl = Session::get('reurl');
	if (Auth::attempt(['email' => $email, 'password' => $password]))
	{
	Session::forget('otp');	
	Session::forget('userdata');
	Session::forget('reurl');
	Session::put('login_as', 'self');	
	return redirect("$reurl");	
						   }else{
			Session::put('msg', '<strong class="alert alert-danger">Invalid Email or Password.</strong>');
		return redirect('/signin');				   
						   }
		}else{
			if($login_user->user_type=="company"){
			$userlog = new Company;
			$userlogin = $userlog->whereId($login_user->user_id)->first();
			$mobile = 	$userlogin->contact_person_mobile;	
			}else{
			$userlog = new Agent;
			$userlogin = $userlog->whereId($login_user->user_id)->first();
			$mobile = 	$userlogin->mobile;
			}
			
			$helpers = new Helpers;
			$otp =  $helpers->genrateotp();
			Session::put('otp',$otp);
			Session::put('userdata',array("id"=>$userlogin->id,"email"=>$userlogin->email,"password"=>$password));		
			$msg = "Hi OTP is ".$otp." to access your SAGRTA Account. For security reason do not share this OTP with anyone - Team SAGRTA";
			$sendsms =  $helpers->sendsms($mobile,$msg);
			Session::put('msg', '<strong class="alert alert-success"> OTP sent to your XXXXXXXX'.substr($mobile,-2).' mobile number.</strong>');
			return redirect('/confirm-signin');
			}
		}else{
		 Session::put('msg', '<strong class="alert alert-danger">Invalid Email or Password.</strong>');
		 return redirect('/signin');
		}
	}
}

	public function forgot()
{
	if (Auth::check())
	{
		return redirect('/myaccount');
	}else{
		return view('pages.fogot');
	}
}

public function logout()
{
	if (Auth::check())
	{
	 Auth::logout();
	 return redirect('/signin');
	}else {
	 return redirect('/signin');
	}
}


public function forgotpost(Request $request)
{

	$validator = Validator::make($request->all(), [

	'email' => 'required|email',

	]);

	if ($validator->fails()) {

	    return redirect('/forgot-password')->withErrors($validator)->withInput();

	}else{
		$email =  $request->input('email');
		$user = User::where('email',$email)->where('type','!=','3')->first();
       
	if(!isset($user->id)){

		Session::put('msg', '<strong class="alert alert-danger">Please enter your registered email.</strong>');

		return redirect('/forgot-password');

	}else{


		$length = 10;

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$charactersLength = strlen($characters);

		$randomString = '';

		for ($i = 0; $i < $length; $i++) {

			$randomString .= $characters[rand(0, $charactersLength - 1)];

		}

		$url = $randomString;

		$cur_date = date("Y-m-d H:i:s");

		//$user = User::find($emaildata->id);

		$user->forgot_url = $url;

		$user->forgot_time = $cur_date;

		$user->save();

		$create_url = \App::make('url')->to('/create-password')."/".$url;

		$data = array( 'email' => $email, 'name' => $name, 'from' => 'info@sagrta.com', 'from_name' => 'Bringo', 'data' => array("url"=>$create_url,"name"=>$name));

		Mail::send( 'pages.email.forgot',$data, function( $message ) use ($data)
		{
			$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Forgot Password' );

		}); 

		Session::put('msg', '<div class="alert alert-success">Forgot password instruction has been successfully sent.</div>');

		return redirect('/forgot-password');

	}

	}
}



public function createpass($uniqurl)
{	
	if (Auth::check())
	{
		return redirect('/');
	}else {
		$user = User::whereForgot_url($uniqurl)->first();
		if(!isset($user->id)){
			return view('pages.error404',['msg'=>'Invalid url!']);
		}else{
			$time = date('Y-m-d H:i:s');
			$time1 = strtotime($user->forgot_time);
			$time2 = strtotime($time);
			$diff = $time2 - $time1;
			$hour_diff = $diff/3600;
			if($hour_diff > 24)
			{
				return view('pages.error404',['msg'=>'Your Session has been expired!']);
			}else{
				return view('pages.createpassword',['uniqurl'=>$uniqurl]);
			}
		}
	}
} 

public function createpasspost(Request $request)
{
	if (Auth::check())
	{
		return redirect('/myaccount');
	}else {
	$validator = Validator::make($request->all(), [
	'uniqurl' => 'required',
	'password' => 'required|min:6|max:16|confirmed',
	'password_confirmation' => 'required|min:6|max:16',
	]);
	if ($validator->fails()) {
	return redirect()->back()->withErrors($validator)->withInput();
	}else{ 
		 $password =  $request->input('password');
		 $uniqurl =  $request->input('uniqurl');
		$uniqurldata = User::whereForgot_url($uniqurl)->first();
		
		if(!isset($uniqurldata->id)){
			return view('pages.error404',['msg'=>'Invalid url!']);
		}else{
			
			$uniqurldata->forgot_url = "";
			$uniqurldata->password = Hash::make($password);
			$uniqurldata->save();
			Session::put('msg', '<div class="alert alert-success">password changed successfully. You can login with new password.</div>');
			return redirect('/signin');
		
		}
	}
	}
} 		

public function myaccount()
{
$user = Auth::user();
if($user->user_type=="agent"){
$userdetail = Agent::find($user->user_id);	
return view('pages.myaccount-agent',['userdata'=>$userdetail]);	
}else{
$userdetail = Company::find($user->user_id);		
return view('pages.myaccount-company',['userdata'=>$userdetail]);	
}	

}
			
public function editprofile()
{
$user = Auth::user();
	if($user->user_type=="company"){
	$userdata = Company::find($user->user_id);	
	return view('pages.profileEditCompany',['user'=>$userdata]);	
	}else{
	$userdata = Agent::find($user->user_id);	
	return view('pages.profileEditAgent',['user'=>$userdata]);	
	}

}

public function editprofilepost(Request $request)
{
$user= Auth::user();
$validator = Validator::make($request->all(), [
	'cin' => 'required|max:150|unique:company,cin,'.$user->user_id,
	'company_name' => 'required|max:150',
	'listed' => 'required|max:50',
	'type_of_company' => 'required|max:150',
	'date_of_incorporation' => 'required|max:150',
	'registered_address' => 'required|max:255',
	'email' => 'required|email|unique:user_login,email,'.$user->id,
	'phone_no' => 'required',
	'gst_no' => 'required|unique:company,gst_no,'.$user->user_id,
	'pan_no' => 'required|unique:company,pan_no,'.$user->user_id,
	'contact_person_name' => 'required',
	'contact_person_designation' => 'required',
	'contact_person_mobile' => 'required|unique:company,contact_person_mobile,'.$user->user_id,
	'contact_person_email' => 'required',
	]);	
	
if ($validator->fails()) {
return redirect('/edit-profile')->withErrors($validator)->withInput();
}else{
	$cin =  $request->input('cin');
	$company_name =  $request->input('company_name');
	$listed =  $request->input('listed');
	$type_of_company =  $request->input('type_of_company');
	$date_of_incorporation =  $request->input('date_of_incorporation');
	$registered_address =  $request->input('registered_address');
	$email =  $request->input('email');
	$phone_no =  $request->input('phone_no');
	$website =  $request->input('website');	
	$gst_no =  $request->input('gst_no');
	$pan_no =  $request->input('pan_no');
	$contact_person_designation =  $request->input('contact_person_designation');
	$contact_person_mobile =  $request->input('contact_person_mobile');
	$contact_person_email =  $request->input('contact_person_email');
	$contact_person_name =  $request->input('contact_person_name');
	
	$company =  Company::find($user->user_id);
	$company->cin = $cin;
	$company->company_name = $company_name;
	$company->listed = $listed;
	$company->type_of_company = $type_of_company;
	$company->date_of_incorporation = $date_of_incorporation;
	$company->registered_address = $registered_address;
	$company->email = $email;	
	$company->phone_no = $phone_no;
	$company->website = $website;
	$company->gst_no = $gst_no;
	$company->pan_no = $pan_no;
	$company->contact_person_designation = $contact_person_designation;
	$company->contact_person_mobile = $contact_person_mobile;
	$company->contact_person_email = $contact_person_email;
	$company->contact_person_name = $contact_person_name;
	$company->save();
	
	$login_user = Login::whereUser_id($user->user_id)->first();
	$login_user->email = $email;
	$login_user->save();
		Session::put('msg', '<strong class="alert alert-success">Your Profile successfully updated.</strong>');
		return redirect('/myaccount');
}
}

	public function editagentprofilepost(Request $request)
{
$user= Auth::user();
$validator = Validator::make($request->all(), [
	'company_name' => 'required|max:150',
	'email' => 'required|email|unique:user_login,email,'.$user->id,
	'gst_no' => 'required|unique:agent,gst_no,'.$user->user_id,
	'pan_no' => 'required|unique:agent,pan_no,'.$user->user_id,
	'name' => 'required',
	'mobile' => 'required|unique:agent,mobile,'.$user->user_id,
	]);	
	
if ($validator->fails()) {
return redirect('/edit-profile')->withErrors($validator)->withInput();
}else{
	$company_name =  $request->input('company_name');
	$email =  $request->input('email');
	$gst_no =  $request->input('gst_no');
	$pan_no =  $request->input('pan_no');
	$mobile =  $request->input('mobile');
	$name =  $request->input('name');
	
	$agent =  Agent::find($user->user_id);
	$agent->company_name = $company_name;
	$agent->email = $email;	
	$agent->gst_no = $gst_no;
	$agent->pan_no = $pan_no;
	$agent->mobile = $mobile;
	$agent->name = $name;
	$agent->save();
	
	$login_user = Login::whereUser_id($user->user_id)->first();
	$login_user->email = $email;
	$login_user->save();
		Session::put('msg', '<strong class="alert alert-success">Your Profile successfully updated.</strong>');
		return redirect('/myaccount');
}
}
	
public function changepassword()
{					
return view('pages.changePassword');
}				

public function changepasswordpost(Request $request)
{
$user = Auth::user();
$validator = Validator::make($request->all(), [
'password' => 'required|min:6|max:16|confirmed',
'password_confirmation' => 'required|min:6|max:16',
]);
if ($validator->fails()) {
return redirect('/myaccount/change-password')->withErrors($validator)->withInput();
}else{
$password =  $request->input('password');
$user->password = Hash::make($password);
$user->save();
Session::put('msg', '<strong class="alert alert-success">Your password successfully changed.</strong>');
return redirect('/myaccount');
}
}

						
public function contactpost(Request $request)
{
	$validator = Validator::make($request->all(), [
		'name' => 'required|max:150',
		'company' => 'max:150',
		'email' => 'required|email',
		'mobile_number' => 'required',
		'message' => 'required|max:255',
	]);
	if ($validator->fails()) {
	    return redirect('/rta-contact')->withErrors($validator)->withInput();
	}else{
		$name =  $request->input('name');
		$company =  $request->input('company');
		$email =  $request->input('email');
		$mobile_number =  $request->input('mobile_number');
		$message =  $request->input('message');
		$type =  "contact";
		$contact = new Contact;

		
		$contact->name = $name;
		$contact->company = $company;
		$contact->email = $email;
		$contact->mobile_number = $mobile_number;
		$contact->message = $message;
		$contact->type = $type;
		$contact->save();
		$data = array( 'email' => "info@sagrta.com", 'from' => 'info@sagrta.com', 'from_name' => 'SAG RTA',"data"=>array('name' => $name, 'company' => $company,'email' => $email, 'mobile_number' => $mobile_number, 'message' => $message));

		Mail::send( 'pages.email.contact',$data, function( $message ) use ($data)
		{
			$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Contact Detail' );

		});

		Session::put('msg', '<div class="alert alert-success">Thanks for contact us we will contact you soon.</div>');
		return redirect('/thanks');
	
	}
}

	
	public function enquirepost(Request $request)
{
	$validator = Validator::make($request->all(), [
		'name' => 'required|max:150',
		'email' => 'required|email',
		'mobile_number' => 'required',
		'message' => 'required|max:255',
	]);
	if ($validator->fails()) {
	    return redirect('/')->withErrors($validator)->withInput();
	}else{
		$name =  $request->input('name');
		$formtype =  $request->input('formtype');
		$email =  $request->input('email');
		$mobile_number =  $request->input('mobile_number');
		$message =  $request->input('message');
		if($formtype=="rightForm_enquire"){
        $type =  "rightForm_enquire";
		}else{
		$type =  "enquire";	
		}
		
		$contact = new Contact;

		
		$contact->name = $name;
		$contact->email = $email;
		$contact->company = "";
		$contact->mobile_number = $mobile_number;
		$contact->message = $message;
		$contact->type = $type;
		$contact->save();
		$data = array( 'email' => "info@sagrta.com", 'from' => 'info@sagrta.com', 'from_name' => 'SAG RTA',"data"=>array('name' => $name,'email' => $email, 'mobile_number' => $mobile_number, 'message' => $message));

		Mail::send( 'pages.email.enquiry',$data, function( $message ) use ($data)
		{
			$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Contact Detail' );

		});

		Session::put('msg', '<div class="alert alert-success">Thanks for contact us we will contact you soon.</div>');
		return redirect('/thanks');
	
	}
}

	
public function security()
{
    $user = Auth::user();
	if($user->user_type=="company"){
			$userlog = new Company;
			$userdata = $userlog->whereId($user->user_id)->first();
			}else{
			$userdata = $user;
			}
	return view('pages.myaccount-security',['userdata'=>$userdata]);	

}

	

}

?>