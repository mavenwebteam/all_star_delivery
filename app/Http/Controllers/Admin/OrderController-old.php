<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\User;
use App\Uniqcode;
use App\Models\Admin;
use App\Models\Stores;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Productimages;
use App\Models\Orders;
use App\Models\Brands;
use App\Models\Orderitems;
use App\Models\ReturnItem;
use Excel;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
use PDF;
class OrderController  extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('admin')['id'])
		{
			$admindata = Admin::find(session('admin')['id']);
			$this->user = $admindata;
		}
    }
    public function index(Request $request, $slug = null)
	{  
		$username=$request->input('username');
		
		$first_name=$request->input('first_name');
		$last_name=$request->input('last_name');
		
		$product_id=$request->input('product_id');
		$category_id=$request->input('category_id');
		$brand_id=$request->input('brand_id');
		$order_id=$request->input('order_id');
		$transaction_id=$request->input('transaction_id');
		$payment_mode=$request->input('payment_mode');
		$status=$request->input('status');
		$order_delivery_status=$request->input('order_delivery_status');
		if(!empty($slug))
		{
			$start_date= base64_decode($slug);
		}else{
		
		$start_date= $request->input('start_date');
		}
        $end_date=$request->input('end_date');
        
		$perpage=$request->input('perpage');
        $orderdata = Orders::select('orders.*','stores.name as storename','products.name as productname','category.name as catname','brands.name as brandname','users.id as user_id',
        DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'), DB::raw('sum(order_items.quantity) as sumquantity'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		->leftJoin('products', 'orders.product_id', '=', 'products.id')
		->leftJoin('category', 'products.cat_id', '=', 'category.id')
		->leftJoin('brands', 'orders.brand_id', '=', 'brands.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->groupBy('order_items.order_id')
		->orderBy("orders.created_at","DESC");
		
	     if($category_id!="")
		{ 
			$orderdata=$orderdata->where('products.cat_id',$category_id);
		}
		 if($product_id!="")
		{ 
			$orderdata=$orderdata->where('products.id',$product_id);
		}
		
		if($order_id!="")
		{ 
			$orderdata=$orderdata->where('orders.id',$order_id);
		}
		
		if($transaction_id!="")
		{ 
			$orderdata=$orderdata->where('orders.transaction_id',$transaction_id);
		}
		
		if($payment_mode!="")
		{ 
			$orderdata=$orderdata->where('orders.payment_mode',$payment_mode);
		}
		
		if($status!="")
		{ 
			$orderdata=$orderdata->where('orders.status',$status);
		}
		if($order_delivery_status!="")
		{ 
			$orderdata=$orderdata->where('orders.order_delivery_status',$order_delivery_status);
		}
	   
		if($first_name!="")
		{  
			
			
			$orderdata=$orderdata->where('users.first_name','like',"%".$first_name."%");
			
		}
		if($last_name!="")
		{  
			
			
			$orderdata=$orderdata->where('users.last_name','like',"%".$last_name."%");
			
		}

		if ($start_date!="" && $end_date!="") {

            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
			$orderdata = $orderdata->whereBetween('orders.created_at', [$_start_date, $_end_date]);
            

        }  else if ($start_date!="" && $end_date=="") {

			 $_start_date = date('Y-m-d H:i:s', strtotime($start_date));
			if(!empty($slug)){ 
			$_start_date = date('Y-m-d', strtotime($start_date));
					$orderdata = $orderdata->whereDate('orders.created_at',"=",$_start_date)->orderBy("created_at","DESC");
				}else{
					$orderdata = $orderdata->where('orders.created_at',">=",$_start_date)->orderBy("created_at","DESC");
				}

           
        }else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime( $request->input('end_date')));
			$orderdata = $orderdata->where('orders.created_at',"<=",$end_date)->orderBy("created_at","DESC");

        }
     
		//$orderdata=$orderdata->paginate(10);
		
		if(!empty($perpage)){
		    $orderdata = $orderdata->paginate($perpage);
		   } else {
			 $orderdata = $orderdata->paginate(10);
		   }
		//echo '<pre>';print_r($productdata);exit;
		if ($request->ajax()) 
		{
			return view('admin.orders.search', compact('orderdata'));  
        }
		$admindata = Admin::find(session('admin')['id']);
		
		$categorydata = Category::where('status','1')->where('is_deleted',0)->orderBy("name","DESC")->get();
		$category_box=array(''=>'Select Category');
		foreach($categorydata as $key=>$value){
			$category_box[$value->id]=$value->name;
		}
		
		$brandsdata = Brands::where('status','1')->where('is_deleted',0)->orderBy("name","DESC")->get();
		$brand_box=array(''=>'Select Brands');
		foreach($brandsdata as $key=>$value){
			$brand_box[$value->id]=$value->name;
		}
		
		$productdata = Product::where('status','1')->where('is_deleted',0)->orderBy("name","DESC")->get();
		$product_box=array(''=>'Select Product');
		foreach($productdata as $key=>$value){
			$product_box[$value->id]=$value->name;
		}

        return view('admin.orders.show', compact('orderdata','admindata','category_box','brand_box','product_box'));

		

	}
	
	
	
	
	 public function assigndeliveryboy(Request $request)
	{  
		$first_name=$request->input('first_name');
		$last_name=$request->input('last_name');
		
		$product_id=$request->input('product_id');
		$category_id=$request->input('category_id');
		$brand_id=$request->input('brand_id');
		$order_id=$request->input('order_id');
		$transaction_id=$request->input('transaction_id');
		$payment_mode=$request->input('payment_mode');
		$status=$request->input('status');
		$order_delivery_status=$request->input('order_delivery_status');
		
		$start_date= $request->input('start_date');
        $end_date=$request->input('end_date');
        $perpage=$request->input('perpage');
        $orderdata = Orders::select('orders.*','stores.name as storename','products.name as productname','category.name as catname','brands.name as brandname',
        DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'),  DB::raw('CONCAT(db.first_name, "  ", db.last_name) as deliveryboyname'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		  ->leftJoin('users as db', 'orders.delivery_boy_id', '=', 'db.id')
		->leftJoin('products', 'orders.product_id', '=', 'products.id')
		->leftJoin('category', 'orders.category_id', '=', 'category.id')
		->leftJoin('brands', 'orders.brand_id', '=', 'brands.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->groupBy('order_items.order_id')
        ->orderBy("orders.created_at","DESC");
       
	   
	     if($category_id!="")
		{ 
			$orderdata=$orderdata->where('products.cat_id',$category_id);
		}
		 if($product_id!="")
		{ 
			$orderdata=$orderdata->where('products.id',$product_id);
		}
		
		if($order_id!="")
		{ 
			$orderdata=$orderdata->where('orders.id',$order_id);
		}
		if($order_id!="")
		{ 
			$orderdata=$orderdata->where('orders.id',$order_id);
		}
		if($transaction_id!="")
		{ 
			$orderdata=$orderdata->where('orders.transaction_id',$transaction_id);
		}
		
		if($payment_mode!="")
		{ 
			$orderdata=$orderdata->where('orders.payment_mode',$payment_mode);
		}
		
		if($status!="")
		{ 
			$orderdata=$orderdata->where('orders.status',$status);
		}
		if($order_delivery_status!="")
		{ 
			$orderdata=$orderdata->where('orders.order_delivery_status',$order_delivery_status);
		}
	   
		if($first_name!="")
		{  
			
			
			$orderdata=$orderdata->where('users.first_name','like',"%".$first_name."%");
			
		}
		if($last_name!="")
		{  
			
			
			$orderdata=$orderdata->where('users.last_name','like',"%".$last_name."%");
			
		}

		if ($start_date!="" && $end_date!="") {

            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
			$orderdata = $orderdata->whereBetween('orders.created_at', [$_start_date, $_end_date]);
            

        }  else if ($start_date!="" && $end_date=="") {

			$_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$orderdata = $orderdata->where('orders.created_at',">=",$_start_date)->orderBy("created_at","DESC");

           
        }else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime( $request->input('end_date')));
			$orderdata = $orderdata->where('orders.created_at',"<=",$end_date)->orderBy("created_at","DESC");

        }

		if(!empty($perpage)){
		    $orderdata = $orderdata->paginate($perpage);
		   } else {
			 $orderdata = $orderdata->paginate(10);
		   }
		   $productdata = Product::where('status','1')->orderBy("name","DESC")->get();
		$product_box=array(''=>'Select Product');
		foreach($productdata as $key=>$value){
			$product_box[$value->id]=$value->name;
		}
		$deliveryboydata = User::where('type','2')->where('status','1')->orderBy("first_name","DESC")->get();
		$deliveryboy_box=array(''=>'Select Delivery Boy');
		foreach($deliveryboydata as $key=>$value){
			
			if(!empty($value->first_name))
			{
				$deliveryboy_box[$value->id]=$value->first_name.' '.$value->last_name;
			}	
		}
		//echo '<pre>';print_r($productdata);exit;
		if ($request->ajax()) 
		{
			return view('admin.orders.searchdeliverboy', compact('orderdata','product_box','deliveryboy_box'));  
        }
		$admindata = Admin::find(session('admin')['id']);
		
		
		
     
        return view('admin.orders.assigndeliveryboy', compact('orderdata','admindata','deliveryboy_box','brand_box','product_box'));

		

	}
	
	 public function returnitem(Request $request)
	{  
		$name=$request->input('name');
		
		$product_id=$request->input('product_id');
		$category_id=$request->input('category_id');
		$brand_id=$request->input('brand_id');
		$order_id=$request->input('order_id');
		$transaction_id=$request->input('transaction_id');
		$payment_mode=$request->input('payment_mode');
		$status=$request->input('status');
		$order_delivery_status=$request->input('order_delivery_status');
		
		$start_date= $request->input('start_date');
        $end_date=$request->input('end_date');
        $perpage=$request->input('perpage');
		
		
		
/*
        $orderdata = Orders::select('orders.*','stores.name as storename','products.name as productname','category.name as catname','brands.name as brandname',
        DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'),  DB::raw('CONCAT(db.first_name, "  ", db.last_name) as deliveryboyname'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		  ->leftJoin('users as db', 'orders.delivery_boy_id', '=', 'db.id')
		->leftJoin('products', 'orders.product_id', '=', 'products.id')
		->leftJoin('category', 'orders.category_id', '=', 'category.id')
		->leftJoin('brands', 'orders.brand_id', '=', 'brands.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->where('orders.return_status','1')
		->groupBy('order_items.order_id')
        ->orderBy("orders.created_at","DESC");
		*/
		
		$orderdata = ReturnItem::select('return_items.*','stores.name as storename','products.name as productname',
        DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'))
        ->leftJoin('stores', 'return_items.store_id', '=', 'stores.id')
        ->leftJoin('users', 'return_items.user_id', '=', 'users.id')
		->leftJoin('products', 'return_items.product_id', '=', 'products.id')
        ->orderBy("return_items.created_at","DESC");
       
	   
	     if($category_id!="")
		{ 
			$orderdata=$orderdata->where('return_items.category_id',$category_id);
		}
		 if($product_id!="")
		{ 
			$orderdata=$orderdata->where('return_items.product_id',$product_id);
		}
		
		if($order_id!="")
		{ 
			$orderdata=$orderdata->where('return_items.order_id',$order_id);
		}
		
		if($transaction_id!="")
		{ 
			$orderdata=$orderdata->where('return_items.transaction_id',$transaction_id);
		}
		
		if($payment_mode!="")
		{ 
			$orderdata=$orderdata->where('return_items.payment_mode',$payment_mode);
		}
		
		if($status!="")
		{ 
			$orderdata=$orderdata->where('return_items.status',$status);
		}
		if($order_delivery_status!="")
		{ 
			$orderdata=$orderdata->where('return_items.order_delivery_status',$order_delivery_status);
		}
	   
		if($name!="")
		{
			  $users = explode(" ", $name);
			$orderdata=$orderdata->where('users.first_name','like',"%".$users[0]."%");
			if(!empty($users[1])){
				$orderdata=$orderdata->where('users.last_name','like',"%".$users[1]."%");
			}
			}

		if ($start_date!="" && $end_date!="") {

            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
			$orderdata = $orderdata->whereBetween('return_items.created_at', [$_start_date, $_end_date]);
            

        }  else if ($start_date!="" && $end_date=="") {

			$_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$orderdata = $orderdata->where('return_items.created_at',">=",$_start_date)->orderBy("created_at","DESC");

           
        }else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime( $request->input('end_date')));
			$orderdata = $orderdata->where('return_items.created_at',"<=",$end_date)->orderBy("created_at","DESC");

        }

		
		if(!empty($perpage)){
		    $orderdata = $orderdata->paginate($perpage);
		   } else {
			 $orderdata = $orderdata->paginate(10);
		   }
		//echo '<pre>';print_r($productdata);exit;
		$productdata = Product::where('status','1')->orderBy("name","DESC")->get();
		$product_box=array(''=>'Select Product');
		foreach($productdata as $key=>$value){
			$product_box[$value->id]=$value->name;
		}
		if ($request->ajax()) 
		{
			return view('admin.orders.searchreturnitem', compact('orderdata','product_box'));  
        }
		$admindata = Admin::find(session('admin')['id']);
		
		$deliveryboydata = User::where('type','2')->where('status','1')->orderBy("first_name","DESC")->get();
		$deliveryboy_box=array(''=>'Select Delivery Boy');
		foreach($deliveryboydata as $key=>$value){
			
			if(!empty($value->first_name))
			{
				$deliveryboy_box[$value->id]=$value->first_name.' '.$value->last_name;
			}	
		}
		
     
        return view('admin.orders.returnitem', compact('orderdata','admindata','deliveryboy_box','brand_box','product_box'));

		

	}
	
	
	public function exportorders(Request $request)
	{    
		$name=$request->input('name');
		
		$product_id=$request->input('product_id');
		$category_id=$request->input('category_id');
		$brand_id=$request->input('brand_id');
		$order_id=$request->input('order_id');
		$transaction_id=$request->input('transaction_id');
		$payment_mode=$request->input('payment_mode');
		$status=$request->input('status');
		$order_delivery_status=$request->input('order_delivery_status');
		
		$start_date= $request->input('start_date');
        $end_date=$request->input('end_date');
        
		$perpage=$request->input('perpage');
        $orderdata = Orders::select('orders.*','stores.name as storename','products.name as productname','category.name as catname','brands.name as brandname','users.id as user_id',
        DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'), DB::raw('sum(order_items.quantity) as sumquantity'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		->leftJoin('products', 'orders.product_id', '=', 'products.id')
		->leftJoin('category', 'products.cat_id', '=', 'category.id')
		->leftJoin('brands', 'orders.brand_id', '=', 'brands.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->groupBy('order_items.order_id')
       ->orderBy("orders.created_at","DESC");
       // ->orderBy("orders.created_at","DESC");
		
	     if($category_id!="")
		{ 
			$orderdata=$orderdata->where('orders.category_id',$category_id);
		}
		 if($product_id!="")
		{ 
			$orderdata=$orderdata->where('order_items.product_id',$product_id);
		}
		
		if($order_id!="")
		{ 
			$orderdata=$orderdata->where('orders.id',$order_id);
		}
		
		if($transaction_id!="")
		{ 
			$orderdata=$orderdata->where('orders.transaction_id',$transaction_id);
		}
		
		if($payment_mode!="")
		{ 
			$orderdata=$orderdata->where('orders.payment_mode',$payment_mode);
		}
		
		if($status!="")
		{ 
			$orderdata=$orderdata->where('orders.status',$status);
		}
		if($order_delivery_status!="")
		{ 
			$orderdata=$orderdata->where('orders.order_delivery_status',$order_delivery_status);
		}
	   
		if($name!="")
		{
			$users = explode(" ", $name);

			$orderdata=$orderdata->where('users.first_name','like',"%".$users[0]."%");
			if(!empty($users[1])){
				$orderdata=$orderdata->where('users.last_name','like',"%".$users[1]."%");
			}
			}

		if ($start_date!="" && $end_date!="") {

            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
			$orderdata = $orderdata->whereBetween('orders.created_at', [$_start_date, $_end_date]);
            

        }  else if ($start_date!="" && $end_date=="") {

			$_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$orderdata = $orderdata->where('orders.created_at',">=",$_start_date)->orderBy("created_at","DESC");

           
        }else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime( $request->input('end_date')));
			$orderdata = $orderdata->where('orders.created_at',"<=",$end_date)->orderBy("created_at","DESC");

        }

		$orderdata=$orderdata->get();
		//echo '<pre>';print_r($orderdata);exit;
		$export_data=array();
		foreach($orderdata as $key=>$value){

		if($value->payment_mode==1) {
			$payent_mode="COD";
		} elseif($value->payment_mode==2)
		{
			$payent_mode="Card";
		}else{
			$payent_mode="ONLINE";
		}
		
		if($value->status==1) {
			$status="Complete";
		} elseif($value->status==2)
		{
			$status="Pending";
		}else{
			$status="Failed";
		}
		
		if($value->order_delivery_status==0) {
			$order_delivery_status="sent to restaurant";
		} elseif($value->order_delivery_status==1)
		{
			$order_delivery_status="accepted by restaurant";
		} elseif($value->order_delivery_status==2)
		{
			$order_delivery_status="preparing order";
		}
		 elseif($value->order_delivery_status==3)
		{
			$order_delivery_status="picked up and flying to you";
		}
		else{
			$order_delivery_status="arrived";
		}
	             $productdata = Orderitems::select('order_items.*','products.name as productname')
			->leftJoin('products', 'order_items.product_id', '=', 'products.id')->where('order_items.order_id',$value->id)->get();
			$a = array();
			foreach($productdata as $da)
			{
				 $a[] = $da->productname;
				
			}
			$prod = implode(", ",$a);

				$export_data[]=array('order_id'=>$value->id,'customer_name'=>$value->username,
				'product_name'=>$prod,
				'category'=>$value->catname,
				'brand'=>$value->brandname,
				'transaction_id'=>$value->transaction_id,
				'quentity'=>$value->sumquantity,
				'total_amount'=>$value->total_amount,
				'delivery_charge'=>$value->total_shipping_amount,
				'net_amount'=>$value->net_amount,
				'is_cancelled'=> $value->is_cancelled==1 ? 'Yes' : 'No',
				'net_amount'=>$value->net_amount,
				'payent_mode'=>$payent_mode,
				'order_delivery_status'=>$order_delivery_status,
				'status'=>$status,
				'created_at'=> date("d/m/Y h:i:s A",strtotime($value->created_at)));
		}
		//echo '<pre>';print_r($export_data);exit;
		return Excel::create('order_list', function($excel) use ($export_data) {

            $excel->sheet('mySheet', function($sheet) use ($export_data)

            {

                $sheet->fromArray($export_data);

            });

        })->download('csv');


		

	}
	
	public function addreturnitem()
	{  
	    
		return view('admin.orders.addreturnitem');
	}
    
	public function addreturnitemdata(Request $request)
	{  
	    $order_id = $request->input('order_id');
	    $orderitems = Orderitems::select('order_items.*','products.name as productname')
									->where('order_items.order_id',$order_id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->first();
									
		if(empty($orderitems))
		{
			 
			return response()->json(['success'=>false ,'message'=>"No order found for the requested order id."]);
		}else{
			
			return view('admin.orders.addreturnitemdata',array('orderitems'=>$orderitems));
		}
	    
		
	}
	
	public function addreturnitempost(Request $request)
	{  
	    $id=$request->input('id');
		$return_quantity=$request->input('return_quantity');
		$quantity=$request->input('quantity');
	    $newquantity = $quantity - $return_quantity;
		$orderitems = Orderitems::find($id);	
		$orders = Orders::find($orderitems->order_id);
		if($orders)
		
		$orderitems->quantity = $newquantity;
		$orderitems->save();	
		
			
		$newquantity = $orders->total_quantity - $return_quantity;
		$orders->total_quantity = $newquantity;
		$orders->save();
		
		$ReturnItem = new ReturnItem;
		
		$ReturnItem->order_id = $orderitems->order_id;
		$ReturnItem->user_id = $orderitems->user_id;
		$ReturnItem->product_id = $orderitems->product_id;
		$ReturnItem->quantity = $return_quantity;
		$ReturnItem->price = $orderitems->price;
		$ReturnItem->vendor_id = $orderitems->vendor_id;
		$ReturnItem->store_id = $orderitems->store_id;
		$ReturnItem->outlet_id = $orderitems->outlet_id;
		$ReturnItem->order_id = $orderitems->order_id;
		$ReturnItem->order_id = $orderitems->order_id;
		$ReturnItem->save();
		
		 echo json_encode(array('class'=>'success','message'=>'Item Return successfully.'));die;
	    
		
	}
	public function vieworders($id)

	{
		$id = base64_decode($id);
		/*$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','category.name as cat_name')
									->where('order_items.order_id',$id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();*/
		$orderdata = Orders::select('orders.*','stores.name as storename','products.name as productname','category.name as catname',
		'brands.name as brandname',
		DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'), 
		DB::raw('CONCAT(db.first_name, "  ", db.last_name) as dbname'),DB::raw('CONCAT(v.first_name, "  ", v.last_name) as vendorname'), DB::raw('sum(order_items.quantity) as sumquantity'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		  ->leftJoin('users as db', 'orders.delivery_boy_id', '=', 'db.id')
		  ->leftJoin('users as v', 'orders.vendor_id', '=', 'v.id')

		->leftJoin('products', 'orders.product_id', '=', 'products.id')
		->leftJoin('category', 'products.cat_id', '=', 'category.id')
		->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->groupBy('order_items.order_id')
		->where('orders.id',$id)->first();
       	
		//echo '<pre>'; print_r($orderdata); die;
		return view('admin.orders.view',["orderdata" => $orderdata]);

	}
    
	public function assigndeliveryboypost(Request $request)
	{
			$id =$request->input('order_id');
			$orderdata = Orders::find($id);
			
			if($orderdata->picker_status == 3)
			{
				$deliveryBoys = User::where('status','1')->where('order_process_status',0)->whereNotNull('auth_token')->where('is_notification','1')->where('type',2)->where('id',$request->input('db_id'))->where('is_online',1)->where('order_status',0)->select('device_id','id','latitude','longitude')->first(); 
				if(!empty($deliveryBoys))
				{
					
					$stores = DB::table('stores')->where('id',$orderdata->store_id)->first();
					$outletdata = DB::table('users')->where('id',$orderdata->outlet_id)->first();
					
					$tokenList[] = $deliveryBoys->device_id;
					$title = "Bringoo-OrderId #".$id;
									$message = "You assign for this order ";
									$extraNotificationData = ["order_id" => $id,"notification_type" =>'new_request_driver','store_name'=>$stores->name,'store_address'=>$outletdata->address,'store_logo'=>URL::to('/media/store').'/'.$stores->image, 'lat'=>$outletdata->latitude,'lng'=>$outletdata->longitude];
									//echo '<pre>'; print_r($tokenList); die;
									$notification = $this->send_notification($tokenList,$title,$message,$extraNotificationData);
					if($notification == 1)
					{		
							$notification = new Notification();
							$notification->user_id = $request->input('db_id');
							$notification->noti_type = $title;
							$notification->notification = $message;
							$notification->is_read = 0;
							$notification->save();
							$orderdata->delivery_boy_id = $request->input('db_id');
							$orderdata->save();
							return response()->json(['class'=>'success','message'=>'Delivery Boy Assign successfully.']);
					}else{
								return response()->json(['class'=>'error','message'=>'Delivery Boy not assign.']);
					
					}		
				}else{
					return response()->json(['class'=>'error','message'=>'This Delivery boy not available.']);
				
				}
						
			}else{
				return response()->json(['class'=>'error','message'=>"Picker not complete this order so can't assign this driver."]);
			}
		
	}
	

	public function print_order($id)
	{ 
	  
		$orderdata = Orders::select('orders.*','stores.name as storename', DB::raw('CONCAT(users.first_name, "  ", users.last_name) as username'), DB::raw('CONCAT(db.first_name, "  ", db.last_name) as dbname'), DB::raw('sum(order_items.quantity) as sumquantity'),'vendor.email as vendoremail','stores.address as storesddress','vendor.country_code','vendor.mobile',DB::raw('sum(order_items.subtotal) as ordersubtotal'))
        ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
		->leftJoin('users as db', 'orders.delivery_boy_id', '=', 'db.id')
		 ->leftJoin('users as vendor', 'stores.user_id', '=', 'vendor.id')
		->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
		->where('orders.id',$id)->first();
		 //$pdf = PDF::loadView('admin.orders.viewpdf', $orderdata);
        // echo '<pre>'; print_r($orderdata); die;
        $productdata = Orderitems::select('order_items.*','products.name as productname')
		->leftJoin('products', 'order_items.product_id', '=', 'products.id')
		->where('order_items.order_id',$orderdata->id)->get();

        //return $pdf->download('hdtuto.pdf');
		 view()->share('orderdata',$orderdata);
		 view()->share('productdata',$productdata);
        //if($request->has('download')){
            $pdf = PDF::loadView('admin.orders.viewpdf');
           return $pdf->stream('admin.orders.viewpdf');
        //}
        return view('admin.orders.viewpdf');
		//return view('admin.orders.viewpdf',["orderdata" => $orderdata]);
	}
	
}

?>