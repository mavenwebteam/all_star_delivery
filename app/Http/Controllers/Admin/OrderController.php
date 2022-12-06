<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Address;
use App\User;
use App\Constants\Constant;
use Session;
use Validator, DB;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\BaseController;
use Carbon\Carbon;

class OrderController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::with(['orderItems','store','driver']);

        if(!empty($request->order_id)){
            $orders = $orders->where('order_id', $request->order_id);
        }
        if(!empty($request->store_id)){
            $orders = $orders->where('store_id', $request->store_id);
        }
        if(!empty($request->status)){
            if($request->status == 'RUNNING'){
                $orders = $orders->whereNotIn('status', ['DELIVERED','CANCELLED']);
            }else{
                $orders = $orders->where('status', $request->status);
            }
        }
        if(!empty($request->start_date)){
            $startDate = date('Y-m-d H:i:s', strtotime($request->start_date));
            $orders = $orders->whereDate('created_at', '>=', $startDate);
        }
        if(!empty($request->end_date)){
            $endDate = date('Y-m-d H:i:s', strtotime($request->end_date));
            $orders = $orders->whereDate('created_at', '<=', $endDate);
        }
        $orders = $orders->orderBy('id','DESC')
                        ->paginate(Constant::ADMIN_RECORD_PER_PAGE);
                    
        if($request->ajax()){
            return view('admin.orders.table', compact('orders'));
        }
        return view('admin.orders.index', compact('orders'));
    }
    public function showtodayorder(){
        $orders = Order::with(['orderItems','store','driver']);
        $todayDate=Carbon::today();
        $orders = $orders->whereDate('created_at', '=', $todayDate);
        $orders = $orders->orderBy('id','DESC')
                        ->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if($orders){return view('admin.orders.index', compact('orders')); } 
        else{echo "error";}               
                      
    }
    public function todaycancelorder(){
        $orders = Order::with(['orderItems','store','driver']);
        $todayDate=Carbon::today();
        $orders = $orders->whereDate('created_at', '=', $todayDate);
        $orders = $orders->where('status','=','CANCELLED');
        $orders = $orders->orderBy('id','DESC')
                        ->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if($orders){return view('admin.orders.index', compact('orders')); } 
        else{echo "error";}               
                      
    }
    public function notacceptedorder(){
        $orders = Order::with(['orderItems','store','driver']);
        $todayDate=Carbon::today();
        $orders = $orders->whereDate('created_at', '=', $todayDate);
        $orders = $orders->where('status','=','ORDERED')
                        ->where('updated_at', '<=' , Carbon::now()->addMinutes(1)->toDateTimeString());
        $orders = $orders->orderBy('id','DESC')
                        ->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if($orders){return view('admin.orders.index', compact('orders')); } 
        else{echo "error";}               
                      
    }
    /**
     * edit specific order 
     * @ @param int $id as order Id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $order = Order::with(['driver','address','user','store'])
        ->where('id',$id)
        ->first();

        $assignedDriver = object_get($order, 'driver', NULL);

        $drivers = (object) [];

        if(empty($assignedDriver)){
            $latitude = object_get($order, 'store.lat', NULL);
            $longitude = object_get($order, 'store.lng', NULL);
        
            $distance_query = '(6371* acos( cos( radians('.$latitude.') ) * cos( radians( users.latitude ) ) * cos( radians( users.longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( users.latitude ) ) ) )';
        
            $drivers = User::select('users.*', DB::raw($distance_query.' AS distance'))
                ->where('role_id','2') //role_id = 2 for driver 
                ->where('status','1')
                ->where('is_online','1')
                ->where('is_driver_busy','0')
                ->whereNotNull('device_token')
                ->where('device_token', '!=', "")
                ->where('is_deleted','0')
                ->having("distance", "<", 30) // 30 KM
                ->orderBy('distance', 'ASC')
                ->get();
        }
        return view('admin.orders.edit', compact('order','drivers', 'assignedDriver'));

    }

    /**
     * update specific order 
     * @ @param int $id as order Id
     * @ @param Pequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), 
		[
			'driver_id' => 'nullable|exists:users,id',
			'order_status' => 'required',
			'reason_of_cancel' => 'nullable|required_if:order_status,==,CANCELLED'

		]);
        if ($validator->fails()) 
        {
            return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
        }

        $status = $request->order_status;
        $order = Order::find($id);
        
        switch ($status) {
            case 'CANCELLED':   
                if($order->status != "DELIVERED")
                {
                    //----------DB transection----------
                    try {
                        DB::transaction(function () use($order, $request) {
                            $order->status = "CANCELLED";
                            $order->reason_of_cancel = $request->reason_of_cancel;
                            $remarkAppend = $order->status_remark;
                            $order->status_remark = $remarkAppend."order cancelled by admin, ";
                            $order->save();
                            // make driver free
                            if($order->driver_id){
                                $driver = User::find($order->driver_id);
                                $driver->is_driver_busy = 0;
                                $driver->save();
                            }
                            // ---send notification---
                            $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                            $vendorObj = new VendorOrderController;
                            $vendorObj->sendPushNotification($order->user_id, $title, $request->reason_of_cancel, $order->id);
                        });
                        return response()->json(['success'=>true ,'msg'=>'Order has been cancelled!']);
                    } catch (Exception $ex) {
                        return response()->json(['success'=>false ,'msg'=>'Something went wrong!']);
                    }
                    break;
                }
                return response()->json(['success'=>false ,'msg'=>'Order already '.$order->status ]);
                break;
            case 'ACCEPTED':
                $statusArr = ['ARRIVED_AT_DELIVERY_LOCATION', 'ON_THE_WAY', 'DELIVERED', 'CANCELLED'];
                if($order->delivery_boy_status == NULL && (!in_array( $order->status, $statusArr)))
                {   
                    if(empty($request->driver_id)){
                       return response()->json(['success'=>false ,'errors'=>['driver_id'=>'driver is required for accept order.'] ]);
                    }
                    $driver = User::find($request->driver_id);
                    if($driver->is_driver_busy == 1){
                       return response()->json(['success'=>false ,'msg'=> $driver->fullName.' is busy now!']);
                    }
                    try {
                        DB::transaction(function () use($order, $driver) {
                            $driver->is_driver_busy = 1;
                            $driver->save();
                            $order->status = "ACCEPTED";
                            $order->driver_id = $driver->id;
                            $order->delivery_boy_status = "ASSIGNED";
                            $remarkAppend = $order->status_remark;
                            $order->status_remark = $remarkAppend."order accepted by admin,";

                            //------------payment settlement start----------
                            $address = Address::find($order->address_id);
                            $baseControllerObj = new BaseController;
                            $distance = $baseControllerObj->calculateDistance($address->latitude,$address->longitude, $order->store_id);
                            $apiObj = new ApiOrderController;
                            $amountPayableToDriver = number_format($apiObj->getDeliveryCharges($distance, "DRIVER"), 2, '.', ''); 
                            $order->amount_payable_to_driver = $amountPayableToDriver;
                            /**
                             * adminShare = customerPayableAmount - storeShare - amountPayableToDriver
                             *  */
                            $customerPayableAmount = number_format(object_get($order,'grand_total', 0), 2, '.', '');
                            $amountPayableToStore = number_format(object_get($order,'amount_payable_to_store', 0), 2, '.', '');
                            $adminShare = $customerPayableAmount - $amountPayableToStore -  $amountPayableToDriver;

                            $order->admin_commission_amount = number_format($adminShare, 2, '.', '');
                            //------------payment settlement end----------
                            $order->save();
                            // ---send notification---
                            $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                            $message = trans('message.ORDER_ACCEPTED_STORE_MSG');
                            $vendorObj = new VendorOrderController;
                            $vendorObj->sendPushNotification($order->user_id, $title, $message, $order->id);
                        });
                    } catch (Exception $ex) {
                        return response()->json(['success'=>false ,'msg'=>'Something went wrong!']);
                    }
                    return response()->json(['success'=>true ,'msg'=>'Order accepted !' ]);  
                    break;
                } 
                return response()->json(['success'=>false ,'msg'=>'Order already '.$order->status ]);  
                break;

            case 'DELIVERED':
                if(empty($order->driver_id)){
                    return response()->json(['success'=>false ,'msg'=>'Assign a driver first for delivere this order- '.$order->order_id ]);
                    die;
                }
                try {
                    DB::transaction(function () use($order) {
                        // driver mark as free
                        $driver = User::find($order->driver_id);
                        $driver->is_driver_busy = 0;
                        $driver->save();

                        // update order
                        $order->status = "DELIVERED";
                        $order->delivery_boy_status = "DELIVERED";
                        $remarkAppend = $order->status_remark;
                        $order->status_remark = $remarkAppend."order delivered mark by admin,";                        
                        $order->save();
                        // ---send notification---
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_DELIVERED');
                        $vendorObj = new VendorOrderController;
                        $vendorObj->sendPushNotification($order->user_id, $title, $message, $order->id);
                    });
                } catch (Exception $ex) {
                    return response()->json(['success'=>false ,'msg'=>'Something went wrong!']);
                }
                return response()->json(['success'=>true ,'msg'=>'Order has been delivered !']);
                break;
            default:
                return response()->json(['success'=>false ,'msg'=>'Invailed order status !']);
                break;
        }
    }


     /**
     * Show specific order detail
     * @ @param int $id as order Id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where('id',$id)->with(['orderItems','store','driver','user', 'address'])->first();
        if($order){
            return view('admin.orders.show', compact('order'));
        }
    }

    /**
     * update the specified order status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderUpdateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if($order){
            switch ($request->status) {
                case 'Cancel':
                        $order->status = "CANCELLED";
                        $remarkAppend = $order->status_remark;
                        $order->status_remark = $remarkAppend."order cancelled by admin, ";
                        $order->save();
                        return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.order_cancelled')],200);
                        break;
                default:
                return response()->json(['toster_class'=>'error', 'msg'=> 'Invalied request'],400);
                break;
            } 
        }else{
            return response()->json(['toster_class'=>'error', 'msg'=> 'Invalied Order Id'],400);
        }
    }
}
