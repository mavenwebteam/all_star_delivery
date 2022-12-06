<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Chat;
use App\Models\Order;
use App\User;
use App\Http\Requests\api\ChatRequest;
use App\Http\Requests\api\ChatMsgListRequest;
use App\Http\Resources\ChatResource;
use Auth;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;

class ChatController extends BaseController
{
    /**
     * Store chat in DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function chatStore(ChatRequest $request){
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            // check order status
            $checkOrder = Order::select('status')->where('id', $request->order_id)->first();
            if($checkOrder->status == 'DELIVERED' || $checkOrder->status == 'CANCELLED')
            {
                return $this->sendError(trans('message.CHAT_ORDER_STATUS', ['status'=> $checkOrder->status]), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $senderId = Auth::user()->id;
                
            $chat = new Chat();
            $chat->sender_id = $senderId;
            $chat->receiver_id = $request->receiver_id;
            $chat->order_id = $request->order_id;
            $chat->message = base64_encode($request->message);
            $chat->created_at = date('Y-m-d H:i:s');
            $chat->save();
            if($chat->save()){
                // ---------send notification to receive--------------------
                self::sendMsgNotification($request->receiver_id, $request->order_id, $request->message);
                $data = [
                    'id' => (int) $chat->id,
                    'message' => (string) $request->message,
                    'sender_id' => (int) $senderId,
                    'order_id'  => (int) $request->order_id,
                    'receiver_id' => (int) $request->receiver_id
                ];
                return $this->sendResponse($data, trans('message.GET_DATA'));
            }
            return $this->sendError("Something went wrong!", JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    /**
     * get list of message
     *
     * @return \Illuminate\Http\Response
     */
    public function getChatMsgList(ChatMsgListRequest $request){
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $checkOrder = Order::select('status')->where('id', $request->order_id)->first();
            if($checkOrder->status == 'DELIVERED' || $checkOrder->status == 'CANCELLED')
            {
                return $this->sendError(trans('message.CHAT_ORDER_STATUS', ['status'=> $checkOrder->status]), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $userId = Auth::id();
            $chat = Chat::where('order_id',$request->order_id)->where(function($query) use ($request,$userId){
                $query->where('sender_id',$userId)
                        ->orWhere('receiver_id',$userId);
            })->orderBy('id')->get();
            $data = ChatResource::collection($chat);
            return $this->sendResponse($data, trans('message.GET_DATA'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * send notification message
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMsgNotification($userId, $orderId, $message)
    {
        $user = User::select('id', 'device_token')->find($userId);
        if(!empty($user->device_token)){
            $ids = [$user->id];
            $tokens = [$user->device_token];
            $title = trans('message.NEW_MESSAGE');
            $msg = $message;
            $url = '';
            parent::sendPushNotification($ids, $tokens, $title, $msg, $url, $orderId);
        }
    }
}
   
