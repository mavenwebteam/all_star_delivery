<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Validator;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Auth;
/**
 * Class NotificationController
 * @package App\Http\Controllers\Api
 * @version May 18, 2021, 4:00 pm IST
*/

class NotificationController extends BaseController
{
    /**
     * Notification listing
     * @return \Illuminate\Http\Response
     */
    public function notificationList(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $userId = Auth::id();
            $notification = Notification::where('user_id',$userId)->latest()->get();

            $notification = NotificationResource::collection($notification);
            $data = [
                'notificatin' => $notification
            ];              
            return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Mark notification readed
     * @return \Illuminate\Http\Response
     */
    public function notificationReaded(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:notification,id'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }

            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $notification = Notification::find($request->id);
            $notification->is_read = 1;
            $notification->save();
            return $this->sendResponse([], trans('message.NOTIFICATION_READ'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /* Clear notification
     * @return \Illuminate\Http\Response
     */
    public function notificationClear(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $notification = Notification::where('user_id', $request->user_id)->delete();
            return $this->sendResponse([], trans('message.DELETED_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }   
    
     /* Notifican on/off
     * @return \Illuminate\Http\Response
     */
    public function notificationOnOff(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'notify' => 'required|boolean'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $user = Auth::user();
            $user->is_notification = $request->notify ? "1" : "0";
            $user->save();
            $msg = $request->notify ? 'NOTIFICATION_ON' : 'NOTIFICATION_OFF';
            return $this->sendResponse([], trans('message.'.$msg));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
