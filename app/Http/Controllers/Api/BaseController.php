<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Validator;
use App\User;
use App\Models\Product;
use App\Models\Stores;
use DB;
use App\Models\Notification;
use App\Jobs\PushNotification;
use App\Jobs\WebPushNotification;
use App\Helpers\Helper;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = JsonResponse::HTTP_OK)
    {
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $result,
        ];
        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error,$code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
    {
        $response = [
            'status' => $code,
            'message' => $error
        ];
        return response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

     /**
     * return __paginate response.
     *
     * @return \Illuminate\Http\Response
     */
    public function __paginate($resource, $data){
        return [
            'data'=>$resource,
            'pagination' => [
                'total'     => $data->lastPage(),  //total number of pages
                'count'     => $data->count(),
                'per_page'  => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_pages'   => $data->lastPage(),
                'previous_page_url' => $data->previousPageUrl(),
                'next_page_url'     => $data->nextPageUrl()
            ]
        ];
    }

    /**
     * Override validate method use validation exception
     *
     * @param Request $request
     * @param array $rules
     */
    public function makeValidator($request, array $rules){
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            
             $response = [
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $validator->errors()->first(),
                'data'=> []
            ];
            throw new HttpResponseException(
                response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }

    /**
     * check account exist.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountExistant($role_id, $email=NULL,$mobile=NULL)
    {
        if($email){
            $user = User::where('email',$email)
            ->where('role_id',$role_id)
            ->count();
            if($user){
                return true;
            }else{
                return false;
            }
        }
        if($mobile){
            $user = User::where('mobile',$mobile)
            ->where('role_id',$role_id)
            ->count();
            if($user){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * Get product latest price
     * @return discounted price if exist else main price
     * @param productId
     */
    public function getLatestPrice($productId)
    {
        $product = Product::find($productId);
        if(!empty($product->discounted_price)){
            return $product->discounted_price;
        }else{
            return $product->price;
        }
    }


    /**
     * Get distance between store and given cordinate
     * @return distance in km
     * @param latitude 
     * @param longitude
     * @param storeId
     *  */ 
    public function calculateDistance($latitude, $longitude, $storeId)
    {
        $distance_query='(6371* acos( cos( radians('.$latitude.') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( stores.lat ) ) ) )';
	
		$store = Stores::select(DB::raw($distance_query.' AS distance'))
        ->where('id',$storeId)
        ->first();
        return object_get($store, "distance", 0);
    }
    
    
    /**
     * Send push notification to mobile app
     * */ 
    public function sendPushNotification($ids, $tokens, $title, $msg, $url, $order_id)
    {
        
        $type = 'order';
        $data = array();
        foreach($ids as $id){
            $notificationSave = array(
                'type' => $type,
                'target_id' => $order_id,
                'title' => $title,
                'description' => $msg,
                'is_read' => 0,
                'user_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            array_push($data, $notificationSave);
        }
        Notification::insert($data);
        PushNotification::dispatch($tokens, $title, $msg, $type, $order_id);
        return true;
    }

    /**
     * send web push notification to admin
     * @param int $reciverId => user id which will get this notification
     */
    public function sendWebNotification($reciverId, $orderId, $msgTitle, $msgBody, $msgUrl = '')
    {
        $firebaseToken = User::where('id', $reciverId)->whereNotNull('device_token')->pluck('device_token')->all();
		$title = $msgTitle;
		$body = $msgBody;
        $url = $msgUrl;
        $notificationSave = array(
            'type' => 'order',
            'target_id' => $orderId,
            'title' => $title,
            'description' => $body,
            'is_read' => 0,
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        Notification::insert($notificationSave);
        if(!empty($firebaseToken)){
            WebPushNotification::dispatch($firebaseToken, $title, $body, 'order', $orderId);
        }
        return true;
    }
}
