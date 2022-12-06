<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\Stores;
use App\Models\Address;
use App\User;
use App\Models\DriverOrder;
use App\Http\Resources\DriveOrderRequestResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\DriverOrderDetailsResource;
use App\Http\Resources\StoreResource;
use App\Http\Requests\api\OrderStatusUpdateRequest;
use Validator, Auth;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\OrderController;
use App\Constants\Constant;
use Illuminate\Support\Facades\Log;
/**
 * Class DriverOrerController
 * @package App\Http\Controllers\Api
 * @version Jun 25, 2021, 10:00 am IST
*/

class DriverOrderController extends BaseController
{
    /**
     * Get drive order history
     * @return \Illuminate\Http\Response
     */
    public function orderHistory(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'order_type' => 'required|in:CURRENT_ORDER,PAST_ORDER',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $driverId = Auth::id();
            $orders = Order::where('driver_id',$driverId);
            if($request->order_type == 'CURRENT_ORDER'){
                $orders = $orders->whereNotIn('status',['DELIVERED','CANCELLED'])->latest('updated_at')->paginate(Constant::API_RECORD_PER_PAGE);
            }else{
                $orders = $orders->whereIn('status',['DELIVERED','CANCELLED'])->latest('updated_at')->paginate(Constant::API_RECORD_PER_PAGE);
            }
            $data = OrderResource::collection($orders);           
            return $this->sendResponse($this->__paginate($data, $orders), trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get drive new order request
     * @return \Illuminate\Http\Response
     */
    public function newOrderRequest(Request $request){
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $driverId = Auth::id();
            $data = DriverOrder::with('order', 'order.store')
            ->where('driver_id', $driverId)
            ->where('is_rejected', '0')
            ->get();
            $order = DriveOrderRequestResource::collection($data);
            return $this->sendResponse($order, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Action(Accept/Reject) order by driver
     * @return \Illuminate\Http\Response
     */
    public function acceptOrRejectOrder(Request $request){
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'driver_id' => 'required|exists:users,id',
                'order_id' => 'required|exists:orders,id',
                'order_action' => 'required|in:accept,reject',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $driverId = $request->driver_id;
            $driverRunningOrder = Order::where('driver_id', $driverId)
            ->whereNotNull('delivery_boy_status')
            ->whereNotIn('status', ['CANCELLED', 'DELIVERED'])
            ->where('delivery_boy_status','!=' ,'DELIVERED')
            ->count();
            
            if($driverRunningOrder){
                return $this->sendError(trans('message.DRIVER_HAVE_ORDER'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            if($request->order_action == 'accept')
            {
                $order = Order::find($request->order_id);
                if($order->driver_id == null || $order->driver_id == '') {
                    $order->driver_id = $request->driver_id;
                    $order->delivery_boy_status = 'ASSIGNED';
                    // mark driver as busy
                    $driver = User::find($request->driver_id);
                    $driver->is_driver_busy = 1;
                    $driver->save();

                    DriverOrder::where('order_id', $request->order_id)
                    ->where('driver_id', $request->driver_id)
                    ->update(['is_rejected'=>0]);
                    // get customer lat long
                    if(!empty($order->address_id)){
                        $address = Address::find($order->address_id);
                    }else{
                        $address = User::find($order->user_id);
                    }
                    // ---delete selected driver for this order------
                    //DriverOrder::where('order_id', $order->id)->delete();


                    /**
                     * delivery fee caculation
                     * */ 
                    $distance = parent::calculateDistance($address->latitude,$address->longitude, $order->store_id);
                    $obj = new OrderController;
                    $amountPayableToDriver = number_format($obj->getDeliveryCharges($distance, "DRIVER"), 2, '.', ''); 
                    $order->amount_payable_to_driver = $amountPayableToDriver;
                    

                    /**
                     * adminShare = customerPayableAmount - storeShare - amountPayableToDriver
                     *  */
                    $customerPayableAmount = number_format(object_get($order,'grand_total', 0), 2, '.', '');
                    $amountPayableToStore = number_format(object_get($order,'amount_payable_to_store', 0), 2, '.', '');
                    $adminShare = $customerPayableAmount - $amountPayableToStore -  $amountPayableToDriver;

                    $order->admin_commission_amount = number_format($adminShare, 2, '.', '');
                    
                    $order->save();

                    $data = DriverOrder::with('order', 'order.store')
                    ->where('driver_id', $driverId)
                    
                    ->get();
                    $order = DriveOrderRequestResource::collection($data);
                    return $this->sendResponse($order, trans('message.ORDER_ACCEPTED'));
                }else{
                    return $this->sendResponse([], trans('message.ORDER_ALREADY_ACCEPTED'));
                }
                
            } else{
                // ---action is "reject"------
                DriverOrder::where('order_id', $request->order_id)
                ->where('driver_id', $request->driver_id)
                ->update(['is_rejected'=>1]);
                return $this->sendResponse([], trans('message.SUCCESS'));
            }
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Driver order status update
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(OrderStatusUpdateRequest $request){
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            switch ($request->order_status) {
                case 'ARRIVED_AT_STORE':
                    $order = Order::find($request->order_id);
                    if($order->status == "ACCEPTED" || $order->status == "READY_TO_SHIP"){
                        $order->delivery_boy_status = "ARRIVED_AT_STORE";
                        $order->save();
                        //send notification to customer
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.DRIVER_ARRIVED_AT_STORE');
                        self::sendNotificationCall($order->user_id, $request->order_id, $title, $message);
                        return $this->sendResponse([], trans('message.SUCCESS'));
                    } else{
                        // return if order not acceptable
                        $msg = trans('message.ORDER_STATUS_MSG', ['Status' => $order->status]);
                        return $this->sendError($msg, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    break;
                case 'PICKEDUP':
                    $order = Order::find($request->order_id);
                    if($order->status == "READY_TO_SHIP"){
                        $order->delivery_boy_status = "PICKEDUP";
                        $order->status = "ON_THE_WAY";
                        if(!empty($request->delivery_estimate_time)){
                            $order->delivery_estimate_time = $request->delivery_estimate_time;
                        }

                        $order->save();
                        //send notification to customer
                        
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_PICKEDUP');
                        self::sendNotificationCall($order->user_id, $request->order_id, $title, $message);
                        return $this->sendResponse([], trans('message.SUCCESS'));
                    }else{
                        $msg = trans('message.ORDER_NEED_TO_READY');
                        return $this->sendError($msg, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    break;
                case 'ARRIVED_AT_DELIVERY_LOCATION':
                    $order = Order::find($request->order_id);
                    if($order->status == "ON_THE_WAY"){
                        $order->delivery_boy_status = "ARRIVED_AT_DELIVERY_LOCATION";
                        $order->save();
                        //send notification to customer
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_ARRIVED');
                        self::sendNotificationCall($order->user_id, $request->order_id, $title, $message);
                        return $this->sendResponse([], trans('message.SUCCESS'));
                    }else{
                        $msg = trans('message.ORDER_NEED_TO_PICKUP');
                        return $this->sendError($msg, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    break;
                case 'DELIVERED':
                    $order = Order::find($request->order_id);
                    $store_id = $order->store_id;
                    if($order->delivery_boy_status == "ARRIVED_AT_DELIVERY_LOCATION"){
                        $order->delivery_boy_status = "DELIVERED";
                        $order->status = "DELIVERED";
                        $order->save();
                        // mark driver available for new order
                        User::where('id', $request->driver_id)->update(['is_driver_busy'=> 0]);

                        //send notification to customer
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_DELIVERED');
                        self::updateStorePayment($store_id);
                        self::sendNotificationCall($order->user_id, $request->order_id, $title, $message);
                        return $this->sendResponse([], trans('message.SUCCESS'));
                    }else{
                        $msg = trans('message.REACH_THE_DESTINATION');
                        return $this->sendError($msg, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    break;
                default:
                    $msg = 'Something went wrong !';
                    return $this->sendError($msg, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    break;
            };
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Update store and admin commission amount
     * respect to each(this) store
     */
    public function updateStorePayment($storeId)
    {
        $storeTotalEarning = Order::where('store_id', $storeId)->where('status', 'DELIVERED')->sum('amount_payable_to_store');
        $adminTotalCommission = Order::where('store_id', $storeId)->where('status', 'DELIVERED')->sum('admin_commission_amount');
        $store = Stores::find($storeId);
        $store->total_earning = $storeTotalEarning;
        $store->total_admin_commission = $adminTotalCommission;
        $store->save();
        return true;
    }

    public function sendNotificationCall($usesId, $orderId, $title, $message)
    {
        $token = User::where('id',$usesId)->pluck('device_token');
        $url = "";
        $ids = array($usesId);
        parent::sendPushNotification($ids, $token[0], $title, $message, $url, $orderId);
    }

    /**
     * Get drive order details
     * @return \Illuminate\Http\Response
     */
    public function orderDeatils(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            //$driverId = Auth::id();
            $order = Order::where('id',$request->order_id)
                        /*->where('driver_id',$driverId)*/
                        ->first();
            $order = new DriverOrderDetailsResource($order);     
            return $this->sendResponse($order, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    
}
