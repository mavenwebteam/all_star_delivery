<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Requests\api\AuthRequest;
use App\Http\Requests\api\RegistrationRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constant;
use Illuminate\Support\Str;
use App\Http\Requests\api\UpdateProfileRequest;
use Image;
use App\Http\Controllers\Controller;


/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 * @version January 23, 2020, 1:00 pm IST
*/

class CustomerAuthController extends BaseController
{
    
    /**
     * Login
     * @ @param int $mobile 
     * @ @param int $role_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function login(AuthRequest $request)
    {
             
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            // ---check user status is active or not---------
            $checkUser = User::where('role_id','1')
            ->where('mobile', request('mobile'))
            ->where('is_deleted','0')
            ->first();
            if($checkUser)
            {
                if($checkUser->status !== 1){
                    return $this->sendError(trans('message.ACCOUNT_DEACTIVATED'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                    die;
                }
            }



            $otp = Helper::__generateNumericOTP(4);

            $arr = [
                'device_type'  => request('device_type'),
                'device_token' => request('device_token'),
                'otp'          => $otp,
                'uu_id'        => (string) Str::uuid(),
                'status'       => 1
                ];
            $user = User::updateOrCreate(
                ['mobile' => request('mobile'),'role_id'=>1],$arr
            );
            $user->tokens->each(function($token, $key) {
                $token->delete();
            });
            $token = $user->createToken(Constant::PASSPORT_TOKEN)->accessToken;
            $user = User::find($user->id);
            $user->token = $token;
            if($user->status != '1')
            {
                return $this->sendError(trans('message.ACCOUNT_MESSAGE_DEACITVE'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
            $message = "Your otp is: ".$otp;
            Helper::__sendOtp($request->input('mobile') , $message);
            return $this->sendResponse( new CustomerResource($user), trans('message.OTP_SEND_SUCCESS'));
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
            
            foreach($tokens as $token) {
                $token->revoke();   
            }
            return $this->sendResponse([],trans('message.LOGOUT'));
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
                'mobile'  => 'required|max:14|exists:users,mobile',
                'otp'   =>  'required'
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }
            $user = Auth::user();
            $user->token = $request->bearerToken();
         
            if($user->otp === request('otp'))
            {
                User::where('id', $user->id)->update(['otp' => NULL, 'is_mobile_verify' => 1]); 
                return $this->sendResponse( new CustomerResource($user), trans('message.GET_DATA'));
            }else{
                return $this->sendError(trans('message.OTP_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * resend otp
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
                'mobile' => 'required|max:14|exists:users,mobile',
            ]);
    
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }
            $user = User::where('mobile','=', request('mobile'))
                ->where('role_id','=', '1')   //role_id = 1 for customer
                ->first();
            if($user->status != 1)
            {
                return $this->sendError(trans('message.ACCOUNT_MESSAGE_DEACITVE'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
            $user->token = $request->bearerToken();
            $message = "Your otp is: ".$user->otp;
            $otp = Helper::__sendOtp($request->input('mobile') , $message);
            return $this->sendResponse( new CustomerResource($user), trans('message.OTP_SEND_SUCCESS'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update login user profile details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try{
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $userId = Auth::id();
            $user = User::find($userId);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            
            $image = $request->file('profile');
			if(isset($image))
			{
                // --delete existing image if exist---
                if($user->profile_pic != '')
				{
                    $obj = new Controller;
					$image_path = public_path('media/users/thumb/'.$user->profile_pic);
					$obj->deleteFile($image_path);
					$image_path = public_path('media/users/'.$user->profile_pic);
					$obj->deleteFile($image_path);
				}

                $imageName = time().rand(0,999).'.'.$image->getClientOriginalExtension();
				$imageName = str_replace(" ", "", $imageName);
				$image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/users/thumb/' . $imageName));
				$image->move(public_path('/media/users'), $imageName);
				$user->profile_pic = $imageName;
            }
            $user->save();
            $user->token = $request->bearerToken();
            $result = new CustomerResource($user);
            return $this->sendResponse($result, trans('message.PROFILE_UPDATED'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
