<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Order;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Constants\Constant;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Controllers\Api\OrderController as OrderApiController;
use App\Jobs\PushNotification;
use Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
            ->where('store_id', $storeId)
            ->where('status', 'ORDERED');
            //->whereDate('created_at', '>=', $yestorday);
            
            if(!empty($request->order_id)){
                $orders = $orders->where('order_id', $request->order_id);
            }
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
            if($request->ajax()){
                return view('vendor.orders.search', compact('orders'));
            }
            return view('vendor.orders.show', compact('orders'));
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
        
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  
        $order = Order::with(['orderItems', 'orderItems.product'])
        ->where('id',$id)
        ->first();
        
        return view('vendor.orders.order_details', compact('order'));
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * accept the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderStatusUpdate(Request $request, $id)
    {
        $order = Order::find($id);
        if($order){
            switch ($request->status) {
                case 'Accept':
                    if($order->status=="ORDERED")
                    {
                        /**
                         * if qty is not enough order will not accept
                         * */ 
                        $qty = self::updateProductQty($order->id);
                        if(!$qty){ 
                            return response()->json(['toster_class'=>'error', 'msg'=> 'Order item stock is not enough'],400);
                            break;
                        }
                        $order->status = "ACCEPTED";
                        $remarkAppend = $order->status_remark;
                        $order->status_remark = $remarkAppend."order accepted by vendor,";
                        $order->save();
                     
                        // ---send notification---
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_ACCEPTED_STORE_MSG');
                        self::sendPushNotification($order->user_id, $title, $message, $id);

                        $adminSetting = Helper::setting();
                        $obj = new OrderApiController;
                        $obj->findDriver($order->id, $order->store_id, $adminSetting->driver_range_1);

                        return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.order_accepted')],200);
                        break;
                    }
                    return response()->json(['toster_class'=>'warning', 'msg'=> 'Order already in '.$order->status.' stage'],400);
                    break;
                case 'Cancel':
                    if($order->status=="ORDERED")
                    {
                        $order->status = "CANCELLED";
                        $remarkAppend = $order->status_remark;
                        $order->status_remark = $remarkAppend."order cancelled by vendor,";
                        $order->save();
                        // ---send notification---
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_CANCELLED_STORE_MSG');
                        self::sendPushNotification($order->user_id, $title, $message, $id);
                        return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.order_cancelled')],200);
                        break;
                    }
                    return response()->json(['toster_class'=>'warning', 'msg'=> 'Order already in '.$order->status.' stage'],400);
                    break;
                case 'Ship':
                    if($order->status=="ACCEPTED")
                    {
                        $order->status = "READY_TO_SHIP";
                        $remarkAppend = $order->status_remark;
                        $order->status_remark = $remarkAppend."order is ready to ship update by vendor,";
                        $order->save();
                        // ---send notification---
                        $title = trans('message.ORDER_ACCEPTED_STORE_TITLE');
                        $message = trans('message.ORDER_SHIP_STORE_MSG');
                        self::sendPushNotification($order->user_id, $title, $message, $id);
                        if($order->driver_id){
                            self::sendPushNotification($order->driver_id, $title, $message, $id);
                        }
                        return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.ready_to_ship')],200);
                        break;
                    }
                    return response()->json(['toster_class'=>'warning', 'msg'=> 'Order already in '.$order->status.' stage'],400);
                    break;
                default:
                return response()->json(['toster_class'=>'error', 'msg'=> 'Invalied request'],400);
                break;
            } 
        }else{
            return response()->json(['toster_class'=>'error', 'msg'=> 'Invalied Order Id'],400);
        }
    }


    /**
     * Current order listing.
     * Orders which status = 'DELIVERED','CANCELLED'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function currentOrder(Request $request)
    {
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
            ->where('store_id', $storeId)
            ->whereNotIn('status', ['DELIVERED','CANCELLED','ORDERED']);
            if(!empty($request->order_id)){
                $orders = $orders->where('order_id', $request->order_id);
            }
            if(!empty($request->date)){
                $date = date('Y-m-d', strtotime($request->date));
                $orders = $orders->whereDate('created_at', $date);
            }
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
            if($request->ajax()){
            return view('vendor.orders.current_order_table', compact('orders'));
            }
            return view('vendor.orders.current_order', compact('orders'));
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }
    public function todayOrder(){
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
                                ->where('store_id', $storeId);
            $date =  Carbon::today();
            $orders = $orders->whereDate('created_at', $date);
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
            return view('vendor.orders.past_order', compact('orders'));                
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }
    public function todayCancels(){
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
                                ->where('store_id', $storeId);
            $orders = $orders->where('status' ,'=',"CANCELLED");
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
            return view('vendor.orders.past_order', compact('orders'));                
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }
    public function todaydelivered(){
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
                                ->where('store_id', $storeId);
            $orders = $orders->where('status' ,'=',"DELIVERED");
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
            return view('vendor.orders.past_order', compact('orders'));                
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }
    public function notyetAcceptedOrder(){
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            $orders = Order::with('orderItems.product')
                                ->where('store_id', $storeId);
            $orders = $orders->where('status' ,'=',"ORDERED")
                            ->where('updated_at', '<=' , Carbon::now()->addMinutes(1)->toDateTimeString());
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);
                            
            return view('vendor.orders.past_order', compact('orders'));             
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }


    /**
     * past order listing. 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pastOrder(Request $request)
    {
        $storeId = Helper::getStoreId();
        if($storeId)
        {
            //$twoDayAgo = Carbon::now()->subDay(2);
            $orders = Order::with('orderItems.product')
            ->where('store_id', $storeId)
            ->whereIn('status', ['DELIVERED','CANCELLED']);
            //->whereDate('created_at', '<=', $twoDayAgo);
            if(!empty($request->order_id)){
                $orders = $orders->where('order_id', $request->order_id);
            }
            if(!empty($request->date)){
                $date = date('Y-m-d', strtotime($request->date));
                $orders = $orders->whereDate('created_at', $date);
            }
            $orders = $orders->orderBy('id','DESC')
                            ->paginate(Constant::VENDOR_RECORD_PER_PAGE);

            if($request->ajax()){
            return view('vendor.orders.past_order_table', compact('orders'));
            }
            return view('vendor.orders.past_order', compact('orders'));
        }else{
            Session::false('warning', trans('vendor.store_not_found'));
            return redirect()->route('vendor.dashboard');
        }
    }

    /**
     * past order listing. 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProductQty($orderId){
        $orderItems = OrderItem::with('product')->where('order_id', $orderId)->get();
        $flage = true;
        foreach ($orderItems as $item) {
            $productOrderQty = $item->qty;
            $productAvailableQty = object_get($item, 'product.available_qty', 0);

            if($productAvailableQty >= $productOrderQty){
               $flage = true;
            }else{
               $flage = false;
               break;
            }
        }
        if(!$flage){
            return false;
        }else{
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                $product->available_qty = ($product->available_qty - $item->qty);
                $product->save();
            }
            return true;
        }
    }

    public function sendPushNotification($id, $title, $msg, $orderId)
    {
        $tokens = User::where('id', $id)
        ->pluck('device_token')
        ->toArray();
        $type = 'order_prepared';
        $data = array(
            'type' => $type,
            'target_id' => $orderId,
            'title' => $title,
            'description' => $msg,
            'is_read' => 0,
            'user_id' => $id,
        );
        Notification::create($data);
        PushNotification::dispatch($tokens, $title, $msg, $type, $orderId);
        return true;
    }

}