<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\AddressController;
use App\Models\Order;
use App\Models\Stores;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\DriverOrder;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\DeliveryFee;
use App\Models\Settings;
use App\Models\Promocode;
use App\User;
use App\Http\Requests\api\CheckoutRequest;
use App\Http\Requests\api\PlaceOrderRequest;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\AddressResource;
use Validator;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Jobs\PushNotification;
use App\Jobs\FindDriver;
use Illuminate\Support\Facades\DB;
use App\Constants\Constant;
use Log;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api
 * @version Jun 16, 2021, 12:00 am IST
*/

class OrderController extends BaseController
{

    /**
     * create new order in DB
     * * @param checkoutRequest $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(CheckoutRequest $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            
            //---check cart empty----
            $cart = Cart::where('user_id',$request->user_id)->get();
            if($cart->count() <= 0) {
                return $this->sendError(trans('message.CART_NOT_FOUND'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }

            // ----------get store details--------
            $store_id = $cart[0]->store_id;
            $getStore = Stores::find($store_id);
            $store = new StoreResource($getStore);

            // -------get distance----------------
            $distance = 0;
            if($request->address_id){
                $address = Address::find($request->address_id);
                $distance = parent::calculateDistance($address->latitude,$address->longitude, $store_id);
            }else{
                if(empty($request->latitude) || empty($request->longitude)){
                    return $this->sendError(trans('Address or latitude, longitude required'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                }
                $customer = User::find($getStore->user_id);
                $customer->latitude = $request->latitude;
                $customer->longitude = $request->longitude;
                $customer->save();
                $distance = parent::calculateDistance($request->latitude,$request->longitude, $store_id);
            }
            /**
             * Return if delivery radius is greater than delivery_max_radius
            */
            $adminSetting = Settings::first();
            if($distance > $adminSetting->delivery_max_radius){
                return $this->sendError(trans('message.DELIVERY_RADIUS_EXCEED'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }

            // ---------get delivery charges with tax------
            $adminSettings = Settings::first();
            $tax = object_get($adminSettings,'tax', 0);
            $deliveryCharge = self::getDeliveryCharges($distance, 'CUSTOMER',  $tax);
           
            // ----update cart product with latest price-------
            foreach($cart as $value)
            {
                $price = parent::getLatestPrice($value->product_id);
                Cart::where('id',$value->id)
                ->update([
                    'price'=>$price,
                    'amount'=> $price * $value->qty,
                    ]);
            }
            $cartWithProduct = Cart::with('product')->where('user_id',$request->user_id)->get();
            $cartAmount = Cart::where('user_id', $request->user_id)->sum('amount');

            // ----variable declarations-------
            $promocodeStatus = false;
            $discountedAmount = NULL;
            $discountPresent = '';
            $offAmount = '';
            $promocodeMsg = '';
            $isPromocode = false;

            /**
             * Apply promocode
            **/ 
            $promocodeResult = array();
            if(!empty($request->promocode_id))
            {
                $promocodeResult = self::checkPromocode($request->promocode_id, $cartAmount, $request->user_id, $getStore->business_category_id);
                $isPromocode = true;
                $promocodeStatus = $promocodeResult['status'];
                $discountedAmount = $promocodeResult['discounted_amount'];
                $discountPresent = $promocodeResult['discount_present'];
                $promocodeMsg = $promocodeResult['message'];
                $offAmount = $promocodeResult['off_amount'];
            }
            $products = CheckoutResource::collection($cartWithProduct);
            
            /**
             * Calculate payable amount
             *  */ 
            $payableAmount = empty($discountedAmount) ? $cartAmount : $discountedAmount;
            $payableAmount = number_format($payableAmount,2, '.', '') + number_format($deliveryCharge,2, '.', '');

            $data = [
                'product'          => $products,
                'cart_amount'      => $cartAmount,
                'delivery_charge'  => $deliveryCharge,
                'tax'              => $tax,
                'is_promocode'     => $isPromocode,
                'is_promocode_applied' => $promocodeStatus,
                'promocode_id' => Arr::get($promocodeResult, 'promocode_id', ''),
                'promocode_message' => $promocodeMsg,
                'discounted_amount'=> $discountedAmount,
                'discount_present' => $discountPresent,
                'off_amount' => $offAmount,
                'payable_amount' => number_format($payableAmount, 2, '.', ''),
                'store' => $store,
                'distance' => number_format($distance, 2, '.', '')
            ];
            return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Calculate delivery fee for Driver|Customer
     * @param distance in km
     * @param feeFor CUSTOMER|DRIVER
     * @return deliveryCharge
     */
    public function getDeliveryCharges($distance, $feeFor, $tax=NULL)
    {
        $deliveryCharge = 0;
        $deliveryFee = DeliveryFee::where('fee_for',$feeFor)->first();

        if($deliveryFee->max_distance >=  $distance)
        {
            $deliveryCharge = $deliveryFee->fee;
        }else{
            $remainingDistance = $distance - $deliveryFee->max_distance;
            $deliveryCharge = $deliveryFee->fee + ($deliveryFee->delivery_fee_per_km * $remainingDistance);
        }
        if(!empty($tax)){
           $deliveryCharge = $deliveryCharge + (($deliveryCharge*$tax)/100);
        }
        
        return number_format($deliveryCharge, 2, '.', '');
    }
    

    /**
     * Check promocode
     * @param promocode
     * @param orderAmount cart total amount
     * @return 
     */
    public function checkPromocode($promocodeId, $orderAmount, $userId, $businessCategoryId)
    {   
        $data = [
            'status' => false,
            'discount_present' => '',
            'order_amount' => '',
            'off_amount' => '',
            'discounted_amount' => '',
            'promocode_id' => '',
            'message' => trans('message.INVALIED_PROMOCODE')
        ];
        $todayDate = Carbon::now()->format('Y-m-d');        

        $promocode = Promocode::where('id', $promocodeId)
                    ->whereNull('deleted_at')
                    ->where('status','1')
                    ->where('business_category_id',$businessCategoryId)
                    ->whereDate('start_date', '<=', $todayDate)
                    ->whereDate('end_date', '>=', $todayDate)
                    ->first();
        if(!$promocode){
            $data['message'] = trans('message.INVALIED_PROMOCODE');
            return $data;
            exit();
        }else{
            $orders = Order::where('user_id', $userId)
            ->where('promocode_id',$promocode->id)
            ->count();

            /**
             * Check How many time use by same user limit
            */
            if($orders >= $promocode->no_of_times_for_same_user){
                $data['message'] = trans('message.PROMOCODE_ALREADY_USED');
                return $data;
                exit();
            }
            /**
             * Check total number of times will use for all users limit
            */
            $allUserOrders = Order::where('promocode_id',$promocode->id)->count();
            if($allUserOrders >= $promocode->total_no_of_times_use){
                $data['message'] = trans('message.INVALIED_PROMOCODE');
                return $data;
                exit();
            }

            /**
             * Check How many time use by same user in one day limit
            */
            $todayTimestamp = Carbon::now()->format('Y-m-d H:i:s');   
            $sameDayOrdersForThisUser = Order::where('user_id', $userId)
            ->where('promocode_id',$promocode->id)
            ->whereDate('created_at', $todayTimestamp)
            ->count();
           
            if($sameDayOrdersForThisUser >= $promocode->no_of_times_in_each_day){
                $data['message'] = trans('message.PROMOCODE_USED_FOR_TODAY');
                return $data;
                exit();
            }
            $offAmount = ($orderAmount * $promocode->discount_present)/100;
            /**
             * check cap limit of offer
            */
            if($offAmount > $promocode->cap_limit){
                 $discounted_amount = $orderAmount - $promocode->cap_limit;
                  $offAmount = $promocode->cap_limit;
            }else{
                $discounted_amount = $orderAmount - $offAmount;
            }
            
            if($orderAmount < 0) {
                $discounted_amount = 0;
            }
            $data['status'] = true;
            $data['discount_present'] = $promocode->discount_present;
            $data['order_amount'] = $orderAmount;
            $data['discounted_amount'] = number_format($discounted_amount,2, '.', '');
            $data['off_amount'] = number_format($offAmount,2, '.', '');
            $data['promocode_id'] = $promocode->id;
            $data['message'] = trans('message.PROMOCODE_APPLIED_SUCCESSFULLY');
            return $data;
        }
    }

     /**
     * check min. order amount should be grater or equal as per admin setting
     * @param orderAmount custoer payable amount
     * @return Boolean
    */
    public function checkMinimumOrderAmount($orderAmount) : array
    {
        $result = array('is_check'=>false, 'amount' => '');
        $adminSetting = Settings::first();
        if(!empty($adminSetting->min_order_amount_for_delivery)){
          $result['is_check'] = ($adminSetting->min_order_amount_for_delivery <= $orderAmount) ? false : true; 
          $result['amount'] = $adminSetting->min_order_amount_for_delivery;
        }
        return $result;
    }


    /**
     * Place order
     * @param PlaceOrderRequest
     * @return Http/Response
    */
    public function placeOrder(PlaceOrderRequest $request)
    {
        if(Helper::apiKeyMatch($request->header('x-api-key')))
        {
            return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            die;
        }
        // ---check min order amount ------
        $minOrderAmount = self::checkMinimumOrderAmount($request->customer_payable_amount);
        if($minOrderAmount['is_check']){
            return $this->sendError('Order amount must be greater than or equal '.$minOrderAmount['amount'] .' Ks',JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            die;
        }

        // -------check cart is exist for this customer--------
        $cartItems = Cart::where('user_id', $request->user_id)->get();
        if($cartItems->count() <= 0){
            return $this->sendError('Your cart is empty!' ,JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            die;
        }


        $data = [
            'order_id' => '',
            'user_id' => $request->user_id,
            'address_id' => $request->address_id,
            'store_id' => $request->store_id,
            'promocode_id' => object_get($request,'promocode_id',NULL),
            'payment_mode' => $request->payment_mode,
            'instructions' => object_get($request,'instructions',NULL),
            'delivery_fee' => $request->delivery_fee,
            'tax' => $request->tax,
            'amount' => $request->cart_amount,
            'discounted_amount' => object_get($request,'discounted_amount',NULL),
            'grand_total' => $request->customer_payable_amount,
            'status' 	    => 'ORDERED',
            'status_remark' => 'Order placed by customer,',
        ];


        // ----payment gateway work-------
        // if($request->payment_mode !== 'COD'){

        // }

        $customerPayableAmount = $request->customer_payable_amount;
        $store = Stores::find($request->store_id);
      
        /**
         * storeShare = customerPayableAmount - (customerPayableAmount* adminComission(%)/100) - deliveryCharge
         *  */ 
        $storeShare = $customerPayableAmount - ($customerPayableAmount*$store->comission/100) - $request->delivery_fee;
        $data["amount_payable_to_store"] = $storeShare;
        
        //----------DB transection----------
        try {
            $order = DB::transaction(function () use($data, $cartItems, $request) {
                $isCreated = Order::create($data);
                $isCreated->order_id = $isCreated->get_order_number();
                $isCreated->save();
    
                // -------save order item------
                $orderItems = array();
                // ----delete cart items--------
                Cart::where('user_id', $request->user_id)->delete();
                foreach($cartItems as $item){
                    $orderItems[] = array(
                        'order_id' => $isCreated->id,
                        'product_id' => $item->product_id,
                        'qty' => $item->qty,
                        'price' => $item->price,
                    );
                }
                OrderItem::insert($orderItems);
                
                return $isCreated;
            });
        } catch (Exception $ex) {
           return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        $data = [
            'id' => $order->id,
            'order_id' => $order->order_id,
        ];
        return $this->sendResponse($data, trans('message.ORDER_PLACED_SUCCESS'));
    }

    /**
     * Get driver usign lat long under specific radius
     * @param latitude
     * @param longitude
     * @param radius in KM
     *  */ 
    public function getDriverByRadius($latitude, $longitude, $radius = 3, $orderId = NULL)
	{		
		$distance_query = '(6371* acos( cos( radians('.$latitude.') ) * cos( radians( users.latitude ) ) * cos( radians( users.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( users.latitude ) ) ) )';
	
		$drivers = User::select('users.*', DB::raw($distance_query.' AS distance'))
			->where('role_id','2') //role_id = 2 for driver 
			->where('status','1')
			->where('is_online','1')
			->where('is_driver_busy','0')
            ->whereNotNull('device_token')
            ->where('device_token', '!=', "")
			->where('is_deleted','0')
			->having("distance", "<", $radius)
			->get();
            $driverIdArr = array();
            $tokenIdArr = array();
            foreach($drivers as $driver) {
                $isExist = DriverOrder::where('order_id',$orderId)
                ->where('driver_id', $driver->id)->count();
                if($isExist == 0){
                    array_push($driverIdArr, $driver->id);
                    array_push($tokenIdArr, $driver->device_token);
                }
            }       
		$data = array('drivers'=>$driverIdArr, 'tokens' => $tokenIdArr);
        return $data;
	}


    /**
     *Find Driver after place ordert 
     * find driver for 3, 5 and 30KM (Radius range for all three cycle define in admin setting)
     */ 
    public function findDriver($orderId, $storeId, $radius)
    {
        $adminSetting = Helper::setting();
        if($radius <= $adminSetting->driver_range_3)
        {
            $order = Order::find($orderId);
            if($order && empty($order->driver_id) && $order->status !== 'CANCELLED')
            {
                //----get store----
                $store = Stores::find($storeId);
                $latitude = object_get($store, 'lat', NULL);
                $longitude = object_get($store, 'lng', NULL);
                
                /**
                 * if empty lat or long send notification to admin for assign driver manualy
                 */
                if(empty($latitude) ||empty($longitude)){
                    $token = User::where('id','1')->whereNotNull('device_token')->pluck('device_token')->all();
                    $title = "Driver not found for orderId:" . $orderId;
                    $body = "Assign driver manualy for order Id:" . $orderId;
                    Helper::notificationWeb($token, $title, $body);
                    die();
                }
                // -------find driver under radius------
                $tokens = NULL;
                $drivers = self::getDriverByRadius($latitude, $longitude, $radius, $orderId);
                $tokens = Arr::get($drivers, 'tokens', NULL);
                $drivers = Arr::get($drivers, 'drivers', []);
                if($tokens){
                    //----send notification to driver--- 
                    $title = trans('message.NEW_ORDER');
                    $message = trans('message.NEW_ORDER_REQUEST');
                    $url = '';
                    parent::sendPushNotification($drivers, $tokens, $title, $message, $url, $orderId);
                   
                    $driverOrderArr = array();
                    foreach($drivers as $driver)
                    {
                        $checkDriver = DriverOrder::where('order_id', $orderId)
                        ->where('driver_id', $driver)->count();
                        if($checkDriver==0){
                            DriverOrder::insert(['order_id' => $orderId,'driver_id' => $driver]);
                        }
                    }
                }
                /**
                 * dispatch job for 2 more cycle
                 * @param orderId
                 * @param storeId
                 * @param radius
                 * */ 
                if($radius == $adminSetting->driver_range_1){
                    $start = Carbon::now();
                    FindDriver::dispatch($orderId, $storeId, $adminSetting->driver_range_2)->delay($start->addSeconds(180));
                    FindDriver::dispatch($orderId, $storeId, $adminSetting->driver_range_3)->delay($start->addSeconds(120));
                    //--when this job run send notification to admin for manual assign driver
                    $bigRadius = $adminSetting->driver_range_3+5;
                    FindDriver::dispatch($orderId, $storeId, $bigRadius)->delay($start->addSeconds(120));
                }
                return true;
                die();
            }else{

                /**
                 * If order not found
                 * or order already assigned to a driver
                 * or order already cancelled
                 * */ 
                
                return true;
                die();
            }
        }else{
            // send FCM Notification to admin for assign driver manualy
            $order = Order::find($orderId);
            if(empty($order->driver_id)){
                $messageTitle = 'Driver not found for OrderId:'.$order->order_id;
                $messageBody = 'Driver not found for OrderId:'.$order->order_id .", need to assign driver manualy";
                $url = "";
                parent::sendWebNotification($orderId, $messageTitle, $messageBody, $url);
            }
            return true;
        }
    }
    
    /**
     * Get order History for specific user
     */
    public function orderHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'order_type' => 'required|in:CURRENT_ORDER,PAST_ORDER',
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
            $order = Order::with(['store', 'driver'])
            ->where('user_id',$request->user_id);
            if($request->order_type == 'CURRENT_ORDER'){
                $orders = $order->whereNotIn('status',['DELIVERED','CANCELLED'])->orderBy('created_at', 'DESC')->paginate(Constant::API_RECORD_PER_PAGE);
            }else{
                $orders = $order->whereIn('status',['DELIVERED','CANCELLED'])->orderBy('created_at', 'DESC')->paginate(Constant::API_RECORD_PER_PAGE);
            }

            $data = OrderResource::collection($orders);
            
            return $this->sendResponse($this->__paginate($data, $orders), trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Cancel Order
     */
    public function cancelOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'cancellation_reason' => 'required|max:199|min:5',
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

            $order = Order::find($request->order_id);
            if($order->status !== "ORDERED"){
                return $this->sendError(trans('message.ORDER_CANCEL_FAILED'));
            }else{
                $order->status = 'CANCELLED';
                $order->status_remark = $order->status_remark.' Order CANCELLED by customer,';
                $order->reason_of_cancel = $request->cancellation_reason;
                $order->delivery_boy_status = NULL;
                $order->save();
                return $this->sendResponse([], trans('message.ORDER_CANCEL_SUCCESS'));
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Only use for test the push notification in mobile app
     * */ 
    public function testNotification()
    {
        $tokens = User::where('id', '633')
        ->pluck('device_token')
        ->toArray();
        $type = 'order';
        $title = 'New Order';
        $message = 'You got a new order';
        $target = 111;
        Log::info('Log from controller');

        PushNotification::dispatch($tokens, $title, $message, $type, $target);
        return ["success"]; 
    }


    /**
     * Get order details as per specific order
     */
    public function orderDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
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
         
            $order = Order::with(['orderItems.product','address'])->where('id', $request->order_id)
            ->first();

            $orderResult = new OrderDetailsResource($order);
            return $this->sendResponse($orderResult, trans('message.GET_DATA'));
            
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
