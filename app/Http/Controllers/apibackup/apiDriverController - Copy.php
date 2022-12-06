<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Response;
use Hash;
use DB;
use Validator;
use File;
use App\Helpers;
use App\Models\Category;
use App\Models\Homeslider;
use App\Models\Product;
use App\Models\Stores;
use App\Models\Favorites;
use App\Models\Userlocations;
use App\Models\Emailtemplates;
use App\Models\Cartitem;
use App\Models\Productimages;
use App\Models\Orders;
use App\Models\Points;
use App\Models\Orderitems;
use App\Models\Issue;
use App\Models\Rating;
use App\Models\Deliveryprice;
use App\Models\Storeoffers;
use App\Models\CouponCode;
use App\Models\Notification;
use App\User;
use Config;
use Session;
use URL;
use Mail;
use Auth;
use Carbon\Carbon;
use App\Models\Productinventory;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Input;
class apiDriverController  extends Controller
{
	public function signup() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	
			if($decoded) {
				if(!empty($decoded['type']) && !empty($decoded['country_code']) && !empty($decoded['mobile_number']) && !empty($decoded['email'])  && !empty($decoded['device_id']) && !empty($decoded['device_type']) && !empty($decoded['password'])) {
					//$decoded['password'] = bcrypt($decoded['password']);
					$decoded['mobile_number'] = str_replace("-","",$decoded['mobile_number']);
					$decoded['mobile_number'] = str_replace(" ","",$decoded['mobile_number']);
					$userArray = User::where('mobile','=',$decoded['mobile_number'])->where('country_code','=',$decoded['country_code'])->first();
					$emailArray = User::where('email','LIKE',$decoded['email'])->first();
				
					
					$r = 0;
					if($userArray){
						$r = 1;
					}
					if($emailArray){
						$r = 2;
					}
					if($decoded['invite_code']!="")
					{
						$invite_codeuser= User::where('uniq_id',$decoded['invite_code'])->first();
						if(empty($invite_codeuser)){
							$r = 3;
						}
						
					}
					if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
						$decoded['latitude'] = $decoded['latitude'];
					}else{
						$decoded['latitude'] = NULL;
					}
					if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
						$decoded['longitude'] = $decoded['longitude'];
					}else{
						$decoded['longitude'] = NULL;
					}
					if($r == 0){
						$user = new User();
						$otp = $this->random_digits(6);

						$user->uniq_id = uniqid();
						$user->type =$decoded['type'];
						$user->invite_code =$decoded['invite_code'] ? $decoded['invite_code'] : '';
						$user->otp =$otp;
						$user->otp_verify ='no';
						$user->email =  $decoded['email'];
						$user->country_code =$decoded['country_code'];
						$user->mobile = $decoded['mobile_number'];
						$user->latitude = $decoded['latitude'] ? $decoded['latitude'] : '';
						$user->longitude = $decoded['longitude'] ? $decoded['longitude'] : '';
						$user->device_id = $decoded['device_id'];
						$user->device_type = $decoded['device_type'];
						$user->password =Hash::make($decoded['password']);
						$user->is_notification ='1';
						$user->status ='1';
						$user->save();
						$data['user_id'] = (string)$user->id;
						
						$data['email'] = $user->email;
						$data['country_code'] = $user->country_code;
						$data['mobile_number'] = $user->mobile;
						$data['otp'] = $user->otp;
						$data['latitude'] = $user->latitude;
						$data['longitude'] = $user->longitude;
						$data['is_notification'] = $user->is_notification;
						$data['address'] = '';
						$data['img'] = '';
						// if($userArray->profile_image &&  file_exists(public_path('/storage/profile_images/').$userArray->profile_image ))
						// {
						// 	$data['img'] = url('/').'/public/storage/profile_images/'.$userArray->profile_image;
						// }
						  $emailData = Emailtemplates::where('slug','=','user-registration')->first();
						  if($emailData){
							$textMessage = strip_tags($emailData->description);
							$user->subject = $emailData->subject;
							$activate_url =\App::make('url')->to("account-activate/".base64_encode($user->id));

							if($user->email!='')
							{
								$textMessage = str_replace(array('{USERNAME}','{URL}'), array($user->first_name,$activate_url),$textMessage);
								
								Mail::raw($textMessage, function ($messages) use ($user) {
									$to = $user->email;
									$messages->to($to)->subject($user->subject);
								});
							}
						}
						$data1 = $data;
						$status = 1;
						$message = "Registration Success. Please Verify your mobile and email.";
					}else if($r == 1){
						$message = "Mobile number already exists.";
					
					}else if($r == 3){
					$message = "Invalid Invite code.";
					}else{

						$message = "Email already exists.";
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}

public function login() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
		
			if($decoded) 
			{
				$userArray="";
				if(!empty($decoded['type']) && !empty($decoded['password']) && !empty($decoded['email'])  &&  !empty($decoded['device_id']) && !empty($decoded['device_type'])) 
				{
			
					
						if($decoded['type']=="email")
						{
								if(Auth::attempt(['email' => $decoded['email'], 'password' => $decoded['password']])){ 
									$userArray = Auth::user(); 
								} 
				 
					    
						}else{
							if(Auth::attempt(['country_code' => $decoded['country_code'],'mobile' => $decoded['email'], 'password' => $decoded['password']])){ 
								$userArray = Auth::user(); 
							} 
						}
						
					
					if($userArray){
						if ($userArray->id!="")
						{
							if ($userArray->type == '2' || $userArray->type == '3')
							{			
							if($userArray->status == '1'){
								
								
							
							if($userArray->otp_verify == 'no' ){
								$message = "Please Verify Your Mobile.";
								$otp = $this->random_digits(6);
								$userArray->otp=$otp;
								$userArray->save();
								$data['id'] = $userArray->id;
								$data['otp'] = $userArray->otp;
								
							}
							else
							{
								$message = "You have logged in successfully.";
							}
							
								$userArray->device_id = $decoded['device_id'];
								$userArray->device_type = $decoded['device_type'];
								if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
									$userArray->latitude = $decoded['latitude'];
								}
								if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
									$userArray->longitude = $decoded['longitude'];
								}

								$userArray->auth_token = base64_encode(openssl_random_pseudo_bytes(30));
								
								$userArray->save();
								$notification = DB::table('notification')->where('user_id',$userArray->id)->count();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name;
								$data['last_name'] = $userArray->last_name;
								$data['email'] = $userArray->email;
								
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								$data['notification_count'] = !empty($notification) ? $notification : 0;
								$data['is_online'] = $userArray->is_online;
								$data['type'] = $userArray->type;
								$data['vehicle_type'] = !empty($userArray->vehicle_type) ? $userArray->vehicle_type : '';
								$data['auth_token'] = $userArray->auth_token;
								$mobile_verify="";
								if($userArray->otp_verify=='yes')
								{
									$mobile_verify='1';
								}else{
									$mobile_verify='0';
								}
								$data['is_mobile_verify'] = $mobile_verify;
								
								
								$data['address'] = $userArray->address ? $userArray->address : '';
								if($userArray->profile_pic &&  file_exists(public_path('/media/users/').$userArray->profile_pic ))
									{
										$data['image_url'] = url('/').'/media/users/'.$userArray->profile_pic;
									}else{
										$data['image_url'] =  url('/').'/media/no-image.png';
									}
							
								$data1 = $data;
								$status = 1;
							}else{
								
								$message = "Your account has been deactivated.";
							}
							}else{
							   $message = "You are Invalid User. Please try again.";
							}	
								
						}else{
							$message = "Invalid login credentials. Please try again.";
						}
					}else{
						$message = "Invalid login credentials. Please try again.";
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}
public function verify_otp() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	
			if($decoded) {
				if(!empty($decoded['user_id']) && !empty($decoded['otp'])) {
					
					$userArray = User::where('id','=',$decoded['user_id'])->first();
					$r = 0;
					if($userArray){
						if($userArray->status == '0'){
							$is_deactivate = '1';
							$message = "Your account has been deactivated.";
							$r = 1;
						}
					}
					if($r == 0){
						$user_id = $decoded['user_id'];
						$check_otp = User::where('id','=',$user_id)->where('otp','=',$decoded['otp'])->first();
						if($check_otp)
						{   
							$check_otp->otp="";
							$check_otp->otp_verify="yes";
							$check_otp->save();
							if($userArray){
								$status = 1;
								
								
								if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
									$userArray->latitude = $decoded['latitude'];
								}
								if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
									$userArray->longitude = $decoded['longitude'];
								}
								if(isset($decoded['device_id']) && $decoded['device_id'] != ''){
									$userArray->device_id = $decoded['device_id'];
								}
								if(isset($decoded['device_type']) && $decoded['device_type'] != ''){
									$userArray->device_type = $decoded['device_type'];
								}
								$userArray->save();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name ? $userArray->first_name : '';
								$data['last_name'] = $userArray->last_name ? $userArray->last_name : '';
								$data['email'] = $userArray->email;
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								// $data['img'] = '';
								// if($userArray->profile_image &&  file_exists(public_path('/storage/profile_images/').$userArray->profile_image ))
								// {
								// 	$data['img'] = url('/').'/public/storage/profile_images/'.$userArray->profile_image;
								// }
								$data['address'] = $userArray->address ? $userArray->address : '';
								$data1 = $data;
							}else{
								$status = 1;
								$data['user_id'] = '';
								$data['role_id'] = '';
								$data['first_name'] = '';
								$data['last_name'] = '';
								$data['email'] = '';
								$data['country_code'] = '';
								$data['mobile_number'] = '';
								$data['remember_token'] = '';
								$data['latitude'] = '';
								$data['longitude'] = '';
								$data['img'] = '';
								$data['driving_license'] = '';
								$data['id_proof'] = '';
								$data['comments'] = '';
								$data['address'] = '';
								$data['assigned_service'] = '';
								$data['assigned_location'] = '';
								$data1 = $data;
							}
							$message = "OTP has been verified successfully.Your account has been created successfully.";
						}else{
							$message = "Invalid OTP";
						}
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}
	function forgotpassword(Request $request)

