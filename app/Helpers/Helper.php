<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Constants\Constant;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Settings;
use App\Models\Stores;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Favorites;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Illuminate\Support\Facades\Log;

class Helper
{

     /**
     * @param $string
     * @return string ucfirst
     */
    public static function apiKeyMatch($key)
    {
        // try {
        //     if( $key === Crypt::decryptString(Constant::API_KEY))
        //     {
        //         return false;
        //     }else{
        //         return true;
        //     }

        // } catch(\RuntimeException $e) {
        //     return true;
        // }
        return false;   
    }


    /**
     * Get Settings
     */
    public static function setting()
    {
        $data = Settings::findOrFail(1);
        return $data;
    }

    /**
     * @param $string
     * @return string ucfirst
     */
    public static function mb_strtolower($string)
    {
        $encoding = 'utf8';
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    /**
     * @param date
     * @return Date
     */
    public static function date($date)
    {
        setlocale(LC_TIME, \App::getLocale() . '.utf8');
        return \Carbon\Carbon::parse($date)->formatLocalized('%d %B %Y');
    }

    public static function __failedValidation($msg){
        $response = [
            'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $msg
        ];
        throw new HttpResponseException(
            response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
      }

    /**
     * Send FCM Notification
     * @param $token
     * @param string $message notification mesg 
     * @param string $title notification title 
     * @param string $type module type like order, banner, store etc. 
     * @param int $target_id key of target like order_id, banner_id, store_id. 
     */
    public static  function notification($token,$title, $message, $type, $target_id){
    
        if(!is_array($token)){
         $token = [$token];
        }

        $server_key = Constant::FCM_KEY;
        $url = 'https://fcm.googleapis.com/fcm/send';

	    $data = [
				"title"=> Constant::APP_NAME,
				"body"=> $message,
				"icon" => '/logo.png',
                'image-url' => "ic_notification_smal",
				'sound' => 'mySound',
		];
        $date = date('Y-m-d H:i:s', time());
        $extraNotificationData = ["notification_type" => $type, "target_id" => $target_id, "date"=>$date];
        $fields = array (
                'notification' => $data,
                'data' => $extraNotificationData
        );

        if(count($token) > 1){
            $fields["registration_ids"] = $token; //for multiple users
        }else{
            $fields["to"] = $token[0];  //for single user
        }

	    $fields = json_encode ( $fields );
        $headers = array (
                'Authorization: key='.$server_key,
                'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        $result = curl_exec ( $ch );
        curl_close ( $ch );
        
        // Log::info('notification-log');
         Log::info($result);
        return true;
    }


    /**
     * Send FCM Notification in web
     * @param $token
     * @param $title of notification
     * @param $body of notification
     * @param $url optional
     */
    public static function notificationWeb($token, $title, $body, $type=NULL, $target_id=NULL){ 
        if(!is_array($token)){
            $token = [$token];
        }
        $SERVER_API_KEY = Constant::FCM_KEY;
        $extraNotificationData = ["notification_type" => $type, "target_id" => $target_id];
        $data = [
            "notification" => [
                "title" => $title,
                "body"  => $body, 
				"icon"  => '/logo.png',
				"url"   => "https://google.com/",
                'image-url' => "ic_notification_smal",
				'sound' => 'mySound',
                'priority'=> "high"
			],
            'data' => $extraNotificationData
        ];

        if(count($token) > 1){
            $data["registration_ids"] = $token; //for multiple users
        }else{
            $data["to"] = $token[0];  //for single user
        }

        $dataString = json_encode($data);
       
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
            "Urgency: high",
            "priority:10",
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
      	// dd($response);
    }


    /**
     * get image url
     * @param $name|string
     * @param $type|string
     */
    public static function getImageUrlUser($name,$path){

        if(File::exists($path.$name) && $name){
            return url($path.$name);
        }else{
            return url(Constant::NO_IMAGE_USER);
        }
    }

    public static function getImageUrl($name,$path){

        if(File::exists($path.$name) && $name){
            return url($path.$name);
        }else{
            return url(Constant::NO_IMAGE_DUMMY);
        }
    }


    /**
     * @param $number
     * @param $message
     */
    public static function __sendOtp($number,$message){
        // $accountSid = Constant::TWILIO_ACCOUNT_SID;
        // $authToken  = Constant::TWILIO_AUTH_TOKEN;
        // $client = new Client($accountSid, $authToken);
        // try{
        //     $client->messages->create(
        //         $number,
        //         array(
        //             'from' => Constant::TWILIO_FROM,
        //             'body' => $meassge
        //         )
        //     );
        //     return true;
        // }catch (\Exception $e){
        //     return true;
        // }
        return true;
    }

    /**
     * Function to generate OTP
     * @param int $n
     */
    public static function __generateNumericOTP($n) {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
        return $result;
    }


    /**
     * create random strings
     */
    public static function _random_strings($length_of_string){
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),0, $length_of_string);
    }

    public static function vendorHasActiveStore($vendorId) { 
        $store = Stores::where('user_id',$vendorId)->where('status','1')->count();
        if($store)
          return true;
        else
          return false;
    }

    public static function getDiscountPrice($mainPrice, $offerPresent) { 
        //actual price - (actual price * (discount / 100))
       return number_format($mainPrice-($mainPrice*$offerPresent/100),2, '.', '');
       
    }

    public static function cartItemForUser($userId, $productId) {
        $item = Cart::select('qty')
       ->where('user_id',$userId)
       ->where('product_id',$productId)  
       ->first();
       if($item)
       return $item->qty;
       else
       return 0;
    }

    public static function isStoreFavourite($userId, $storeId) {
        $item = Favorites::where('user_id',$userId)
       ->where('store_id',$storeId)  
       ->count();
       if($item)
       return 1;
       else
       return 0;
    }

    /**
     * Get store Id from login vendorId
     * return store Id
     * */ 

    public static function getStoreId() {
        $store = Stores::where('user_id', Auth::user()->id)->first();
        if($store) return $store->id;
        else return false;
    }

    /**
     * Get order items from specifiec orderId
     * */ 
    public static function getOrderItem($orderId) {
        $orderItems = OrderItem::with('product')->where('order_id',$orderId)
        ->get();
        $product = array();
        foreach($orderItems as $item){
            $productArr = array();
            $productArr['product_name'] = object_get($item, 'product.name_en', NULL);
            $productArr['product_qty']  = object_get($item, 'qty', NULL);
            
            if($productArr){
                array_push($product, $productArr);
            }
        }
        return $product;
    }
    
    /**
     * Formate the currency
     * */ 
    public static function currencyFormat($currency) { 
        return number_format($currency, "2", ".", ",") ." ks"; 
    }
}
