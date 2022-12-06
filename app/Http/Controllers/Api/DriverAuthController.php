<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Requests\api\DriverLoginRequest;
use App\Http\Requests\api\DriverSignupRequest;
use App\Http\Requests\api\DriverProfileUpdateRequest;
use App\Http\Resources\DriverResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Vehicle;
use App\Models\Emailtemplates;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constant;
use Image, Mail, Config;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller as MainController;
use DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 * @version January 23, 2020, 1:00 pm IST
*/

class DriverAuthController extends BaseController
{
    
    /**
     * Signup
     * @ @param int $mobile 
     * @ @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(DriverSignupRequest $request)
    {   
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
    
            // dd($request->all(), $request->latitude, $request->longitude); 
            $data = [
                'uu_id'           => (string) Str::uuid(),
                'role_id'         => 2,
                'first_name'      => request('first_name'),
                'last_name'       => request('last_name'),
                'email'           => request('email'),
                'mobile'          => request('mobile'),
                'latitude'        => request('latitude') ?? NULL,
                'longitude'       => request('longitude') ?? NULL,
                'password'        => Hash::make(request('password'))
            ];

            $image = $request->file('profile_pic');
            if (isset($image)) {
                $imageName = time().'-'.$image->getClientOriginalName();
                $imageName = str_replace(" ", "", $imageName);
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/users/thumb/' . $imageName));
                $image->move(public_path() . '/media/users', $imageName);
                $data['profile_pic'] = $imageName;
            }

            $user = User::create($data);
            if($user)
            {
                $otp = Helper::__generateNumericOTP(4);
                $message = trans('message.OTP_MESSAGE') . $otp;
                $user->otp = $otp;
                $user->save();
                Helper::__sendOtp($request->input('mobile') , $message);
                $vehicleData = array();
                if(request('vehicle_type') == 'Motorbike')
                {
                    $vehicleData = [
                        'brand_name'      => request('brand_name'),
                        'year'            => request('year'),
                        'vehicle_num'     => request('vehicle_num'),
                        'licence_num'     => request('licence_num'),
                        'vehicle_type'    => request('vehicle_type'),
                        'model'           => request('model'),
                    ];

                    $vehicleImg = $request->file('vehicle_num_img');
                    if (isset($vehicleImg)) {
                        $imageName = time().'-'.$vehicleImg->getClientOriginalName();
                        $imageName = str_replace(" ", "", $imageName);
                        $vehicleImg->move(public_path() . '/media/vehicle', $imageName);
                        $vehicleData['vehicle_num_img'] = $imageName;
                    }
                    $licenceImg = $request->file('licence_img');
                    if (isset($licenceImg)) {
                        $imageName = time().'-'.$licenceImg->getClientOriginalName();
                        $imageName = str_replace(" ", "", $imageName);
                        $licenceImg->move(public_path() . '/media/vehicle', $imageName);
                        $vehicleData['licence_img'] = $imageName;
                    }
                    $vehicleData['user_id'] = $user->id;
                    Vehicle::create($vehicleData);
                }
                if(request('vehicle_type') == 'Bicycle')
                {
                    $vehicleData = [
                        'user_id'         => $user->id,
                        'vehicle_type'    => request('vehicle_type'),
                    ];
                    Vehicle::create($vehicleData);
                }
                // ==========send email varification mail========
                $emailData = Emailtemplates::where('slug','=','driver-signup-mail')->first();
                if($emailData){
                    $activate_url = \App::make('url')->to("account-activate/" . base64_encode($user->id));
                    $textMessage = $emailData->description;
                    $settingsEmail 		= Config::get("Site.email");
                    $full_name = $user->first_name;
                    $subject = $emailData->subject;
                    if($user->email!='')
                    {
                        $textMessage = str_replace(array('{USERNAME}','{{ACTIVE_URL}}'), array($user->first_name, $activate_url), $textMessage);
                        $mainObj = new MainController;
                        $mainObj->sendMail($user->email,$full_name,$subject,$textMessage,$settingsEmail);
                    }
                }
                // ==========send email varification mail end========





                return $this->sendResponse(['otp' => $otp,'user_id'=> $user->id], trans('message.ACCOUNT_CREATED_SUCCESS'));
            }else{
                return $this->sendError(trans('message.SOMETHING_WENT_WRONG'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Login
     * @ @param int $mobile or $email
     * @return \Illuminate\Http\Response
     */
    public function login(DriverLoginRequest $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            
            $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
			$user = User::select('email_verify','status','is_mobile_verify')
            ->where($fieldType, $request->input('email'))
            ->where('role_id', '2')  //for driver
            ->first();
            $userdata = array();
            $message = trans('message.EMAIL_INVALID');
            if($user)
			{
				if($user->status != 1)
				{
					return $this->sendError(trans('message.ACCOUNT_MESSAGE_DEACITVE'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
					die;
				}

				if($fieldType == 'email')
				{
                    $message = trans('message.INVALID_LOGIN_EMAIL');
					if($user->email_verify != 'yes'){
						return $this->sendError(trans('message.EMAIL_NOT_VERIFY'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
						die;
					}

					$userdata = array(
						$fieldType		=> $request->input('email'),
						'password' 		=> $request->input('password'),
						'is_admin' 		=> '0',
						'role_id'       =>  '2',
						'status' 	    => '1',
						'email_verify'  => 'yes',
					);
				}else{
                    $message = trans('message.INVALID_LOGIN_MOBILE');
					if($user->is_mobile_verify != 1)
					{
						return $this->sendError(trans('message.MOBILE_NOT_VERIFY_NEW'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
						die;
					}
					$userdata = array(
						$fieldType		=> $request->input('email'),
						'password' 		=> $request->input('password'),
						'is_admin' 		=> '0',
						'role_id'  		=> '2',
						'status' 	    => '1',
                        'is_mobile_verify' => '1'
					);
				}                                         
            }else{
                $message = trans('message.USER_NOT_FOUND');
                return $this->sendError($message, JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }

            if (Auth::attempt($userdata)){
                User::where('id','=',Auth::id())->update([
                    'device_type' => strtolower(request('device_type')),
                    'device_token'=> request('device_token'),
                    'is_online'   => '1',
                    'latitude'    => request('latitude') ?? NULL,
                    'longitude'   => request('longitude') ?? NULL,
                ]);
                
                $user = User::with('vehicle')->find(Auth::id());

                $user->tokens->each(function($token, $key) {
                    $token->delete();
                });
                $token = $user->createToken(Constant::PASSPORT_TOKEN)->accessToken;
                $user->token = $token;
                return $this->sendResponse( new DriverResource($user), trans('message.GET_DATA'));
            }else{ 
                return $this->sendError($message, JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    /**
     * otp verification
     * * @param int $request
     * @return \Illuminate\Http\Response
     */
    public function otpVerify(Request  $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'user_id'  => 'required|integer|exists:users,id',
                'otp'      =>  'required|max:4',
                'type'     =>   'required|in:signup,forget_password'
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }
            $user = User::find(request('user_id'));
         
            if($user->otp == request('otp'))
            {
                if(request('type') == 'signup')
                {
                    User::where('id', $user->id)->update(['otp' => NULL, 'is_mobile_verify' => 1]); 
                    return $this->sendResponse([], trans('message.DRIVER_SIGNUP_MESSAGE'));
                }
                if(request('type') == 'forget_password')
                {
                    User::where('id', $user->id)->update(['otp' => NULL, 'is_mobile_verify' => 1]);
                    return $this->sendResponse([], trans('message.OTP_MATCH')); 
                }
                
            }else{
                return $this->sendError(trans('message.OTP_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Login
     * @ @param int $mobile 
     * @ @param int $role_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|max:14|exists:users,id',
                'type'    => 'required|in:mobile,email'
            ]);
    
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }

            $user = User::find(request('user_id'));
            if(request('type') == 'mobile'){
                $message = trans('message.OTP_MESSAGE') . $user->otp;
                Helper::__sendOtp($request->input('mobile') , $message);
            }else{
            	
                $emailData = Emailtemplates::where('slug','=','otp-on-mail')->first();
                if($emailData){
                    $textMessage = $emailData->description;
                    $settingsEmail 		= Config::get("Site.email");
                    $full_name = $user->first_name;
                    $subject = $emailData->subject;
                    if($user->email!='')
                    {
                        $textMessage = str_replace(array('{USERNAME}','{OTP_NUMBER}'), array($user->first_name, $user->otp),$textMessage);
                        $this->sendMail($user->email,$full_name,$subject,$textMessage,$settingsEmail);
                    }
                }
            }
            return $this->sendResponse(['otp'=> $user->otp,'user_id'=>$user->id], trans('message.OTP_SEND_SUCCESS'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Forgot Password
     *
     * @return \Illuminate\Http\Response
    */
    public function forgotPassword(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'mobile_or_email' => 'required|max:50',
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }
                
            $fieldType = filter_var($request->mobile_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
            if($fieldType == 'mobile')
            {
                $user = User::where('role_id','2')->where('mobile',request('mobile_or_email'))->first();
                if($user){
                    $otp = Helper::__generateNumericOTP(4);
                    $user->otp = $otp;
                    $user->save();
                    $message = trans('message.OTP_MESSAGE') . $user->otp;
                    Helper::__sendOtp($request->input('mobile') , $message);
                    return $this->sendResponse(['otp' => $otp, 'user_id' => $user->id], trans('message.OTP_SEND_SUCCESS'));
                }else{
                    return $this->sendError(trans('message.ACCOUNT_NOT_EXIST'));
                }
            }else{
                $user = User::where('role_id','2')->where('email',request('mobile_or_email'))->first();
                
                if($user){
                    $otp = Helper::__generateNumericOTP(4);
                    $user->otp = $otp;
                    $user->save();
                    return $this->sendResponse(['otp' => $otp, 'user_id' => $user->id], trans('message.OTP_SEND_SUCCESS'));
                    /*
                    | send email otp
                    */

                }else{
                    return $this->sendError(trans('message.ACCOUNT_NOT_EXIST'), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Reset Password
     *
     * @return \Illuminate\Http\Response
    */
    public function resetPassword(Request  $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            
            $validator = Validator::make($request->all(), [
                'password' => 'required|max:50',
                'user_id'  => 'required|exists:users,id',
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }

            $user = User::find(request('user_id'));
            $user->password = Hash::make(request('password'));
            $user->save();
            return $this->sendResponse([], trans('message.PASSWORD_UPDATE'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logout
     * @ @param int $mobile
     * @ @param int $user_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $tokens = Auth::user()->tokens;
            $user = Auth::user();
            $user->device_token = NULL;
            $user->is_online = '0';
            $user->save();
            foreach($tokens as $token) {
                $token->revoke();   
            }
            return $this->sendResponse([],trans('message.LOGOUT'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /* Driver online/offline
     * @return \Illuminate\Http\Response
     */
    public function driverOnlineOffline(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'is_online' => 'required|boolean'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $user = Auth::user();
            $user->is_online = $request->is_online ? 1 : 0;
            $user->save();
            $msg = $request->is_online ? 'ONLINE' : 'OFFLINE';
            return $this->sendResponse([], trans('message.'.$msg));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update profile
     * @ @param int $mobile 
     * @ @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(DriverProfileUpdateRequest $request)
    {   
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $driverId = Auth::id();
            $driver = User::find($driverId);
            $data = [
                'first_name'      => request('first_name'),
                'last_name'       => request('last_name'),
                'role_id'       => 2,  // role_id 2 for driver
            ]; 

            // 'email'           => request('email') ? request('email') : $driver->email,
            // 'mobile'          => request('mobile'),

            $otp = '';
            $image = $request->file('profile_pic');
            if (isset($image)) {
                $imageName = time().'-'.$image->getClientOriginalName();
                $imageName = str_replace(" ", "", $imageName);
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/users/thumb/' . $imageName));
                $image->move(public_path() . '/media/users', $imageName);
                $data['profile_pic'] = $imageName;
            }
            if($request->mobile && ($driver->mobile != $request->mobile)){
                $otp = Helper::__generateNumericOTP(4);
                $message = trans('message.OTP_MESSAGE') . $otp;
                $data['otp'] = $otp;
                $data['is_mobile_verify'] = 0;
            }
            if($request->email && ($driver->email != $request->email)){
                $otp = Helper::__generateNumericOTP(4);
                $message = trans('message.OTP_MESSAGE') . $otp;
                $data['email'] = $request->email;
                $data['email_verify'] = "no";
            }

            $vehicleData = array();
            if(request('vehicle_type') == 'Motorbike')
            {
                $vehicleData = [
                    'brand_name'      => request('brand_name'),
                    'year'            => request('year'),
                    'vehicle_num'     => request('vehicle_num'),
                    'licence_num'     => request('licence_num'),
                    'vehicle_type'    => request('vehicle_type'),
                    'model'           => request('model'),
                ];

                $vehicleImg = $request->file('vehicle_num_img');
                if (isset($vehicleImg)) {
                    $imageName = time().'-'.$vehicleImg->getClientOriginalName();
                    $imageName = str_replace(" ", "", $imageName);
                    $vehicleImg->move(public_path() . '/media/vehicle', $imageName);
                    $vehicleData['vehicle_num_img'] = $imageName;
                }
                $licenceImg = $request->file('licence_img');
                if (isset($licenceImg)) {
                    $imageName = time().'-'.$licenceImg->getClientOriginalName();
                    $imageName = str_replace(" ", "", $imageName);
                    $licenceImg->move(public_path() . '/media/vehicle', $imageName);
                    $vehicleData['licence_img'] = $imageName;
                }
                
            }
            if(request('vehicle_type') == 'Bicycle')
            {
                $vehicleData = [
                    'vehicle_type'    => request('vehicle_type'),
                ];
            }

            // DB transection start
            try {
                DB::transaction(function () use($data, $vehicleData, $driverId) {
                    $driver = User::where('id', $driverId)->update($data);
                    $vehicleData['user_id'] = $driverId;
                    Vehicle::where('user_id', $driverId)->update($vehicleData);
                });
            } catch (Exception $ex) {
                return $this->sendError(trans('message.SOMETHING_WENT_WRONG'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
            // DB transection end


            // ==========send email varification mail========
            if($request->email && ($driver->email != $request->email)){
                $emailData = Emailtemplates::where('slug','=','driver-signup-mail')->first();
                if($emailData){
                    $activate_url = \App::make('url')->to("account-activate/" . base64_encode($driverId));
                    $textMessage = $emailData->description;
                    $settingsEmail 		= Config::get("Site.email");
                    $full_name = $request->first_name;
                    $subject = $emailData->subject;
                    if($request->email!='')
                    {
                        $textMessage = str_replace(array('{USERNAME}','{{ACTIVE_URL}}'), array($request->first_name, $activate_url), $textMessage);
                        $mainObj = new MainController;
                        $mainObj->sendMail($request->email,$full_name,$subject,$textMessage,$settingsEmail);
                    }
                }
            }
            // ==========send email varification mail end========
            // ==========send otp varification message end========
            if($request->mobile && ($driver->mobile != $request->mobile)){
                $message = trans('message.OTP_MESSAGE') . $otp;
                Helper::__sendOtp($request->mobile , $message);
            }
            $getDriver = User::with('vehicle')->find(Auth::id());
            $token = $request->bearerToken();
            $getDriver->token = $token;
            return $this->sendResponse( new DriverResource($getDriver), trans('message.GET_DATA'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }










   
}