	{
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
		$data1 = array();
		$is_deactivate = '0';
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

			if($decoded) 
			{
				if(!empty($decoded['email'])) {
					
					$userArray = User::where('email','=',$decoded['email'])->first();
					if($userArray)
					{
						$status = 1;
						$length = 10;
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$charactersLength = strlen($characters);
						$randomString = '';

						for ($i = 0; $i < $length; $i++) 
						{$randomString .= $characters[rand(0, $charactersLength - 1)];}
						$url = $randomString;
						$cur_date = date("Y-m-d H:i:s");
						$userArray->forgot_url = $url;
						$userArray->forgot_time = $cur_date;
						$userArray->save();
						$create_url = \App::make('url')->to('/create-password')."/".$url;

						$emailData = Emailtemplates::where('slug','=','user-forgot-password')->first();
						if($emailData){
						  $textMessage = strip_tags($emailData->description);
						  $userArray->subject = $emailData->subject;
						  if($userArray->email!='')
						  {
							  $textMessage = str_replace(array('{NAME}','{URL}'), array($userArray->first_name,$create_url),$textMessage);
							  
							  Mail::raw($textMessage, function ($messages) use ($userArray) {
								  $to = $userArray->email;
								  $messages->to($to)->subject($userArray->subject);
							  });
						  }
					  }
						
						
						$message = "Forgot password instruction has been successfully sent on your email.";
								
					}else {
					$message = 'Please enter register email id.';
				    }		
						
					
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
			$response_data = array('status'=>$status,'message'=>$message);
			echo json_encode(array('response' => $response_data));
			die;
	}
	public function resend_otp() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	$data1 = (object) array();
		$is_deactivate = '0';
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['country_code']) && !empty($decoded['mobile_number'])) {
						$decoded['mobile_number'] = str_replace("-","",$decoded['mobile_number']);
						$decoded['mobile_number'] = str_replace(" ","",$decoded['mobile_number']);
						$userArray = User::where('mobile','=',$decoded['mobile_number'])->where('country_code','=',$decoded['country_code'])->first();
						//pr($userArray);die;
						$r = 0;
						if($userArray){
							if($userArray->status == '0'){
								$is_deactivate = '1';
								$message = "Your account has been deactivated.";
								$r = 1;
							}
						}else{
							$message = "Please enter your registered mobile.";
							$r = 2;
						}
						if($r == 0){
							$code_number = $decoded['country_code'].$decoded['mobile_number'];
							$otp = $this->random_digits(6);
							// $is_send = $this->send_sms($otp,$code_number);
							// if($is_send){
								$status = 1;
								$message = "OTP has been sent to your mobile number.";
								$userArray->otp=$otp;
								$userArray->save();
								$data['otp'] = $otp;
								$data['country_code'] = $decoded['country_code'];
								$data['mobile_number'] = $decoded['mobile_number'];
								$data1=$data;
							// }else{
							// 	$message = 'Opps! Something went wrong. Please try again.';
							// }
						}
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
		        }else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
		echo json_encode(array('response' => $response_data));
		die;
	}


	public function notificationStatus(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    $auth_token = $request->header('auth'); 
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		if(!empty($auth_token)){
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{   
						 $users = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
					   if(!empty($users)){
						$userdata = User::find($decoded['user_id']);
				
						if($decoded['is_notification']=="1")
							{
								$userdata->is_notification = "1";
								$userdata->save();
								$status = 1;
								$message = "Notification Active successfully.";
					
								
					
							}else
							{
								$userdata->is_notification = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Deactive successfully.";
					
								
								
							}
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }	
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}	
				}else {
					$message = 'Auth token missing.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
		
	
	public function onlineStatus(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    $auth_token = $request->header('auth'); 
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		if(!empty($auth_token)){
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{   
				       $users = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
					   if(!empty($users)){
						   
						$userdata = User::find($decoded['user_id']);
				
						if($decoded['is_online']=="1")
							{
								$userdata->is_online = "1";
								$userdata->save();
								$status = 1;
								$message = "Online status  Active successfully.";
					
								
					
							}else
							{
								$userdata->is_online = "0";
								$userdata->save();
								$status = 1;
								$message = "Online status Deactive successfully.";
					
								
								
							}
					   }else{
							$status = -1;
							$message = "Session expired.";
					   }	
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
				}else {
					$message = 'Auth token missing.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function selectVehicle(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
		
		$auth_token = $request->header('auth'); 
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		if(!empty($auth_token)){
		    if($decoded && !empty($auth_token)){
					if(!empty($decoded['user_id']) && !empty($decoded['vehical_id'])) 
					{  
				       $users = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
					 
					   if(!empty($users)){
						   
						$userdata = User::find($decoded['user_id']);
				
						
								$userdata->vehicle_type = $decoded['vehical_id'];
								$userdata->save();
								$status = 1;
								$message = "Vehical update successfully.";
					
								
					
					   }else{
							$status = -1;
							$message = "Session expired.";
					   }	
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}	
				}else {
					$message = 'Auth token missing.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function EditProfile(Request $request) 
	{
		 $user_id =  $request->input('user_id');
		 $auth_token = $request->header('auth'); 
		$data1 = array();	
		$first_name =  $request->input('first_name');
		$last_name =  $request->input('last_name');
		$vehicle_type =  $request->input('vehicle_type');
		$image = $request->file('image');
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			 ]);
		if ($validator->fails()) 
		{$response_data = array('status'=>'0','message'=>'All fields are required!');
		}else
		{
			if($auth_token){
			 $users = DB::table('users')->where('auth_token',$auth_token)->where('id',$user_id)->first();
			 if(!empty($users)){
			$userArray =  User::where('id',$user_id)->first();
			if(empty($userArray))
			{
				$response_data = array('status'=>'0','message'=>'Invalid User!');
			}else
			{
					$userArray->first_name =$first_name ? $first_name : 'gdfg';
					$userArray->last_name =$last_name ? $last_name : 'dgdfg';
					if(isset($image))
					{
						$imageName = time().$image->getClientOriginalName();
						$image->move(public_path().'/media/users', $imageName);
						$userArray->profile_pic = $imageName;
					}
					if(!empty($vehicle_type))
					{
						$userArray->vehicle_type =$vehicle_type;
					}
					$userArray->save();
					$notification = DB::table('notification')->where('user_id',$userArray->id)->count();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name;
								$data['last_name'] = $userArray->last_name;
								$data['email'] = $userArray->email;
								
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								$data['is_online'] = $userArray->is_online;
								$data['type'] = $userArray->type;
								$data['auth_token'] = $userArray->auth_token;
								$data['notification_count'] = !empty($notification) ? $notification : 0;
								$data['vehicle_type'] = !empty($userArray->vehicle_type) ? $userArray->vehicle_type : '';
								$mobile_verify="";
								if($userArray->otp_verify=='yes')
								{
									$mobile_verify='1';
								}else{
									$mobile_verify='0';
								}
								$data['is_mobile_verify'] = $mobile_verify;
								
								
								$data['address'] = $userArray->address ? $userArray->address : '';
								if($userArray->profile_pic &&  file_exists(public_path('/media/users/').$userArray->profile_pic ))
									{
										$data['image_url'] = url('/').'/media/users/'.$userArray->profile_pic;
									}else{
										$data['image_url'] =url('/').'/media/no-image.png';;
									}
							
					
					$status = 1;				
					$data1 = $data;
					$message = "Edit Profile Sucessfully.";
					

				}
			 }else{
							$status = -1;
							$message = "Session expired.";
					}
					
			}else{
				$message = "Auth token missing.";
			}		
				$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		}
		echo json_encode(array('response' => $response_data),JSON_UNESCAPED_SLASHES);
		die;
	}
	public function vehicalList(){
			
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		
				$delivery_data = Deliveryprice::where('status',1)
							->get();
				$delivery_count_data = Deliveryprice::where('status',1)
							->count();			
				
				if($delivery_data->count()>0)
				{
					//$home_data['vehical_count']=$delivery_count_data;
					foreach($delivery_data as $data)
					{
						$home_product_data[] =  array(
												'id' =>$data->id,
												'type' => !empty($data->type) ? $data->type : '',
												'slug' => !empty($data->slug) ? $data->slug : '',
												'maximum_weight'=>!empty($data->maximum_weight) ? $data->maximum_weight : 0,
												'default_charge_for_max_distance'=> !empty($data->default_charge_for_max_distance) ? $data->default_charge_for_max_distance : '',
												'max_dis_for_default_charge'=> !empty($data->max_dis_for_default_charge) ? $data->max_dis_for_default_charge :0,
												'charge_after_default_dis'=> !empty($data->charge_after_default_dis) ? $data->charge_after_default_dis : '',
											
												);
						$home_data=$home_product_data;
					}	
						$status = '1';
						$message='Vehical listed below.';	
				}else{
					$home_data['vehical'] = [];
					$message = 'No Vehical data found.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	    echo json_encode(array('response' => $response_data));
	   die();
	}
	public function userProfile(Request $request){ 
	$id = Input::get('user_id'); 
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	$auth_token = $request->header('auth');  ;
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	//$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	if($auth_token)
	{
  					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$id)->first();
					if($userArray){
								$notification = DB::table('notification')->where('user_id',$id)->count();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name;
								$data['last_name'] = $userArray->last_name;
								$data['email'] = $userArray->email;

								
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								$data['is_online'] = $userArray->is_online;
								$data['type'] = $userArray->type;
								$data['auth_token'] = $userArray->auth_token;
								$data['notification_count'] = !empty($notification) ? $notification : 0;
								$data['vehicle_type'] = !empty($userArray->vehicle_type) ? $userArray->vehicle_type : '';
								$data['point'] = $userArray->point;
								$mobile_verify="";
								if($userArray->otp_verify=='yes')
								{
									$mobile_verify='1';
								}else{
									$mobile_verify='0';
								}
								$data['is_mobile_verify'] = $mobile_verify;
								
								
								$data['address'] = $userArray->address ? $userArray->address : '';
								if($userArray->profile_pic &&  file_exists(public_path('/media/users/').$userArray->profile_pic ))
									{
										$data['image_url'] = url('/').'/media/users/'.$userArray->profile_pic;
									}else{
										$data['image_url'] = url('/').'/media/no-image.png';;
									}
							
								$data1 = $data;
								$status = 1;
							
								$message = "User Data Below.";
						
					  }else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Auth token is missing';
				}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
	echo json_encode(array('response' => $response_data));
	die;
}

public function notificationList(Request $request){
			
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		$auth_token = $request->header('auth');  ;
		if(!empty($auth_token)){
			
			
		if($decoded)
		{   
	
			$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
		if(!empty($userArray))
		{
			
			if(!empty($decoded['user_id'])) 
			{
				
				$notification_data = Notification::where('user_id',$decoded['user_id'])->orderBy("created_at","DESC")
							->paginate(10);
				$notification_count_data = Notification::where('user_id',$decoded['user_id'])
							->count();			
				
				if($notification_data->count()>0)
				{
					$home_data['notification_count']=$notification_count_data;
					foreach($notification_data as $data)
					{
						$home_product_data[] =  array(
												'id' =>$data->id,
												'noti_type' => !empty($data->noti_type) ? $data->noti_type : '',
												'user_type'=>!empty($data->user_type) ? $data->user_type : '',
												'notification'=> !empty($data->notification) ? $data->notification : '',
												'user_id'=>$decoded['user_id'],
												'created_at'=> date("d/m/Y h:i:s A",strtotime($data->created_at)),
												'is_read'=> $data->is_read 
												
												);
						$home_data['notification']=$home_product_data;
					}	
						$status = 1;
						$message='Notification listed below.';	
				}else{
					$home_data['notification'] = [];
					$message = 'No Notification data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
			}else{
							$status = -1;
							$message = "Session expired.";
					   }
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		}else {
					$message = 'Auth token is missing';
				}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	    echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
       die();
	}
	
	public function notificationDelete(Request $request){ 
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');  ;
		if(!empty($auth_token)){
		    if($decoded){
				$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
		if(!empty($userArray))
		{
					if(!empty($decoded['user_id'])) 
					{   
				        //echo $decoded['type']; die;
						if($decoded['type'] == "All")
						{	
								$notificationdata =  DB::table('notification')->where('user_id',$decoded['user_id'])->delete();
								
								if(!empty($notificationdata))
								{
									$status = 1;
									$message = "Notification Delete successfully.";
								}else{
									 $message = "Notification not found.";
								}	
								
						
						} else{
							$notificationdata = DB::table('notification')->where('user_id',$decoded['user_id'])->where('id',$decoded['notification_id'])->delete();
							if(!empty($notificationdata))
								{
									$status = 1;
									$message = "Notification Delete successfully.";
								}else{
									 $message = "Notification not found.";
								}	
						
						}					
					
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}

					}else{
							$status = -1;
							$message = "Session expired.";
					   }						
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}		
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	
	
	public function notificationReadStatus(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
		
		$auth_token = $request->header('auth');  
		if(!empty($auth_token)){
		    if($decoded){
				$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
		if(!empty($userArray))
		{
					if(!empty($decoded['notification_id'])) 
					{      //echo $decoded['notification_id']; die;
							$id = $decoded['notification_id'];
						$userdata = Notification::find($id);
						//$userdata =  Notification::where('id',$id)->first();
				           //echo '<pre>'; print_r($userdata); die;
						if(!empty($userdata))
						{		
							if($decoded['is_read']=="1")
								{
									$userdata->is_read = "1";
									$userdata->save();
									$status = 1;
									$message = "Notification Read successfully.";
						
									
						
							}
						}else{
							$message = "Notification Not found";
						}
							/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
					}else{
							$status = -1;
							$message = "Session expired.";
					   }							
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}

				}else {
					$message = 'Auth token is missing';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function customerRating(Request $request) {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data1 = array();
	   
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');  
		if(!empty($auth_token)){
				if($decoded) {
					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
		if(!empty($userArray))
		{
					if(!empty($decoded['order_id']) && !empty($decoded['user_id'])) 
					{
						$r=0;

							
							$orderdata = Orders::select('orders.*')
							->where('orders.id',$decoded['order_id'])->first();
							//echo '<pre>'; print_r($orderdata); die;
							$rateingchackdata = Rating::select('rating.id')
							->where('order_id',$decoded['order_id'])->where('user_id',$decoded['user_id'])->first();
						
							if(empty($orderdata) || $orderdata=="")
							{ 
								$r=1;
								$message = "No Order Found.";
							}
							if(!empty($rateingchackdata) || $rateingchackdata!="")
							{ 
								$r=1;
								$message = "You have already given rating.";
							}
							if($r==0)
							{
								
								$rating = new Rating;
								$rating->user_id =$decoded['user_id'];
								$rating->store_id =$orderdata->store_id;
								$rating->vendor_id =$orderdata->vendor_id;
								$rating->outlet_id =$orderdata->outlet_id;
								$rating->customer_id = $orderdata->user_id;
								$rating->order_id =$decoded['order_id'];
	
								$rating->customer_review =$decoded['customer_review'] ? $decoded['customer_review'] : '';
								$rating->customer_rating =$decoded['customer_rating'] ? $decoded['customer_rating'] : '';
								
								$rating->save();

						 //$nodes = Rating::select(DB::raw( 'count( rating.id ) as count'),'total_rating as average')->where('store_id',$orderdata->store_id)->groupBy('total_rating')->get();
						 
						 
						 //user rating
						$userRating = DB::table('rating')->where('customer_id', $orderdata->user_id)->avg('customer_rating'); 
						$user_data = User::find($orderdata->user_id)->first();
						$user_data->rating=round($userRating,1);
						$user_data->save();
						
							$status = 1;
							$message = "User Rating Successfully Given.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
		$response_data = array('status'=>$status,'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	}
public function orderHistory(Request $request){ 
	$id = Input::get('user_id'); 
	$pd_status = Input::get('status'); 
	$type = Input::get('type'); 
	
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = array();
	$data1['total_count'] = 0;
	$data1['list'] = array();
	$users = (object) array();
	$auth_token = $request->header('auth');  ;
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	//$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	if($auth_token)
	{
  					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$id)->first();
					if($userArray){
								
								$orderdata = Orders::select('orders.id','orders.picker_id','orders.picker_status','orders.delivery_boy_id','orders.delivery_boy_status','orders.status','orders.order_address','orders.order_completed_date',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name',
								'stores.image','rating.total_rating','rating.customer_rating as dcustomer_rating',DB::raw('CONCAT(users.first_Name, " ",users.last_Name) AS full_name'),'users.email','users.mobile','users.device_id','users.id as userid','users.country_code','stores.address as storeaddress','stores.lat as storelatitude','stores.lng as storelongitude','orders.order_latitude','orders.order_longitude')
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->leftJoin('users', 'orders.user_id', '=', 'users.id')
								//->groupBy('rating.order_id')
								 ->whereNull('rating.delivery_boy_id')
								->orderBy("orders.created_at","DESC");	
								
								if(!empty($id))
								{
									if($type == 'picker')
									{
										$orderdata = $orderdata->where('orders.picker_id',$id);
									} else {
										$orderdata = $orderdata->where('orders.delivery_boy_id',$id);
									}
								}	
								
								if(!empty($pd_status))
									{
										
										if($type == 'picker')
											{  	
												if($pd_status == 2)
												{ 
													$orderdata = $orderdata->whereIn('orders.picker_status',[1,2]);
												}else{
													$orderdata = $orderdata->where('orders.picker_status',$pd_status);
												}
											} else {
												//$orderdata = $orderdata->where('orders.delivery_boy_status',$pd_status);
												
												if($pd_status == 2)
												{ 
													$orderdata = $orderdata->whereIn('orders.delivery_boy_status',[1,2]);
												}else{
													$orderdata = $orderdata->where('orders.delivery_boy_status',$pd_status);
												}
											}
									}
								$data1['total_count'] = $orderdata->count();
								$orderdata = $orderdata->paginate(10);
								//$orderdatacount = $orderdata;
								//$orderdatacount = $orderdatacount->count();
								//$data1['total_count'] = $orderdatacount; 
								//$data1['total_count'] = $orderdata->count();								
								//$orderdata = $orderdata->paginate(10);
								//echo $orderdata->count(); die;	
								//$orderdatacount = $orderdatacount->get();
								
								$data1['list'] = array();
								//echo '<pre>'; print_r($orderdata); die;
								foreach($orderdata as $data)
								{
									$users = array("full_name" => !empty($data->full_name) ? $data->full_name : '',												"id"=> !empty($data->userid) ? $data->userid : 0,
									            "email"=> !empty($data->email) ? $data->email : '',
												"mobile"=> !empty($data->mobile) ? $data->mobile : '',
												"country_code" => !empty($data->country_code)  ? $data->country_code :'',
												"address"=> !empty($data->order_address) ? $data->order_address : '0',
												"latitude" => !empty($data->order_latitude) ? $data->order_latitude : '0',
												"longitude" => !empty($data->order_longitude) ? $data->order_longitude : '0',
												"device_id" => !empty($data->device_id) ? $data->device_id : '',
												 );
									$product_ids=explode(',',$data->product_id);
									//count($product_ids);
									$var="";
									$items="";
									for($i=0;$i<count($product_ids);$i++)
									{
										$orderdata = Product::select('products.name')
										->where('id',$product_ids[$i])
										->first();
										$items.=$var.$orderdata->name;
										$var=",";
									}
									$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','product_inventories.discount_price','product_inventories.weight','product_inventories.weight_unit','category.name as cat_name')
									->where('order_items.order_id',$data->id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();
									$product_data=array();
									foreach($orderitems as $dat)
									{      
									        if(!empty($dat->weight_unit)){
												$weight_unit = DB::table('weight_unit')->where('id',$dat->weight_unit)->select('name')->first();
											}
											$prd_img_data = Productimages::where('product_id',$dat->product_id)->first();
											$image="";
											if(isset($prd_img_data) && $prd_img_data->image!=""){
												$image=URL::to('/media/products').'/'.$prd_img_data->image;
											}
											
											//dd($prd_img_data);
											$subtotal=$dat->quantity * $dat->price;
											$product_data[] = array('id' => $dat->id,
											
											'store_id' => $dat->store_id,
											'product_id' =>$dat->product_id,
											'product_name' =>$dat->name,
											'product_image'=>$image,
											'qty'=>$dat->quantity,
											'orignal_price'=>$dat->product_price,
											'weight'=>$dat->weight,
											'weight_unit'=>!empty($weight_unit->name) ? $weight_unit->name : '',
											'discount_price'=>$dat->discount_price,
											'subtotal'=>number_format($subtotal,2, '.', ''),
											'category'=>$dat->cat_name,
											
											);
									}

									if($data->payment_mode=='1')
									{
										$payment_type="Cash On Delivery";
									}else if($data->payment_mode=='2'){
										$payment_type="Card Payment";
									}else{
										$payment_type="Onlline Payment";
									}
									$rating=0;
												if($data->dcustomer_rating){
													$rating=$data->dcustomer_rating;
													$rating = $this->wc_float_to_string($rating);
												}
								$data1['list'][]=array('store_name'=>!empty($data->name) ? $data->name : '','image'=>URL::to('/media/store').'/'.$data->image,
									'order_id'=>!empty($data->id) ? $data->id : '',
									'picker_id' =>!empty($data->picker_id) ? $data->picker_id : 0,
									'picker_status' => $data->picker_status, 
									'delivery_boy_id' =>!empty($data->delivery_boy_id) ? $data->delivery_boy_id : 0,
									'delivery_boy_status' =>$data->delivery_boy_status ,
									'order_completed_date'=> !empty($data->order_completed_date) ? $data->order_completed_date : '',
									'status' =>$data->status,
									'is_cancelled'=>!empty($data->is_cancelled) ? $data->is_cancelled : 0,
									'store_address'=>!empty($data->storeaddress) ? $data->storeaddress : '0',
									'store_latitude'=>!empty($data->storelatitude) ? $data->storelatitude : '0',
									'store_longitude'=>!empty($data->storelongitude) ? $data->storelongitude : '0',
									'items'=>!empty($items) ? $items :'',
									'payment_mode'=>!empty($payment_type) ? $payment_type : '',
									'total_amount'=>!empty($data->total_amount) ? $data->total_amount : '0.00',
									'total_shipping_amount'=>!empty($data->total_shipping_amount) ? $data->total_shipping_amount : '0.00',
									'net_amount'=>!empty($data->net_amount) ? $data->net_amount : '0.00', 
									'rating'=>$rating,
									'order_delivery_status' =>  !empty($data->order_delivery_status) ? $data->order_delivery_status : 0,
									'instructions'=>!empty($data->instructions) ? $data->instructions : '',
									'order_date'=>date("d/m/Y h:i:s A",strtotime($data->created_at)),
									'users_details'=>!empty($users) ? $users : '',
									'products'=>!empty($product_data) ? $product_data : '',
								);
									
									
								}
							
								//$data1 = $data;
								$status = 1;
							
								$message = "Order History List.";
						
					  }else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Auth token is missing';
				}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
	echo json_encode(array('response' => $response_data));
	die;
}

 
public function heatMpaData(Request $request){ 
	$id = Input::get('user_id'); 
	$pd_status = Input::get('status'); 
	$type = Input::get('type'); 
	
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = array();
	
	
	$users = (object) array();
	$auth_token = $request->header('auth');  ;
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	//$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	if($auth_token)
	{
  					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$id)->first();
					if($userArray){
								
								$orderdata = Orders::select('orders.id','orders.status','orders.order_address','orders.order_latitude',
								'orders.order_longitude','orders.created_at')
								->orderBy("created_at","DESC")
								->where('order_delivery_status',5)	
								->get();
								
								//echo '<pre>'; print_r($orderdata); die;
								foreach($orderdata as $data)
								{
								$data1[]=array(
									'order_id'=>!empty($data->id) ? $data->id : '',
									'status' =>$data->status,
									'order_latitude'=>!empty($data->order_latitude) ? $data->order_latitude : 0,
									'order_longitude'=>!empty($data->order_longitude) ? $data->order_longitude : 0);
									
								}
							
								//$data1 = $data;
								$status = 1;
							
								$message = "Order Heat Map List.";
						
					  }else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Auth token is missing';
				}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
	echo json_encode(array('response' => $response_data));
	die;
}

public function orderDetails(Request $request){ 
	//$id = Input::get('user_id'); 
	//$pd_status = Input::get('status'); 
	//$type = Input::get('type'); 
	$id = Input::get('order_id');
	$user_id = Input::get('user_id');
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	
	$auth_token = $request->header('auth');  ;
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	//$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	if($auth_token)
	{
  					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$user_id)->first();
					if($userArray){
								
								$orderdata = Orders::select('orders.id','orders.picker_id','orders.picker_status','orders.delivery_boy_id','orders.delivery_boy_status','orders.status','orders.order_address','orders.order_completed_date',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name as storename',
								'stores.image as storeimage','rating.customer_rating',DB::raw('CONCAT(users.first_Name, " ",users.last_Name) AS full_name'),'users.email','users.id as userid','users.mobile','users.device_id','users.country_code','stores.address as storeaddress','stores.lat as storelatitude','stores.lng as storelongitude','orders.order_latitude','orders.order_longitude')
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->leftJoin('users', 'orders.user_id', '=', 'users.id')
								 ->whereNull('rating.delivery_boy_id')
								->where('orders.id',$id)
								->first();		
								//echo '<pre>'; print_r($orderdata); die;
								if(!empty($orderdata))
								{    
							        $users = array("full_name" => !empty($data->full_name) ? $data->full_name : '',													"id" => !empty($data->userid) ? $data->userid : 0,
									            "email"=> !empty($orderdata->email) ? $orderdata->email : '',
												"mobile"=> !empty($orderdata->mobile) ? $orderdata->mobile : '',
												"country_code"=>!empty($orderdata->country_code) ? $orderdata->country_code : '',
												"address"=> !empty($orderdata->order_address) ? $orderdata->order_address : '',
												"latitude" => !empty($orderdata->order_latitude) ? $orderdata->order_latitude : '0',
												"longitude" => !empty($orderdata->order_longitude) ? $orderdata->order_longitude : '0',
												"device_id" => !empty($orderdata->device_id) ? $orderdata->device_id : '',
												 );
									$product_ids=explode(',',$orderdata->product_id);
									//count($product_ids);
									$var="";
									$items="";
									for($i=0;$i<count($product_ids);$i++)
									{
										$orderdata1 = Product::select('products.name')
										->where('id',$product_ids[$i])
										->first();
										$items.=$var.$orderdata1->name;
										$var=",";
									}
									
									$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','category.name as cat_name','product_inventories.weight','product_inventories.weight_unit')
									->where('order_items.order_id',$id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();
									
									$product_data=array();
									foreach($orderitems as $dat)
									{
										    if(!empty($dat->weight_unit)){
												$weight_unit = DB::table('weight_unit')->where('id',$dat->weight_unit)->select('name')->first();
											}
											$prd_img_data = Productimages::where('product_id',$dat->product_id)->first();
											$image="";
											if(isset($prd_img_data) && $prd_img_data->image!=""){
												$image=URL::to('/media/products').'/'.$prd_img_data->image;
											}
											
											//dd($prd_img_data);
											$subtotal=$dat->quantity * $dat->price;
											$product_data[] = array('id' => $dat->id,
											
											'store_id' => $dat->store_id,
											'product_id' =>$dat->product_id,
											'product_name' =>$dat->name,
											'product_image'=>$image,
											'qty'=>$dat->quantity,
											'weight'=>$dat->weight,
											'weight_unit'=>!empty($weight_unit->name) ? $weight_unit->name : '',
											'orignal_price'=>$dat->product_price,
											'discount_price'=>$dat->discount_price,
											'subtotal'=>number_format($subtotal,2, '.', ''),
											'category'=>$dat->cat_name,
											
											);
									}

									if($orderdata->payment_mode=='1')
									{
										$payment_type="Cash On Delivery";
									}else if($orderdata->payment_mode=='2'){
										$payment_type="Card Payment";
									}else{
										$payment_type="Onlline Payment";
									}
									$rating=0;
												if($orderdata->customer_rating){
													$rating=$orderdata->customer_rating;
													$rating = $this->wc_float_to_string($rating);
												}
									//echo $orderdata->srid; die;			
								$data1=array('store_name'=>!empty($orderdata->storename) ? $orderdata->storename : '','image'=>URL::to('/media/store').'/'.$orderdata->storeimage,
									'order_id'=>$orderdata->id,
									'picker_id' =>!empty($orderdata->picker_id) ? $orderdata->picker_id : 0,
									'picker_status' => !empty($orderdata->picker_status) ? $orderdata->picker_status : 0, 
									'delivery_boy_id' =>!empty($orderdata->delivery_boy_id) ? $orderdata->delivery_boy_id : 0,
									'delivery_boy_status' => !empty($orderdata->delivery_boy_status) ? $orderdata->delivery_boy_status : 0,
									'order_completed_date'=> !empty($orderdata->order_completed_date) ? $orderdata->order_completed_date : '',
									'status' => !empty($orderdata->status) ? $orderdata->status : 0,
									'is_cancelled'=>!empty($orderdata->is_cancelled) ? $orderdata->is_cancelled : 0,
									'store_address'=>!empty($orderdata->storeaddress) ? $orderdata->storeaddress : '0',
									'store_latitude'=>!empty($orderdata->storelatitude) ? $orderdata->storelatitude : '0',
									'store_longitude'=>!empty($orderdata->storelongitude) ? $orderdata->storelongitude : '0',
									'items'=>!empty($items) ? $items :'',
									'payment_mode'=>!empty($payment_type) ? $payment_type : '',
									'total_amount'=>!empty($orderdata->total_amount) ? $orderdata->total_amount : '0.00',
									'total_shipping_amount'=>!empty($orderdata->total_shipping_amount) ? $orderdata->total_shipping_amount : '0.00',
									'net_amount'=>!empty($orderdata->net_amount) ? $orderdata->net_amount : '0.00',
									'rating'=>$rating,
									'order_delivery_status' =>  !empty($orderdata->order_delivery_status) ? $orderdata->order_delivery_status : 0,
									'instructions'=>!empty($orderdata->instructions) ? $orderdata->instructions : '',
									'order_date'=>date("d/m/Y h:i:s A",strtotime($orderdata->created_at)),
									'users_details'=>!empty($users) ? $users : '',
									'products'=>!empty($product_data) ? $product_data : '',
								);
									
									
								
							
								//$data1 = $data;
								$status = 1;
							
								$message = "Order History Detail.";
								} else{
									$message = "Order Detail Not Found";
								}
						
					  }else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Auth token is missing';
				}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
	echo json_encode(array('response' => $response_data));
	die;
}

public function jobList(Request $request){ 
	 $id = Input::get('user_id'); 
	//$pd_status = Input::get('status'); 
	
	$type = Input::get('type'); 
	$max_distance = Input::get('max_distance'); 
	$min_distance = Input::get('min_distance'); 
	$order_type = Input::get('order_type');
	$start_date = Input::get('start_date');
	$end_date = Input::get('end_date');
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = array();
	$data1['total_count'] = 0;
	$data1['list'] = array();
	$auth_token = $request->header('auth');  
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	//$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	if($auth_token)
	{
  					$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$id)->first();
					if($userArray){
						
						        if($max_distance != "" && $type=="driver")
									{     
									       $lat = $userArray->latitude;
									       $long = $userArray->longitude;
									       $gr_circle_radius = 6371;
									       $max_distance = $max_distance;
										   $min_distance = $min_distance;
										$haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(order_latitude)) * cos(radians(order_longitude) - radians($long)) + sin(radians($lat)) * sin(radians(order_latitude))))";
										
								$orderdata = Orders::select('orders.id','orders.picker_id','orders.picker_status','orders.delivery_boy_id','orders.delivery_boy_status','orders.status','orders.order_address','orders.order_completed_date',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name',
								'stores.image','rating.customer_rating',DB::raw('CONCAT(users.first_Name, " ", users.last_Name) AS full_name'),'users.email','users.mobile','users.device_id','users.id as userid','users.country_code','stores.address as storeaddress','stores.lat as storelatitude','stores.lng as storelongitude','orders.order_latitude','orders.order_longitude')
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->leftJoin('users', 'orders.user_id', '=', 'users.id')
								->selectRaw("{$haversine} AS distance")
								 ->whereRaw("{$haversine} < ?", [$max_distance])
								 ->whereRaw("{$haversine} > ?", [$min_distance])
								->groupBy('rating.order_id')
								 ->whereNull('rating.delivery_boy_id')
								->orderBy("orders.created_at","DESC");
							
							} else{ 
								$orderdata = Orders::select('orders.id','orders.picker_id','orders.picker_status','orders.delivery_boy_id','orders.delivery_boy_status','orders.status','orders.order_address','orders.order_completed_date',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name',
								'stores.image','rating.customer_rating',DB::raw('CONCAT(users.first_Name, " ", users.last_Name) AS full_name'),'users.email','users.mobile','users.id as userid','users.country_code','users.device_id','stores.address as storeaddress','stores.lat as storelatitude','stores.lng as storelongitude','orders.order_latitude','orders.order_longitude')
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->leftJoin('users', 'orders.user_id', '=', 'users.id')
								 ->whereNull('rating.delivery_boy_id')
								//->groupBy('rating.order_id')
								->orderBy("orders.created_at","DESC");
								
							} 
							
								if(!empty($id))
								{
									if($type == 'picker')
									{
										//if($order_type == "all")
											  // {											   
													$users = DB::table('users')->where('id', $id)->first();
													$stores = DB::table('stores')->where('user_id', $users->vendor_id)->first();
													
													$orderdata = $orderdata->where('orders.store_id',$stores->id);
											 //  } else {
													//$orderdata = $orderdata->where('orders.picker_id',$id);
											  // }
											   
											  
									} else {
										if($order_type == "mine" )
										{	
											$orderdata = $orderdata->where('orders.delivery_boy_id',$id);
										}	
									}
								}	
								
								if(!empty($order_type))
									{
										//echo $order_type; die;
										if($type == 'picker')
											{  
										       if($order_type == "mine")
											   {
													$orderdata = $orderdata->whereIn('orders.picker_status',[1,2,3])->where('orders.picker_id', '=',$id);
											   } elseif($order_type == "all") {
												  
													$orderdata = $orderdata->where('orders.picker_status',0);
										
											   }else { 
												   $orderdata = $orderdata->whereIn('orders.picker_status',[0,1,2,3])
												   ->where(function ($query)use ($id) {
													$query->where('orders.picker_id', '=',$id)
														  ->orWhere('orders.picker_id', '=', 0);
												});
												//where('orders.picker_id', $id)->orWhere('orders.picker_id','0');
											   }	
											   		
											} elseif($type == 'driver') {
												 if($order_type == "mine")
													{
													$orderdata = $orderdata->whereIn('orders.delivery_boy_status',[1,2,3]);
													}elseif($order_type == "all"){
														$orderdata = $orderdata->where('orders.picker_status', '!=', 0);
														$orderdata = $orderdata->where('orders.delivery_boy_status', 0);														
													}
													else { 
														$zoneId = 0;
														## Find Zone 
														$zoneData = DB::table('zone')->where('status',1)->get();
														foreach($zoneData as $zoneValue) {
															$point = array();
															$zoneDetailData = DB::table('zone_details')->where('zone_id',$zoneValue->id)->get();
															$polygons = array();
															
															foreach($zoneDetailData as $zoneDetailValue) {
																$polygon = array();
																$polygon[] = $zoneDetailValue->latitude;
																$polygon[] = $zoneDetailValue->longitude;
																$polygons[] = implode(" ",$polygon);
															}
															
															$point[] =  $userArray->latitude;
															$point[] =  $userArray->longitude;
															//echo '<pre>'; print_r($polygon); die;
															
															$points = implode(" ",$point);
															if(!empty($polygons)) { 
																$res = $this->pointInPolygon($points, $polygons);
																//echo $zoneValue['Zone']['id'].' -> '.$res ;
																//echo '<br>';
																if($res) {
																	$zoneId = $zoneValue->id;
																	break;
																}
															}
														}
														//echo $zoneId; die;
													$orderdata = $orderdata->where('orders.zone_id',$zoneId);
													
													}
											}
									}
									
									if ($start_date!="" && $end_date!=""){
		
												$start_date = date('Y-m-d H:i:s', strtotime($start_date));
												$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
												$orderdata = $orderdata->whereBetween('orders.created_at', [$start_date, $end_date]);
												
									
											} else if ($start_date!="" && $end_date=="") {
									
												$start_date = date('Y-m-d H:i:s', strtotime($start_date));
												$orderdata =$orderdata->where('orders.created_at',">=",$start_date);
									
											
											} else if ($start_date=="" && $end_date!="") {
									
												$end_date = date('Y-m-d H:i:s', strtotime($end_date));
												$orderdata = $orderdata->where('orders.created_at',"<=",$end_date);
									
											}
										//$orderdata = $orderdata->get();
										//echo '<pre>'; print_r($orderdata); die;
								$orderdatacount = $orderdata;
								$orderdatacount = $orderdatacount->count();
														
								$orderdata = $orderdata->paginate(10);
								//dd($orderdata);
								//echo '<pre>'; print_r($orderdata); die;
								$data1['total_count'] = $orderdatacount; 
								$data1['list'] = array();
								//echo $orderdata->count(); die;
								foreach($orderdata as $data)
								{    
								   $users = array("full_name" => !empty($data->full_name) ? $data->full_name : '',					
												"id" => !empty($data->userid) ? $data->userid : 0,
									            "email"=> !empty($data->email) ? $data->email : '',
												"mobile"=> !empty($data->mobile) ? $data->mobile : '',
												"country_code" => !empty($data->country_code) ? $data->country_code : 0,
												"address"=> !empty($data->order_address) ? $data->order_address : '',
												"latitude" => !empty($data->order_latitude) ? $data->order_latitude : '0',
												"longitude" => !empty($data->order_longitude) ? $data->order_longitude : '0',
												"device_id" => !empty($data->device_id) ? $data->device_id : '',
												 );
									$product_ids=explode(',',$data->product_id);
									
									$var="";
									$items="";
									for($i=0;$i<count($product_ids);$i++)
									{
										$orderdata = Product::select('products.name')
										->where('id',$product_ids[$i])
										->first();
										$items.=$var.$orderdata->name;
										$var=",";
									}
									$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','category.name as cat_name','product_inventories.weight','product_inventories.weight_unit')
									->where('order_items.order_id',$data->id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();
									$product_data=array();
									foreach($orderitems as $dat)
									{
										   if(!empty($dat->weight_unit)){
												$weight_unit = DB::table('weight_unit')->where('id',$dat->weight_unit)->select('name')->first();
											}
											$prd_img_data = Productimages::where('product_id',$dat->product_id)->first();
											$image="";
											if(isset($prd_img_data) && $prd_img_data->image!=""){
												$image=URL::to('/media/products').'/'.$prd_img_data->image;
											}
											
											//dd($prd_img_data);
											$subtotal=$dat->quantity * $dat->price;
											$product_data[] = array('id' => $dat->id,
											
											'store_id' => $dat->store_id,
											'product_id' =>$dat->product_id,
											'product_name' =>$dat->name,
											'product_image'=>$image,
											'qty'=>$dat->quantity,
											'weight'=>$dat->weight,
											'weight_unit'=>!empty($weight_unit->name) ? $weight_unit->name : '',
											'orignal_price'=>$dat->product_price,
											'discount_price'=>$dat->discount_price,
											'subtotal'=>number_format($subtotal,2, '.', ''),
											'category'=>$dat->cat_name,
											
											);
									}

									if($data->payment_mode=='1')
									{
										$payment_type="Cash On Delivery";
									}else if($data->payment_mode=='2'){
										$payment_type="Card Payment";
									}else{
										$payment_type="Onlline Payment";
									}
									$rating=0;
												if($data->customer_rating){
													$rating=$data->customer_rating;
													$rating = $this->wc_float_to_string($rating);
												}
								$data1['list'][]=array('store_name'=>!empty($data->name) ? $data->name : '','image'=>URL::to('/media/store').'/'.$data->image,
									'order_id'=>!empty($data->id) ? $data->id : '',
									'picker_id' =>!empty($data->picker_id) ? $data->picker_id : 0,
									'picker_status' => !empty($data->picker_status) ? $data->picker_status : 0, 
									'delivery_boy_id' =>!empty($data->delivery_boy_id) ? $data->delivery_boy_id : 0,
									'delivery_boy_status' => !empty($data->delivery_boy_status) ? $data->delivery_boy_status :0,
									'status' =>!empty($data->status) ? $data->status : 0,
									'order_completed_date'=> !empty($data->order_completed_date) ? $data->order_completed_date : '',
									'is_cancelled'=>!empty($data->is_cancelled) ? $data->is_cancelled : 0,
									'store_address'=>!empty($data->storeaddress) ? $data->storeaddress : '0',
									'store_latitude'=>!empty($data->storelatitude) ? $data->storelatitude : '0',
									'store_longitude'=>!empty($data->storelongitude) ? $data->storelongitude : '0',
									'items'=>!empty($items) ? $items :'',
									'payment_mode'=>!empty($payment_type) ? $payment_type : '',
									'total_amount'=>!empty($data->total_amount) ? $data->total_amount : '0.00',
									'total_shipping_amount'=>!empty($data->total_shipping_amount) ? $data->total_shipping_amount : '0.00',
									'net_amount'=>!empty($data->net_amount) ? $data->net_amount : '0.00',
									'rating'=>$rating,
									'order_delivery_status' =>  !empty($data->order_delivery_status) ? $data->order_delivery_status : 0,
									'instructions'=>!empty($data->instructions) ? $data->instructions : '',
									'order_date'=>date("d/m/Y h:i:s A",strtotime($data->created_at)),
									'users_details'=>!empty($users) ? $users : '',
									'products'=>!empty($product_data) ? $product_data : '',
									
								);
									
									
								}
							
								//$data1 = $data;
								$status = 1;
							
								$message = "Order History List.";
						
					  }else{
							$status = -1;
							$message = "Session expired.";
					   }	
				}else {
					$message = 'Auth token is missing';
				}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
	echo json_encode(array('response' => $response_data));
	die;
}
public function wc_float_to_string( $float ) {
  if ( ! is_float( $float ) ) {
    return $float;
  }

  $locale = localeconv();
  $string = strval( $float );
  $string = str_replace( $locale['decimal_point'], '.', $string );

  return $string;
}
public function logout(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$userdata = User::find($id);
						
						if(!empty($userdata))
						{		
							
									$userdata->device_id = "";
									$userdata->auth_token = "";
									$userdata->save();
									$status = 1;
									$message = "Logout successfully.";
						
									
						
							
						}else{
							$message = "User Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function updateDevicetoken(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['device_id']) && !empty($decoded['device_type'])) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$userdata = User::find($id);
						
						if(!empty($userdata))
						{		
							
									$userdata->device_id = $decoded['device_id'];
									$userdata->device_type = $decoded['device_type'];
									$userdata->save();
									$status = 1;
									$message = "Device token update successfully.";
						
									
						
							
						}else{
							$message = "User Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function reportIssue(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		$auth_token = $request->header('auth');
		//$decoded        = $_REQUEST;
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['title']) &&  !empty($decoded['order_id'])) 
					{      
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							    
									$Issue = new Issue();
									$Issue->user_id = $decoded['user_id'];
									$Issue->title = $decoded['title'];
									$Issue->description = $decoded['description'];
									$Issue->order_id = $decoded['order_id'];
									$Issue->save();
									$status = 1;
									$message = "Your report has been submitted successfully.";
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function pickHours(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data =  array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		$auth_token = $request->header('auth');
		//$decoded        = $_REQUEST;
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{      
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							    
									$regs =	DB::table('orders')
										->select(DB::raw('count(id) as mycount,HOUR(created_at) as hour'))
										->groupBy('hour')
										->orderBy('mycount','desc')
										->first();
									$first_hour = date("g:i A", strtotime("$regs->hour:00")); 
									$time =  $regs->hour.':00';
									$timestamp = strtotime($time);
									$timestamp_one_hour_later = $timestamp + 3600;
									$second_hour = strftime('%H:%M', $timestamp_one_hour_later);
									$second_hour = date("g:i A", strtotime("$second_hour")); 
									$data = array('pick_hours' => $first_hour.' - '.$second_hour);
									$status = 1;
									$message = "Pick hours time.";
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function updateLatLong(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//echo '<pre>';
		//print_r($decoded); die;
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['latitude']) && !empty($decoded['longitude'])) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$userdata = User::find($id);
						
						if(!empty($userdata))
						{		
							
									$userdata->latitude = $decoded['latitude'];
									$userdata->longitude = $decoded['longitude'];
									//$userdata->device_type = $decoded['device_type'];
									$userdata->save();
									$status = 1;
									$message = "lat long update successfully.";
						
									
						
							
						}else{
							$message = "User Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function acceptPickerOrder(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['order_id'])  && !empty($decoded['picker_packing_time'])) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$orderdata = Orders::find($decoded['order_id']);
						
						if(!empty($orderdata))
						{		
							     if($orderdata->picker_id == 0)
								 {	
								    $userdata = User::find($decoded['user_id']);
								    $userdata->order_process_status = 1;
									$userdata->save();
									$orderdata->picker_id = $decoded['user_id'];
									$orderdata->picker_packing_time = $decoded['picker_packing_time'];
									$orderdata->picker_status = 1;
									//$orderdata->order_delivery_status = 2;
									$orderdata->save();
									
									$vendors = DB::table('users')->where('id',$decoded['user_id'])->first();
									$stores = DB::table('stores')->where('user_id',$vendors->vendor_id)->first();
									/*
									$lat = $stores->lat;
									$long = $stores->lng;
									$gr_circle_radius = 6371;
									$max_distance = 5;
									$distance_query=$gr_circle_radius.' * acos( cos( radians('.$lat.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$long.') ) + sin( radians('.$lat.') ) * sin( radians( latitude ) ) ) )';
									
									$userstoken = User::where('status','1')->where('order_process_status',0)->whereNotNull('auth_token')->where('is_notification','1')->where('type',2)->where('is_online',1)->where('order_status',0)->select('device_id', DB::raw('CONCAT(first_Name, " ", last_Name) AS full_name'),DB::raw('( '.$distance_query.' AS distance'))->having('distance', '<', $max_distance)->pluck('device_id')->all();
									 */
									$deliveryBoys = User::where('status','1')->where('order_process_status',0)->whereNotNull('auth_token')->where('is_notification','1')->where('type',2)->where('is_online',1)->where('order_status',0)->select('device_id','id','latitude','longitude')->get(); 
									 //echo '<pre>'; print_r($deliveryBoys); die;
									 $zoneId = array();
									 $deliveryboyId = array();
									 foreach($deliveryBoys as $boy)
									 {   //echo 'asd'; die;
									 //echo $boy->device_id;
										$point = array();
										$zoneDetailData = DB::table('zone_details')->where('zone_id',$orderdata->zone_id)->get();
										$polygons = array();
										foreach($zoneDetailData as $zoneDetailValue) {
											$polygon = array();
											$polygon[] = $zoneDetailValue->latitude;
											$polygon[] = $zoneDetailValue->longitude;
											$polygons[] = implode(" ",$polygon);
										}
										$point[] =  $boy->latitude;
										$point[] =  $boy->longitude;
										//echo '<pre>'; print_r($point); die;
										$points = implode(" ",$point);
									    if(!empty($polygons)){ 
											$res = $this->pointInPolygon($points, $polygons);
											//echo $zoneValue['Zone']['id'].' -> '.$res ;
											//echo '<br>';
											//$zoneId = array();
											
											if($res){
												
												$zoneId[] = $boy->device_id;
												$deliveryboyId[] = $boy->id;
												///break;
											}
										}
									
									
									 }
									 
									//if($zoneId)
									//{			
									$userstoken = $zoneId; 
									$tokenList = array();
									foreach($userstoken as $token)
									{   if(!empty($token)){
										$tokenList[] = $token;
									}
									}
									$title = "Bringoo-OrderId #".$decoded['order_id'];
									$message = "New order available";
									$extraNotificationData = ["order_id" => $decoded['order_id'],"notification_type" =>'new_request_driver','store_name'=>$stores->name,'store_address'=>$stores->address,'store_logo'=>URL::to('/media/store').'/'.$stores->image, 'lat'=>$stores->lat,'lng'=>$stores->lng, 'picker_time'=>$decoded['picker_packing_time']];
									//echo '<pre>'; print_r($tokenList); die;
									$notification = $this->send_notification($tokenList,$title,$message,$extraNotificationData);
									//echo $notification; die;
									if($notification == 1)
									{   
										/*$users = DB::table('users')->where('is_notification','1')->where('type',2)->where('order_status',0)->where('status', '1')->where('is_online',1)->select('id', DB::raw('( '.$distance_query.' AS distance'))->having('distance', '<', $max_distance)->whereNotNull('auth_token')->get();  */
										foreach($deliveryboyId as $boyId)
										{   
											$notification = new Notification();
											$notification->user_id = $boyId;
											$notification->noti_type = $title;
											$notification->notification = $message;
											$notification->is_read = 0;
											$notification->save();
										}
										
									}
									
									$status = 1;
									$message = "Order accept successfully.";
						
									//} else{
										//$message = "Delivery boy not found.";
										
									//}
								 }else{
									$message = "This Order already has been accepted by someone else."; 
								 }
							
						}else{
							$message = "Order Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function acceptDriverOrder(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['order_id'])  ) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$orderdata = Orders::find($decoded['order_id']);
						
						if(!empty($orderdata))
						{		//echo $orderdata->delivery_boy_id; die;
							     if($orderdata->delivery_boy_id == 0)
								 {	
								    $userdata = User::find($decoded['user_id']);
								    $userdata->order_process_status = 1;
									$userdata->save();
									$orderdata->delivery_boy_id = $decoded['user_id'];
									
									$orderdata->delivery_boy_status = 1;
									$orderdata->order_delivery_status = 3;
									$orderdata->save();
									
									$customerData = User::where('id',$orderdata->user_id)->where('is_notification','1')->select('device_id','id')->first(); 
									//echo '<pre>'; print_r($customerData); die;
									if($customerData)
									{
										 $userstoken = $customerData->device_id; 
										
										$title = "Bringoo- OrderId #".$decoded['order_id'];
										$message = "Driver has been assigned for your order";
										//$extra = array();
										$extraNotificationData = ["notification_type" => 'order_process',"order_id" =>$decoded['order_id']];
										$notification = $this->send_notificationSingle($userstoken,$title,$message,$extraNotificationData);
										//echo $notification; die;
										if($notification == 1)
										{   
											   
												$notification = new Notification();
												$notification->user_id = $customerData->id;
												$notification->noti_type = $title;
												$notification->notification = $message;
												$notification->is_read = 0;
												$notification->save();
											
										}
									}
									$status = 1;
									$message = "Order accept successfully.";
						
									//} else{
										//$message = "Delivery boy not found.";
										
									//}
								 }else{
									$message = "This Order already has been accepted by someone else."; 
								 }
							
						}else{
							$message = "Order Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function send_notificationSingle($token,$title,$message,$extraNotificationData) 
	{   $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
		$notification = [
					'title' => $title,
					'body' => $message,
					'image-url' => "ic_notification_smal",
					'sound' => 'mySound',
				];
				//echo '<pre>'; print_r($userstoken); die;
				//$extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
				if(!empty($extraNotificationData))
				{
				$fcmNotification = [
					//'registration_ids' => $tokenList, //multple token array
					'to'        => $token, //single token
					'notification' => $notification,
					'data' => $extraNotificationData
				];
				}else{
					$fcmNotification = [
					//'registration_ids' => $tokenList, //multple token array
					'to'        => $token, //single token
					'notification' => $notification,
					//'data' => $extraNotificationData
				];
				}
				$headers = ['Authorization:key=AAAA_b1huEM:APA91bGZ5mpD9dCcfVNQ7U-TKOQtIbUvHdgQeTvP6RZFhTqFw2mXlZif0rTtcelhGMdDydp_jHqX1frW49zgSwDOJxwQByLHrO2KRLwkwb7HpkXOxe6wF2qEL1JaT1oQMvllVNy34bAO',
					'Content-Type: application/json'
				];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$fcmUrl);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
				$result = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($result, true);
				//return $result['success']; 
				if($result['success']){
					
					return 1;
					
					// echo json_encode(array('class'=>'success','message'=>'Push Notification Send Successfully.'));die;
				}else{
					return 0;
					//\Session::flash('error', 'Push Notification Not Send Successfully, '.$result['results'][0]['error'].' ,Please try again.');
					//echo json_encode(array('class'=>'success','message'=>'Push Notification Not Send Successfully'));die;
				}
	}
	public function changeJobStatuspicker(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		$auth_token = $request->header('auth');
		if($auth_token)
		{
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['order_id']) && !empty($decoded['status']) && !empty($decoded['type'])) 
					{     
						$userArray = DB::table('users')->where('auth_token',$auth_token)->where('id',$decoded['user_id'])->first();
						if(!empty($userArray)){
							$id = $decoded['user_id'];
							$orderdata = Orders::find($decoded['order_id']);
						
						if(!empty($orderdata))
						{		
								if($decoded['type'] == 'picker')
								{
							       if($decoded['status']  == 3)
								   {
								    $userdata = User::find($decoded['user_id']);
									
								    $userdata->order_process_status = 0;
									$userdata->save();
								   }
									//$orderdata->picker_id = $decoded['user_id'];
									//$orderdata->picker_packing_time = $decoded['picker_packing_time'];
									$orderdata->picker_status = $decoded['status'];
									if($decoded['status'] == 2)
									{
										$orderdata->order_delivery_status = 2;
									}
									$orderdata->save();
									$status = 1;
									$message = "Status Change successfully.";
						
								}else{
									if($orderdata->picker_status == 3)
									{
									if($decoded['status']  == 3)
									{
										$userdata = User::find($decoded['user_id']);
									
										$userdata->order_process_status = 0;
										$userdata->point = $userdata->point + 1;
										$userdata->save();
										
										$points =  new Points();
										$points->user_id = $decoded['user_id'];
										$points->point =  +1;
										$points->save();
										
										$currentUser = DB::table('users')->where('id',$orderdata->user_id)->first();
										
										if($currentUser->referral_code)
											{
												$referralUser = DB::table('users')->where('invite_code',$currentUser->referral_code)->first();
												if(!empty($referralUser))
												{
													$bonus =  Config::get("Site.bonus") + $referralUser->bonus;
													DB::table('users')
													->where('id', $referralUser->id)
													->update(['bonus' => $bonus]);
													//$referralUser->save();
												}
												$bonusss =  Config::get("Site.bonus") + $currentUser->bonus;
												DB::table('users')
													->where('id', $currentUser->id)
													->update(['bonus' => $bonusss]);
												//$currentUser->save();
											}
										
									}
									//$orderdata->picker_id = $decoded['user_id'];
									//$orderdata->picker_packing_time = $decoded['picker_packing_time'];
									$orderdata->delivery_boy_status = $decoded['status'];
									if($decoded['status'] == 2)
									{
										$orderdata->order_delivery_status = 4;
									}
									if($decoded['status'] == 3)
									{
										$orderdata->order_delivery_status = 5;
										$orderdata->order_completed_date = date("Y-m-d h:i:sa");
									}
									$orderdata->save();
									$customerData = User::where('id',$orderdata->user_id)->where('is_notification','1')->select('device_id','id')->first(); 
									//echo '<pre>'; print_r($customerData); die;
									if($customerData)
									{
										$userstoken = $customerData->device_id; 
										
										$title = "Bringoo- OrderId #".$decoded['order_id'];
										if($decoded['status'] == 2)
										{
											$message = "Your order is on the way";
										}
										
										if($decoded['status'] == 3)
										{
											$message = "Congratulation!! You order has been delivered to you";
										}
										$extraNotificationData = ["notification_type" => 'order_process',"order_id" =>$decoded['order_id']];
										$notification = $this->send_notificationSingle($userstoken,$title,$message,$extraNotificationData);
										//echo $notification; die;
										if($notification == 1)
										{   
											
											   
												$notification = new Notification();
												$notification->user_id = $customerData->id;
												$notification->noti_type = $title;
												$notification->notification = $message;
												$notification->is_read = 0;
												$notification->save();
											
											
										}
									}
									$status = 1;
									$message = "Status Change successfully.";
						
									}else{
										$message = "Sorry!! Picker is busy with this Job, You cannot proceed until Picker complete the Job.";
									}
									
								}	
							
						}else{
							$message = "Order Not found";
						}
						
						 }else{
							$status = -1;
							$message = "Session expired.";
					   }		
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			}else {
					$message = 'Auth token is missing';
				}
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function searchProduct(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$search_detail_data=array();
		//$search_product_data=array();
		$home_store_data=array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['product_names']) && !empty($decoded['store_id'])) 
					{  

							$product_cat_data = Product::select('products.id','products.cat_id','products.name as product_name','category.name as category_name')
							->leftJoin('category', 'products.cat_id', '=', 'category.id')	
							->leftJoin('product_inventories', 'products.id', '=', 'product_inventories.product_id')	
							->whereNotNull('product_inventories.product_id')->where('products.status','1')->where('products.store_id',$decoded['store_id'])->orderBy("products.id","ASC")
							->groupBy('products.cat_id');
							
								$product_names = explode(",",$decoded['product_names']);
								
								$product_cat_data->where(function ($query) use ($product_names) {
								foreach ($product_names as $field){
										if (!empty($field)){
												$query->orWhere('products.name','LIKE','%'.$field.'%');
										}
									}
								});
						//$product_data=$product_data->paginate(10);
						$product_cat_data=$product_cat_data->get();
						//die;
					//echo '<pre>'; print_r($product_data); die;
					//dd($product_data);
					
					if($product_cat_data->count()>0){
					foreach($product_cat_data as $datas)
					{  //echo $datas->cat_id; die;
						$product_data = Product::select('products.id','products.cat_id','products.name as product_name','category.name as category_name','stores.name as store_name','stores.category_id','product_inventories.price'
								,'product_inventories.discount_price','product_inventories.stock','product_images.image')
								->leftJoin('stores', 'products.store_id', '=', 'stores.id')	
								->leftJoin('category', 'products.cat_id', '=', 'category.id')	
								->leftJoin('product_inventories', 'products.id', '=', 'product_inventories.product_id')	
								->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')	
								->whereNotNull('product_inventories.product_id')->where('products.status','1')->where('products.store_id',$decoded['store_id'])
								->where('products.cat_id',$datas->cat_id)
								->orderBy("products.id","ASC");
									
									//$product_names = explode(",",$decoded['product_names']);
									$product_data->where(function ($query) use ($product_names) {
									foreach ($product_names as $field){
											if (!empty($field)){
													$query->orWhere('products.name','LIKE','%'.$field.'%');
											}
										}
									});	
						$product_data=$product_data->get();
						//echo '<pre>'; print_r($product_data); 
						//$search_detail_data['product_count']= $product_data->count();
						//$search_detail_data['category']['name']=$datas->category_name;
						foreach($product_data as $data)
						{
							$cart_is="no";
							$cart_qty='0';
							if(isset($decoded['user_id']) && $decoded['user_id']!="")
							{
								$itemcheckcart = Cartitem::where('user_id',$decoded['user_id'])
								->where('product_id',$data->id)->first();
								
								if(	$itemcheckcart )
								{
									$cart_is='yes';
									$cart_qty=$itemcheckcart->qty;
								}
							}
					        
							if(!empty($decoded['user_id']))
								{   
									$ProductFaviourite = DB::table('favorites')->where('user_id',$decoded['user_id'])->where('product_id',$data->id)->pluck('product_id')->all();
								}
							$search_product_data[] =  array('id' =>$data->id,'product_name' =>$data->product_name,'category_id' =>$data->cat_id,'category_name' =>$data->category_name,'image'=>URL::to('/media/products').'/'.$data->image,'store_name'=>$data->store_name
							,'orignal_price' =>$data->price,'discount_price' =>$data->discount_price,'offer'=>round((($data->price - $data->discount_price)*100) /$data->price).'%','cart_is'=>$cart_is,'cart_qty'=>$cart_qty,'is_faviourte' => !empty($ProductFaviourite) ? 1 : 0,'stock_status'=>$data->stock);
							
							 //$search_detail_data['category']['products']=$search_product_data;	
							}
							
							$home_store_data[] =  array('cat_id' =>$datas->cat_id,'cat_name' =>!empty ($datas->category_name) ? $datas->category_name:'','products'=>$search_product_data);
							$search_product_data = [];
						
						}	
						$search_detail_data=$home_store_data;
							$status = '1';
							$message='Products listed below.';	
					}else {
						$search_detail_data=$home_store_data;
						$message = 'No product data found.';
					}
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message,'data'=>$search_detail_data);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function changepassword(Request $request) 
	{  //echo $header = $request->header('asd'); die;
		 $user_id =  $request->input('user_id');
		 $status = 0;
		 $auth_token = $request->header('auth'); 
		 if(!empty($auth_token)){
			 $users = DB::table('users')->where('auth_token',$auth_token)->where('id',$user_id)->first();
		if(!empty($users)){
		// echo json_encode(array('user_id' => $user_id),JSON_UNESCAPED_SLASHES);
		// exit;
		$password =  $request->input('password');
		$old_password =  $request->input('old_password');
		//$password_confirmation =  $request->input('password_confirmation');
		
		$validator = Validator::make($request->all(), [
			'old_password' => 'required',
			'password' => 'required|min:6|max:16',
			//'password_confirmation' => 'required|min:6|max:16',
			 ]);
		if ($validator->fails()) 
		{
			$response_data = array('status'=>0,'errors'=>$validator->errors());
		}else
		{
			$userArray =  User::where('id',$user_id)->first();
			if(empty($userArray))
			{
				$response_data = array('status'=>0,'message'=>'Invalid User!');
			}else
			{      
				if(!Hash::check($old_password, $userArray->password)){    
		               $response_data = array('status'=>0,'message'=>'Your old password is incorrect.');
				} else {
					$password =  $request->input('password');
					$userArray->password = Hash::make($password);
					$userArray->save();

					//$message = "Password change Sucessfully.";
					//$status = 1
					
					$response_data = array('status'=>1,'message'=>'Password change Sucessfully.');
				//} else{
				//	
				//}
			}
			}
		}
		}else{
							//$status = -1;
							//$message = "Session expired.";
							$response_data = array('status'=> -1,'message'=>'Session expired.');
					   }	
		}else {
					//$message = 'Auth token missing.';
					$response_data = array('status'=> 0,'message'=>'Auth token missing.');
				}
				
		echo json_encode(array('response' => $response_data),JSON_UNESCAPED_SLASHES);
		die;
	}
	
	public function send_notification($tokenList,$title,$message,$extraNotificationData) 
	{   $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
		$notification = [
					'title' => $title,
					'body' => $message,
					//'icon' => $imageUrl,
					'sound' => 'mySound',
				];
				//echo '<pre>'; print_r($userstoken); die;
				//$extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

				$fcmNotification = [
					'registration_ids' => $tokenList, //multple token array
					//'to'        => $token, //single token
					'notification' => $notification,
					'data' => $extraNotificationData
				];
		
				$headers = ['Authorization:key=AAAA_b1huEM:APA91bGZ5mpD9dCcfVNQ7U-TKOQtIbUvHdgQeTvP6RZFhTqFw2mXlZif0rTtcelhGMdDydp_jHqX1frW49zgSwDOJxwQByLHrO2KRLwkwb7HpkXOxe6wF2qEL1JaT1oQMvllVNy34bAO',
					'Content-Type: application/json'
				];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$fcmUrl);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
				$result = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($result, true);
				
				if($result['success']){
					
					return 1;
					
					// echo json_encode(array('class'=>'success','message'=>'Push Notification Send Successfully.'));die;
				}else{
					return 0;
					//\Session::flash('error', 'Push Notification Not Send Successfully, '.$result['results'][0]['error'].' ,Please try again.');
					//echo json_encode(array('class'=>'success','message'=>'Push Notification Not Send Successfully'));die;
				}
	}
	function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) { 
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
		
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex"; 
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
		$vertices_count = count($vertices);
		
		/* foreach($vertices as $aas){
			$vertices_x[] = $aas['x'];
			$vertices_y[] = $aas['y'];
		}
		 $vertices_count = count($vertices_x) - 1;
		//pr( $point) ; die;
		$longitude_x =  $point['x'];
		$latitude_y = $point['y'];
		
		 $i = $j = $c = 0;
		  for ($i = 0, $j = $vertices_count ; $i < $vertices_count; $j = $i++) { 
			 if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) ) {
			   $c = !$c; 
			    break; 
			   }
		  }
		  return $c; */
         
        for ($i=1; $i < $vertices_count; $i++) {  
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) { 
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
		
        if ($intersections % 2 != 0) {
            return 1;
        } else {
            return 0;
        } 
    }
 
    function pointOnVertex($point, $vertices) { 
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }
 
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
}