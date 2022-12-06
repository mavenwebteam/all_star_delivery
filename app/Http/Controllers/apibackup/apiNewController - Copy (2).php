<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Response;
use Hash;
use DB;
use Validator;
use File;
use App\Helpers;
use App\Models\Category;
use App\Models\Homeslider;
use App\Models\Product;
use App\Models\Stores;
use App\Models\Favorites;
use App\Models\Userlocations;
use App\Models\Emailtemplates;
use App\Models\Cartitem;
use App\Models\Productimages;
use App\Models\Orders;
use App\Models\Orderitems;
use App\Models\Rating;
use App\Models\Storeoffers;
use App\Models\CouponCode;
use App\Models\Notification;
use App\Models\Content;
use App\User;
use Config;
use Session;
use URL;
use Mail;
use Auth;
use Carbon\Carbon;
use App\Models\Productinventory;
use Illuminate\Session\Store;

class apiNewController  extends Controller
{
	public function home(Request $request) 
	{
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		if ($decoded)
		{
			if(!empty($decoded['lat']) && !empty($decoded['long'])) 
			{
				$lat = $decoded['lat'];
				$long = $decoded['long'];
				$store_status = $decoded['store_status'];
				$fast_delivery = $decoded['fast_delivery'];
				$stores_area = $decoded['stores_area'];
				$previous_orders = $decoded['previous_orders'];
				$popular_orders = $decoded['popular_orders'];
				$order_amount = $decoded['order_amount'];
				$distance = $decoded['distance'];
				$order_type = $decoded['order_type'];
				$payment_type = $decoded['payment_type'];
				$rating = $decoded['rating'];
				$store_name = $decoded['store_name'];
				$user_id = !empty($decoded['user_id']) ? $decoded['user_id'] : "";
				
				$home_data['store_status'] = $store_status;
				$home_data['fast_delivery'] = $fast_delivery;
				$home_data['stores_area'] = $stores_area;
				$home_data['previous_orders'] = $previous_orders;
				$home_data['popular_orders'] = $popular_orders;
				$home_data['order_amount'] = $order_amount;
				$home_data['distance'] = $distance;
				$home_data['distance'] = $distance;
				$home_data['order_type'] = $order_type;
				$home_data['payment_type'] = $payment_type;
				$home_data['rating'] = $rating;
				$home_data['store_name'] = $store_name;
				
				$gr_circle_radius = 6371;
      			$max_distance = 10;
				
				/*$distance_query='(6371* acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$long.') ) + sin( radians('.$lat.') ) * sin( radians( lat ) ) ) )';
				   $store_data = Stores::select('stores.id','stores.offer_code','stores.offer_per',
				   'stores.user_id','stores.name','stores.description', 'stores.image',
				   'stores.slug','stores.category_id','stores.open_at','stores.close_at',
				   'stores.country_id','stores.city_id','stores.address','stores.zipcode',
				   'stores.lat','stores.lng','stores.rating','stores.fast_delivery','stores.order_type',				   'stores.offer_start_date',				   'stores.offer_end_date',				   'stores.is_open',
				   'stores.status','stores.package_id','stores.closing_day','stores.delivery_radius','stores.created_at',				   'orders.payment_mode','orders.user_id as orderuser_id',
				   DB::raw('( '.$distance_query.' AS distance'))
				   ->leftJoin('orders', 'stores.id', '=', 'orders.store_id')
					->having('distance', '<', $max_distance)
					->where('stores.status','1')
					->groupBy('stores.id')
					->orderBy('distance');*/
					$store_data = Stores::select('stores.id','stores.offer_code','stores.offer_per',
				   'stores.user_id as storevendor_id','stores.name','stores.description', 'stores.image',
				   'stores.slug','stores.category_id','stores.open_at','stores.close_at',
				   'stores.country_id','stores.city_id','stores.address','stores.zipcode',
				   'stores.lat','stores.lng','stores.rating','stores.fast_delivery','stores.order_type',				   'stores.offer_start_date',				   'stores.offer_end_date',				   'stores.is_open',
				   'stores.status','stores.package_id','stores.closing_day','stores.delivery_radius','stores.created_at',				   'orders.payment_mode','orders.user_id as orderuser_id')
				   ->leftJoin('orders', 'stores.id', '=', 'orders.store_id')
					
					->where('stores.status','1')
					->groupBy('stores.id');
					//dd($store_data);
				if(!empty($store_status))
					{
						$store_data = $store_data->where('stores.is_open',$store_status);
					}
				if(!empty($fast_delivery))
					{
						$store_data = $store_data->where('stores.fast_delivery',$fast_delivery);
					}	
				if(!empty($order_amount))
					{
						$store_data = $store_data->where('stores.min_order_amount', '>=',$order_amount);
					}
				if(!empty($order_type))
					{
						$store_data = $store_data->where('stores.order_type',$order_type);
					}	
				if(!empty($payment_type))
					{   
				        if($payment_type == 1 || $payment_type== 2 || $payment_type==3)
						{
						  $store_data = $store_data->where('orders.payment_mode',$payment_type);
						} 
					}	
				if(!empty($rating))
					{
						$store_data = $store_data->where('stores.rating','>=', $rating);
					}	
				if(!empty($popular_orders))
					{
						$store_data = $store_data->where('stores.rating','>=', 4);
					}
				if(!empty($user_id) && !empty($previous_orders))
					{
						$store_data = $store_data->where('orders.user_id', $user_id);
					}	
                if(!empty($store_name) && !empty($store_name))
					{
						$store_data = $store_data->where('stores.name', 'like',"%$store_name%");
					}						
					
			 
				$store_data=$store_data->get();			   
				//$store_count_data = Stores::orderBy("id","ASC")->where('stores.status','1')->count();
				$home_data['store_count']=$store_data->count();
				if(isset($decoded['user_id']) && $decoded['user_id']!=""){
						$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
						$home_data['count'] = (string)$usercartitemcount;
					}else{
						$home_data['count']='0';
					}
				//	dd($store_data);
				if($store_data->count()>0)
				{
					
					foreach($store_data as $data)
					{  
						$store_data = Stores::select('stores.id','stores.category_id')->where('stores.id',$data->id)->first();
						if(!empty($store_data->category_id) && $store_data->category_id!=""){
							$store_cat_id=explode(',',$store_data->category_id);
							$product_category_id = Category::select('category.id','category.name','category.image')->WhereIn('category.id',$store_cat_id)->where('category.status','1')->get();
							$product_category_count = Category::select('category.id','category.name','category.image')->WhereIn('category.id',$store_cat_id)->where('category.status','1')->count();
							$product_category_data=array();
							
							//$product_data=array();
							//echo '<pre>'; print_r($product_category_id); die;
							foreach($product_category_id as $data1)
							{
								
									//die;	
								$product_category_data[]=array('id' =>$data1->id,'name' =>$data1->name,'image'=>URL::to('/media/category').'/'.$data1->image);
							}
						}
						if(!empty($data->storevendor_id))
						{   //echo $data->storevendor_id; 
							$outlet_data=array();
							$outLetData = User::where('vendor_id',$data->storevendor_id)->whereIn('type',[1,4])->get();
							
								foreach($outLetData as $outdata)
								{
									$outlet_data[]=array('id' =>$outdata->id,
									'name' =>$outdata->first_name.' '.$outdata->last_name,
									'email' =>$outdata->email,
									'address' => !empty($outdata->address) ? $outdata->address : '',
									'latitude' => !empty($outdata->latitude) ? $outdata->latitude : '0',
									'longitude' => !empty($outdata->longitude) ? $outdata->longitude : '0',
									//'image'=>URL::to('/media/users').'/'.$outdata->profile_pic
									);
								}
							
						}
						
						if(isset($decoded['user_id']) && $decoded['user_id']!=""){
							$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
							$home_data['count'] = (string)$usercartitemcount;
						}else{
							$home_data['count']='0';
						}
				
						$helper=new Helpers;
						if($data->lat!="" && $data->lng!=""){
							$distance= $helper->distance($lat,$long,$data->lat,$data->lng,'K');
						}else{
							$distance=0;
						}
						if(!empty($decoded['user_id']))
						{   
							$StoreFaviourite = DB::table('favorites')->where('user_id',$decoded['user_id'])->where('store_id',$data->id)->pluck('store_id')->all();
						}
						 
						$home_store_data[] =  array('id' =>$data->id,'users'=>$data->storevendor_id,'name' =>!empty($data->name) ? $data->name : '','image'=> !empty($data->image) ? URL::to('/media/store').'/'.$data->image : URL::to('/media/notfound-image.png'),'distance'=>number_format($distance,2).' km'
						,'rating'=>(string)$data->rating,'is_open'=>!empty($data->is_open) ? $data->is_open : 'No','offer_code'=>!empty($data->offer_code) ? $data->offer_code : '','is_faviourte' => !empty($StoreFaviourite) ? 1 : 0, 'offer'=>$data->offer_per.'%','address'=> !empty($data->address) ? $data->address :'','product_count'=>$product_category_count,'product_category'=>$product_category_data, 'outlets' =>$outlet_data);
						unset($outlet_data);
						$home_data['store']=$home_store_data;
					}	
					
						$status = '1';
						$message='Stores listed below.';	
				}else {
					$home_data['store'] = [];
					
					$message = 'No store data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
die();
   }

   public function store_detail(Request $request) 
	{
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$store_detail_data=array();
		$store_product_data=array();
		$data_row = file_get_contents("php://input");
		$decoded= json_decode($data_row, true);

		if ($decoded)
		{
			if(!empty($decoded['outlet_id']) && !empty($decoded['category_id'])) 
			{
				if(isset($decoded['user_id']) && $decoded['user_id']!=""){
					$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
					$store_detail_data['count'] = (string)$usercartitemcount;
					$usercartitems = Cartitem::select('cart_item.outlet_id','cart_item.id','cart_item.product_id','cart_item.user_id',
							'cart_item.qty','product_inventories.discount_price')
							->leftJoin('products', 'products.id', '=', 'cart_item.product_id')	
							->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
							->where('cart_item.user_id',$decoded['user_id'])
							->get();

                            $total=0;
								foreach( $usercartitems as $data1)
								{
									
									$subtotal=$data1->qty * $data1->discount_price;
                                    
									$total+=$subtotal;
								}	
                                $store_detail_data['total']=number_format($total,2, '.', '');
				}else{
					$store_detail_data['count']='0';
					$store_detail_data['total']='0';
				}
				

				$outlet_id = $decoded['outlet_id'];
				
				$store_data = User::select('id')->where('id',$outlet_id)->where('users.status','1')->first();
				
			//echo '<pre>';print_r($store_data);exit;
			    if(!empty($decoded['user_id']))
				{   
					$UserFaviourite = DB::table('favorites')->where('user_id',$decoded['user_id'])->where('outlet_id',$decoded['outlet_id'])->pluck('outlet_id')->all();
					
					$store_detail_data['is_faviourte'] = !empty($UserFaviourite) ? 1 : 0 ;
				}
				//die;
				if(isset($store_data->id) && $store_data->id!="")
				{  
					$product_data = Product::select('products.id','products.cat_id','products.name as product_name','category.name as category_name','stores.name as store_name','stores.category_id','product_inventories.price'
					,'product_inventories.discount_price','product_inventories.stock','product_images.image')
					->leftJoin('stores', 'products.store_id', '=', 'stores.id')	
					->leftJoin('category', 'products.cat_id', '=', 'category.id')	
					->leftJoin('product_inventories', 'products.id', '=', 'product_inventories.product_id')	
					->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')	
					->where('products.outlet_id',$outlet_id)->whereNotNull('product_inventories.product_id')->where('products.status','1')->orderBy("products.id","ASC");
					
					if($decoded['category_id']!="")
					{
						$product_data=$product_data->where('products.cat_id',$decoded['category_id']);
					}
				
					$product_data=$product_data->paginate(10);
					//echo '<pre>'; print_r($product_data); die;
					//dd($product_data);

					$store_detail_data['product_count']=$product_data->count();
					if($product_data->count()>0)
					{
						foreach($product_data as $data)
						{
							$cart_is="no";
							$cart_qty='0';
							if(isset($decoded['user_id']) && $decoded['user_id']!="")
							{
								$itemcheckcart = Cartitem::where('user_id',$decoded['user_id'])
								->where('product_id',$data->id)->first();
								
								if(	$itemcheckcart )
								{
									$cart_is='yes';
									$cart_qty=$itemcheckcart->qty;
								}
							}
					        
							if(!empty($decoded['user_id']))
								{   
									$ProductFaviourite = DB::table('favorites')->where('user_id',$decoded['user_id'])->where('product_id',$data->id)->pluck('product_id')->all();
								}
							$store_product_data[] =  array('id' =>$data->id,'product_name' =>$data->product_name,'category_id' =>$data->cat_id,'category_name' =>$data->category_name,'image'=>!empty($data->image) ?  URL::to('/media/products').'/'.$data->image  : URL::to('/media/notfound-image.png'),'store_name'=> !empty($data->store_name) ? $data->store_name : ''
							,'orignal_price' =>$data->price,'discount_price' =>$data->discount_price,'offer'=>round((($data->price - $data->discount_price)*100) /$data->price).'%','cart_is'=>$cart_is,'cart_qty'=>$cart_qty,'is_faviourte' => !empty($ProductFaviourite) ? 1 : 0,'stock_status'=> $data->stock);
							
							$store_detail_data['products']=$store_product_data;
							
						}	
							$status = '1';
							$message='Products listed below.';	
					}else {
						$store_detail_data['products']=$store_product_data;
						$message = 'No product data found.';
					}
				}else {
					$message = 'No outlet found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
	   //return response()->json(['status'=>$status,'message'=>$message,'data'=>$store_detail_data], 200, [], JSON_UNESCAPED_SLASHES);
	   $response_data = array('status'=>$status,'message'=>$message,'data'=>$store_detail_data);
	   echo json_encode(array('response' => $response_data));
	   die;
   }
public function updateDevicetoken(Request $request){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		//$auth_token = $request->header('auth');
		
		    if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['device_id']) && !empty($decoded['device_type'])) 
					{     
						
							$id = $decoded['user_id'];
							$userdata = User::find($id);
						
						if(!empty($userdata))
						{		
							
									$userdata->device_id = $decoded['device_id'];
									$userdata->device_type = $decoded['device_type'];
									$userdata->save();
									$status = 1;
									$message = "Device token update successfully.";
						
									
						
							
						}else{
							$message = "User Not found";
						}
						
						 		/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
   public function signup() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	
			if($decoded) {
				if(!empty($decoded['country_code']) && !empty($decoded['mobile_number']) && !empty($decoded['email'])  && !empty($decoded['device_id']) && !empty($decoded['device_type']) && !empty($decoded['password'])) {
					//$decoded['password'] = bcrypt($decoded['password']);
					$decoded['mobile_number'] = str_replace("-","",$decoded['mobile_number']);
					$decoded['mobile_number'] = str_replace(" ","",$decoded['mobile_number']);
					$userArray = User::where('mobile','=',$decoded['mobile_number'])->where('country_code','=',$decoded['country_code'])->first();
					$emailArray = User::where('email','LIKE',$decoded['email'])->first();
				
					
					$r = 0;
					if($userArray){
						$r = 1;
					}
					if($emailArray){
						$r = 2;
					}
					if($decoded['invite_code']!="")
					{
						$invite_codeuser= User::where('uniq_id',$decoded['invite_code'])->first();
						if(empty($invite_codeuser)){
							$r = 3;
						}
						
					}
					if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
						$decoded['latitude'] = $decoded['latitude'];
					}else{
						$decoded['latitude'] = NULL;
					}
					if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
						$decoded['longitude'] = $decoded['longitude'];
					}else{
						$decoded['longitude'] = NULL;
					}
					if($r == 0){
						$user = new User();
						$otp = $this->random_digits(6);

						$user->uniq_id = uniqid();
						$user->type = 0;
						$user->invite_code =$decoded['invite_code'] ? $decoded['invite_code'] : '';
						$user->otp =$otp;
						$user->otp_verify ='no';
						$user->email =  $decoded['email'];
						$user->country_code =$decoded['country_code'];
						$user->mobile = $decoded['mobile_number'];
						$user->latitude = $decoded['latitude'] ? $decoded['latitude'] : '';
						$user->longitude = $decoded['longitude'] ? $decoded['longitude'] : '';
						$user->device_id = $decoded['device_id'];
						$user->device_type = $decoded['device_type'];
						$user->password =Hash::make($decoded['password']);
						$user->is_notification ='1';
						$user->status ='1';
						$user->save();
						$data['user_id'] = (string)$user->id;
						
						$data['email'] = $user->email;
						$data['country_code'] = $user->country_code;
						$data['mobile_number'] = $user->mobile;
						$data['otp'] = $user->otp;
						$data['latitude'] = $user->latitude;
						$data['longitude'] = $user->longitude;
						$data['is_notification'] = $user->is_notification;
						$data['address'] = '';
						$data['img'] = '';
						// if($userArray->profile_image &&  file_exists(public_path('/storage/profile_images/').$userArray->profile_image ))
						// {
						// 	$data['img'] = url('/').'/public/storage/profile_images/'.$userArray->profile_image;
						// }
						  $emailData = Emailtemplates::where('slug','=','user-registration')->first();
						  if($emailData){
							$textMessage = strip_tags($emailData->description);
							$user->subject = $emailData->subject;
							$activate_url =\App::make('url')->to("account-activate/".base64_encode($user->id));

							if($user->email!='')
							{
								$textMessage = str_replace(array('{USERNAME}','{URL}'), array($user->first_name,$activate_url),$textMessage);
								
								Mail::raw($textMessage, function ($messages) use ($user) {
									$to = $user->email;
									$messages->to($to)->subject($user->subject);
								});
							}
						}
						$data1 = $data;
						$status = 1;
						$message = "Registration Success. Please Verify your mobile and email.";
					}else if($r == 1){
						$message = "Mobile number already exists.";
					
					}else if($r == 3){
					$message = "Invalid Invite code.";
					}else{

						$message = "Email already exists.";
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}

public function login() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
		
			if($decoded) 
			{
				$userArray="";
				if(!empty($decoded['type']) && !empty($decoded['password']) && !empty($decoded['email'])  &&  !empty($decoded['device_id']) && !empty($decoded['device_type'])) 
				{
			
					
						if($decoded['type']=="email")
						{
								if(Auth::attempt(['email' => $decoded['email'], 'password' => $decoded['password']])){ 
									$userArray = Auth::user(); 
								} 
				 
					    
						}else{
							if(Auth::attempt(['country_code' => $decoded['country_code'],'mobile' => $decoded['email'], 'password' => $decoded['password']])){ 
								$userArray = Auth::user(); 
							} 
						}
						
					
					if($userArray){
						
					
						
						if ($userArray->id!="")
						{
							if($userArray->type == 0)
							{
							if($userArray->status == '0'){
								
								$message = "Your account has been deactivated.";
							}elseif($userArray->otp_verify == 'no' ){
								$message = "Please Verify Your Mobile.";
								$otp = $this->random_digits(6);
								$userArray->otp=$otp;
								$userArray->save();
								$data['id'] = $userArray->id;
								$data['otp'] = $userArray->otp;
								
							}
							else
							{
								$message = "You have logged in successfully.";
							}
							
								$userArray->device_id = $decoded['device_id'];
								$userArray->device_type = $decoded['device_type'];
								if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
									$userArray->latitude = $decoded['latitude'];
								}
								if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
									$userArray->longitude = $decoded['longitude'];
								}

								
								$userArray->save();
								
								$notification = DB::table('notification')->where('user_id',$userArray->id)->count();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name;
								$data['last_name'] = $userArray->last_name;
								$data['email'] = $userArray->email;
								
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								$data['notification_count'] = !empty($notification) ? $notification : 0;
								$mobile_verify="";
								if($userArray->otp_verify=='yes')
								{
									$mobile_verify='1';
								}else{
									$mobile_verify='0';
								}
								$data['is_mobile_verify'] = $mobile_verify;
								
								
								$data['address'] = $userArray->address ? $userArray->address : '';
								if($userArray->profile_pic &&  file_exists(public_path('/media/users/').$userArray->profile_pic ))
									{
										$data['image_url'] = url('/').'/media/users/'.$userArray->profile_pic;
									}else{
										$data['image_url'] ='';
									}
							
								$data1 = $data;
								$status = 1;
						
							}else{
								$message = "You are Invalid User. Please try again.";
							}	
						}else{
							$message = "Invalid login credentials. Please try again.";
						}
					}else{
						$message = "Invalid login credentials. Please try again.";
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}
public function verify_otp() {
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$data1 = (object) array();
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	//$decoded        = $_REQUEST;
	
			if($decoded) {
				if(!empty($decoded['user_id']) && !empty($decoded['otp'])) {
					
					$userArray = User::where('id','=',$decoded['user_id'])->first();
					$r = 0;
					if($userArray){
						if($userArray->status == '0'){
							$is_deactivate = '1';
							$message = "Your account has been deactivated.";
							$r = 1;
						}
					}
					if($r == 0){
						$user_id = $decoded['user_id'];
						$check_otp = User::where('id','=',$user_id)->where('otp','=',$decoded['otp'])->first();
						if($check_otp)
						{   
							$check_otp->otp="";
							$check_otp->otp_verify="yes";
							$check_otp->save();
							if($userArray){
								$status = 1;
								
								
								if(isset($decoded['latitude']) && $decoded['latitude'] != ''){
									$userArray->latitude = $decoded['latitude'];
								}
								if(isset($decoded['longitude']) && $decoded['longitude'] != ''){
									$userArray->longitude = $decoded['longitude'];
								}
								if(isset($decoded['device_id']) && $decoded['device_id'] != ''){
									$userArray->device_id = $decoded['device_id'];
								}
								if(isset($decoded['device_type']) && $decoded['device_type'] != ''){
									$userArray->device_type = $decoded['device_type'];
								}
								$userArray->save();
								$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name ? $userArray->first_name : '';
								$data['last_name'] = $userArray->last_name ? $userArray->last_name : '';
								$data['email'] = $userArray->email;
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								// $data['img'] = '';
								// if($userArray->profile_image &&  file_exists(public_path('/storage/profile_images/').$userArray->profile_image ))
								// {
								// 	$data['img'] = url('/').'/public/storage/profile_images/'.$userArray->profile_image;
								// }
								$data['address'] = $userArray->address ? $userArray->address : '';
								$data1 = $data;
							}else{
								$status = 1;
								$data['user_id'] = '';
								$data['role_id'] = '';
								$data['first_name'] = '';
								$data['last_name'] = '';
								$data['email'] = '';
								$data['country_code'] = '';
								$data['mobile_number'] = '';
								$data['remember_token'] = '';
								$data['latitude'] = '';
								$data['longitude'] = '';
								$data['img'] = '';
								$data['driving_license'] = '';
								$data['id_proof'] = '';
								$data['comments'] = '';
								$data['address'] = '';
								$data['assigned_service'] = '';
								$data['assigned_location'] = '';
								$data1 = $data;
							}
							$message = "OTP has been verified successfully.Your account has been created successfully.";
						}else{
							$message = "Invalid OTP";
						}
					}
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
	$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
	echo json_encode(array('response' => $response_data));
	die;
}
	function forgotpassword(Request $request)

	{
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
		$data1 = array();
		$is_deactivate = '0';
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

			if($decoded) 
			{
				if(!empty($decoded['email'])) {
					
					$userArray = User::where('email','=',$decoded['email'])->first();
					if($userArray)
					{
						$status = 1;
						$length = 10;
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$charactersLength = strlen($characters);
						$randomString = '';

						for ($i = 0; $i < $length; $i++) 
						{$randomString .= $characters[rand(0, $charactersLength - 1)];}
						$url = $randomString;
						$cur_date = date("Y-m-d H:i:s");
						$userArray->forgot_url = $url;
						$userArray->forgot_time = $cur_date;
						$userArray->save();
						$create_url = \App::make('url')->to('/create-password')."/".$url;

						$emailData = Emailtemplates::where('slug','=','user-forgot-password')->first();
						if($emailData){
						  $textMessage = strip_tags($emailData->description);
						  $userArray->subject = $emailData->subject;
						  if($userArray->email!='')
						  {
							  $textMessage = str_replace(array('{NAME}','{URL}'), array($userArray->first_name,$create_url),$textMessage);
							  
							  Mail::raw($textMessage, function ($messages) use ($userArray) {
								  $to = $userArray->email;
								  $messages->to($to)->subject($userArray->subject);
							  });
						  }
					  }
						
						
						$message = "Forgot password instruction has been successfully sent on your email.";
								
					}else {
					$message = 'Please enter register email id.';
				    }		
						
					
				}else {
					$message = 'One or more required fields are missing. Please try again.';
				}
			}else {
				$message = 'Opps! Something went wrong. Please try again.';
			}
		
			$response_data = array('status'=>$status,'message'=>$message);
			echo json_encode(array('response' => $response_data));
			die;
	}
	public function resend_otp() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	$data1 = (object) array();
		$is_deactivate = '0';
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['country_code']) && !empty($decoded['mobile_number'])) {
						$decoded['mobile_number'] = str_replace("-","",$decoded['mobile_number']);
						$decoded['mobile_number'] = str_replace(" ","",$decoded['mobile_number']);
						$userArray = User::where('mobile','=',$decoded['mobile_number'])->where('country_code','=',$decoded['country_code'])->first();
						//pr($userArray);die;
						$r = 0;
						if($userArray){
							if($userArray->status == '0'){
								$is_deactivate = '1';
								$message = "Your account has been deactivated.";
								$r = 1;
							}
						}else{
							$message = "Please enter your registered mobile.";
							$r = 2;
						}
						if($r == 0){
							$code_number = $decoded['country_code'].$decoded['mobile_number'];
							$otp = $this->random_digits(6);
							// $is_send = $this->send_sms($otp,$code_number);
							// if($is_send){
								$status = 1;
								$message = "OTP has been sent to your mobile number.";
								$userArray->otp=$otp;
								$userArray->save();
								$data['otp'] = $otp;
								$data['country_code'] = $decoded['country_code'];
								$data['mobile_number'] = $decoded['mobile_number'];
								$data1=$data;
							// }else{
							// 	$message = 'Opps! Something went wrong. Please try again.';
							// }
						}
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
		        }else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1,'is_deactivate'=>$is_deactivate);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function userlocationsave() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    $data1 = (object) array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id']) && !empty($decoded['type'])) 
					{
						//$decoded['password'] = bcrypt($decoded['password']);
						$r=0;
						
						if($decoded['type']=='add'){
							$Userlocation = new Userlocations();
						}else{
							if($decoded['id']==""){
								$message="Id required ";
								$r=1;
							}else{
								$Userlocation = Userlocations::where('id','=',$decoded['id'])->first();
							}
							
						}
						if($r==0){
							$Userlocation->user_id =$decoded['user_id'] ? $decoded['user_id'] : '';
							$Userlocation->address =$decoded['address'] ? $decoded['address'] : '';
							$Userlocation->complete_address =$decoded['complete_address'] ? $decoded['complete_address'] : '';
							$Userlocation->lng =  $decoded['lng'] ? $decoded['lng'] : '';
							$Userlocation->address_type =$decoded['address_type'] ? $decoded['address_type'] : '';
							$Userlocation->lat = $decoded['lat'] ? $decoded['lat'] : '';
							//$Userlocation->city = $decoded['city'] ? $decoded['city'] : '';
							//$Userlocation->country = $decoded['country'] ? $decoded['country'] : '';
							$Userlocation->save();

							$data['user_id'] = (string)$Userlocation->id;
							$data['address'] = (string)$Userlocation->address;
							$data['complete_address'] = (string)$Userlocation->complete_address;
							$data['lng'] = (string)$Userlocation->lng;
							$data['address_type'] = (string)$Userlocation->address_type;
							$data['lat'] = (string)$Userlocation->lat;
							$data1=$data;
							$status = 1;
							$message = "Your location save successfully.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function userlocationslist(){ //echo 'asfd'; die;
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded){
					if(!empty($decoded['user_id'])) 
					{
						//$decoded['password'] = bcrypt($decoded['password']);
						
						$userArray = Userlocations::where('user_id','=',$decoded['user_id'])->orderBy("created_at","DESC")->get();
						//dd($userArray);
					if(!empty($userArray)){
					foreach($userArray as $data1){
						$data[] = array('id' => $data1->id,'user_id' => $data1->user_id,
						'address' =>$data1->address,
						'complete_address' =>$data1->complete_address,
						'lat'=>$data1->lat,
						'lng'=>$data1->lng,
						'address_type'=>$data1->address_type,
						//'city'=>$data1->city,
						//'country'=>$data1->country,
						);
						
					
					} 
							$status = 1;
							
					}else{
						$message = "No address found, Please add address.";
					}
					
							
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
		echo json_encode(array('response' => $response_data));
		die;
	}
	
	
	
		public function favouritestorelist(){
			
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		if ($decoded)
		{
			if(!empty($decoded['lat']) && !empty($decoded['long']) && !empty($decoded['user_id'])) 
			{
				$lat = $decoded['lat'];
				$long = $decoded['long'];
				$store_data = Favorites::select('favorites.store_id','stores.*')
							->leftJoin('stores', 'favorites.store_id', '=', 'stores.id')
							->where('favorites.user_id',$decoded['user_id'])
							->where('favorites.entity_type',1)
							->groupBy('favorites.store_id')
							->paginate(10);
				$store_count_data = Favorites::select('favorites.store_id','stores.*')
							->leftJoin('stores', 'favorites.store_id', '=', 'stores.id')
							->where('favorites.user_id',$decoded['user_id'])
							->where('favorites.entity_type',1)
							->groupBy('favorites.store_id')
							->count();	
					if($store_data->count()>0)
				{
					$home_data['store_count']=$store_count_data;
					foreach($store_data as $data)
					{
						$store_data = Stores::select('stores.id','stores.category_id')->where('stores.id',$data->id)->first();
						$store_cat_id=explode(',',$store_data->category_id);

					  $product_category_id = Category::select('category.id','category.name','category.image')->WhereIn('category.id',$store_cat_id)->where('category.status','1')->get();
					  $product_category_count = Category::select('category.id','category.name','category.image')->WhereIn('category.id',$store_cat_id)->where('category.status','1')->count();
					 // dd($product_category_id);
					 $product_category_data=array();
					 foreach($product_category_id as $data1)
					{
						$product_category_data[]=array('id' =>$data1->id,'name' =>$data1->name,'image'=>URL::to('/media/category').'/'.$data1->image);
					}
					if(isset($decoded['user_id']) && $decoded['user_id']!=""){
						$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
						$home_data['count'] = (string)$usercartitemcount;
					}else{
						$home_data['count']='0';
					}
					

					//dd($product_category_data);
						$helper=new Helpers;
						if($data->lat!="" && $data->lng!=""){
							$distance= $helper->distance($lat,$long,$data->lat,$data->lng,'K');
						}else{
							$distance=0;
						}
						
						$home_store_data[] =  array('id' =>$data->id,'name' =>$data->name,'image'=>URL::to('/media/store').'/'.$data->image,'distance'=>number_format($distance,2).' km'
						,'rating'=>(string)$data->rating,'is_open'=>$data->is_open,'offer_code'=>$data->offer_code,'offer'=>$data->offer_per.'%','product_count'=>$product_category_count,'address'=> !empty($data->address) ? $data->address :'','product_category'=>$product_category_data);
						$home_data['store']=$home_store_data;
					}	
						$status = '1';
						$message='Stores listed below.';	
				}else {
					$home_data['store'] = [];
					$message = 'No store data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	    echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
       die();
	}
     
	 public function favouriteProduct(){
			
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		if ($decoded)
		{
			if(!empty($decoded['user_id']) && !empty($decoded['store_id'])) 
			{
				
				$product_data = Favorites::select('favorites.product_id','products.*')
							->leftJoin('products', 'favorites.product_id', '=', 'products.id')
							->where('favorites.user_id',$decoded['user_id'])
							->where('favorites.store_id',$decoded['store_id'])
							->where('favorites.entity_type',1)
							->paginate(10);
				$product_count_data = Favorites::select('favorites.product_id','products.*')
							->leftJoin('products', 'favorites.product_id', '=', 'products.id')
							->where('favorites.user_id',$decoded['user_id'])
							->where('favorites.store_id',$decoded['store_id'])
							->where('favorites.entity_type',1)
							->count();			
				
				if($product_data->count()>0)
				{
					$home_data['product_count']=$product_count_data;
					foreach($product_data as $data)
					{
									$prd_img_data = Productimages::where('product_id',$data->product_id)->first();
									$image="";
									if(isset($prd_img_data) && $prd_img_data->image!=""){
										$image=URL::to('/media/products').'/'.$prd_img_data->image;
									}
									$prd_invt_data = Productinventory::where('product_id',$data->product_id)->first();
									
									$prd_cat = Category::where('id',$data->cat_id)->first();
									
									$cart_itme = Cartitem::where('product_id',$data->product_id)->where('user_id',$decoded['user_id'])->where('store_id',$decoded['store_id'])->first();
							 $cart_qty='0';
							if(	$cart_itme )
								{
								///$cart_is='yes';
									$cart_qty=$cart_itme->qty;
								}

					//dd($product_category_data);
						$helper=new Helpers;
						if($data->lat!="" && $data->lng!=""){
							$distance= $helper->distance($lat,$long,$data->lat,$data->lng,'K');
						}else{
							$distance=0;
						}
						$subtotal=$prd_invt_data->qty * $data->discount_price;   
						$home_product_data[] =  array(
												'product_id' =>$data->id,
												'product_name' =>$data->name,
												'image'=>$image,
												'sku'=>!empty($data->sku) ? $data->sku : '',
												'rating'=> !empty($data->rating) ? $data->rating : '',
												'offer'=>round((($prd_invt_data->price - $prd_invt_data->discount_price)*100) / $prd_invt_data->price).'%',
												'description'=>!empty($data->description) ? $data->description : '',
												'orignal_price'=>!empty($prd_invt_data->price) ? $prd_invt_data->price : '',
												'discount_price'=>!empty($prd_invt_data->discount_price) ? $prd_invt_data->discount_price : '',
												'weight'=>!empty($prd_invt_data->weight) ? $prd_invt_data->weight : '',
												'weight_unit'=>!empty($prd_invt_data->weight_unit) ? $prd_invt_data->weight_unit : '',
												'color'=>!empty($prd_invt_data->color) ? $prd_invt_data->color : '',
												'cart_qty'=>$cart_qty,
												'cart_is' => !empty($cart_itme) ? "yes" : "no", 
												'subtotal'=>number_format($subtotal,2, '.', ''),
												'category'=>!empty($prd_cat->name) ? $prd_cat->name : '',
												'stock_status'=>$prd_invt_data->stock
												);
						$home_data['product']=$home_product_data;
					}	
						$status = '1';
						$message='Product listed below.';	
				}else {
					$home_data['store'] = [];
					$message = 'No store data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	    echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
       die();
	}
	
	
	public function notificationList(){
			
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$home_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		if($decoded)
		{
			if(!empty($decoded['user_id'])) 
			{
				
				$notification_data = Notification::where('user_id',$decoded['user_id'])
							->paginate(10);
				$notification_count_data = Notification::where('user_id',$decoded['user_id'])
							->count();			
				
				if($notification_data->count()>0)
				{
					$home_data['motification_count']=$notification_count_data;
					foreach($notification_data as $data)
					{
						$home_product_data[] =  array(
												'id' =>$data->id,
												'noti_type' => !empty($data->noti_type) ? $data->noti_type : '',
												'user_type'=>!empty($data->user_type) ? $data->user_type : '',
												'notification'=> !empty($data->notification) ? $data->notification : '',
												'user_id'=>$decoded['user_id'],
												'created_at'=> date("d/m/Y h:i:s A",strtotime($data->created_at)),
												'is_read'=> $data->is_read 
												
												);
						$home_data['notification']=$home_product_data;
					}	
						$status = '1';
						$message='Notification listed below.';	
				}else{
					$home_data['notification'] = [];
					$message = 'No Notification data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$home_data);
	    echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
       die();
	}
	 
	 public function pages(){
			
		header('Content-Type: application/json');
		$status = '0';
		$message = NULL;
		$home_store_data=array();
		$page_data=array();
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);

		if($decoded)
		{
			if(!empty($decoded['slug'])) 
			{
				
				$pages_data = Content::where('slug',$decoded['slug'])->where('status','1')
							->first();
						
				//echo $pages_data->description; die;
				if($pages_data)
				{
					$page_data =  array(												'id' =>$pages_data->id,
												'title' => !empty($pages_data->title) ? $pages_data->title : '',
												'slug'=>!empty($pages_data->slug) ? $pages_data->slug : '',
												'description'=> !empty($pages_data->description) ? $pages_data->description : '',
												'created_at'=> date("d/m/Y h:i:s A",strtotime($pages_data->created_at))
												);
						//$page_data['page']=$home_product_data;
						
						$status = '1';
						$message='Pages data below.';	
				}else{
					//$page_data =;
					$message = 'No page data found.';
				}
			}else {
				$message = 'One or more required fields are missing. Please try again.';
			}
				
		}else {
			$message = 'Opps! Something went wrong. Please try again.';
		}
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$page_data);
	    echo json_encode(array('response' => $response_data));
	  // return response()->json(['status'=>$status,'message'=>$message,'data'=>$home_data], 200, [], JSON_UNESCAPED_SLASHES);
       die();
	}
	public function userlocationsdelete() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
		$user_locations = array();
	
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id']) && !empty($decoded['id']) ) 
					{
						
						
						$userArray = Userlocations::where('user_id','=',$decoded['user_id'])->where('id','=',$decoded['id'])->first();
						
						if($userArray){
							$userArray->delete();
							$status = 1;
							$message = "Locations Deleted Successfully.";
						}else{
							
							$message = "Record Not Found.";
						}
						
					
							
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	}
	
public function favourite(){
	header('Content-Type: application/json');
	$status = 0;
	$message = NULL;
	$data = array();
	$fav = array();
	$data1 = (object) array();
	
	$is_deactivate = '0';
	$sflag = '0';
	$data_row 		= 	file_get_contents("php://input");
	$decoded 	    = 	json_decode($data_row, true);
	 
   	$user_id     =  $decoded['user_id'];
	$product_id  =  $decoded['product_id'];
	//$action_type =  $decoded['action_type'];
	$store_id    =  !empty($decoded['store_id']) ? $decoded['store_id'] : '';
	$entity_type =  $decoded['entity_type'];
	 
		if(!empty($product_id))
		{
			$UserFaviourite = DB::table('favorites')->where('user_id',$user_id)->where('product_id',$product_id)->first();	 
	    } else {
			
			$UserFaviourite = DB::table('favorites')->where('user_id',$user_id)->where('store_id',$store_id)->first();	
		}
		if(empty($UserFaviourite)){
			if($decoded['favouriteStatus'] == 1){
			       $obj 			 	=  new Favorites;
				   $obj->user_id		=  $user_id ;
				   $obj->product_id	 	=  !empty($product_id) ? $product_id : '';
				   $obj->store_id	 	=  !empty($store_id) ? $store_id : '';
				   $obj->entity_type	=  $entity_type ;
				  //$obj->action_type	=  $action_type;
				   $obj->status			=  1;
				   $obj->save();
			       
				   $status = 1;
				   $fav['favouriteStatus'] = 0;
				   if(!empty($product_id))
					{
						$message = 'Product added favourite';
					} else {
							$message = 'Store added favourite';
					}
				}	
		} else {
			
			   if($decoded['favouriteStatus'] == 0){
			       if(!empty($product_id))
						{
							 DB::table('favorites')->where('user_id',$user_id)->where('product_id',$product_id)->delete();	 
						} else {
							
							DB::table('favorites')->where('user_id',$user_id)->where('store_id',$store_id)->delete();	
						}
						
					$status = 1;
					$fav['favouriteStatus'] = 1;
				   if(!empty($product_id))
					{
						$message = 'Product remove favourite';
					} else {
							$message = 'Store remove favourite';
					}	
				}
		}	
		$response_data = array('status'=>$status,'data'=>$fav, 'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	} 

	public function AddToCart() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    $data1 = (object) array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id']) && !empty($decoded['product_id']) && !empty($decoded['qty']) && !empty($decoded['price']) &&  !empty($decoded['store_id'])&&  !empty($decoded['outlet_id'])) 
					{
						$r=0;
						if($r==0){
							$checkcartitem = Cartitem::where('user_id',$decoded['user_id'])->where('product_id',$decoded['product_id'])->where('store_id',$decoded['store_id'])->where('outlet_id',$decoded['outlet_id'])->first();
							//dd($checkcartitem);
							//echo 1; exit;
							if(!empty($checkcartitem))
							{
								if($decoded['type']=="add")
								{
									$checkcartitem->qty=$checkcartitem->qty+$decoded['qty'];
								}else{
									
									$checkcartitem->qty=$decoded['qty'];
								}
								
								$checkcartitem->save();

							}else
							{
								//$checkcartitem1 = Cartitem::where('user_id',$decoded['user_id'])->where('store_id','!=',$decoded['store_id'])->first();


								$itemdel = Cartitem::whereUser_id($decoded['user_id'])->where('store_id','!=',$decoded['store_id'])->where('outlet_id','!=',$decoded['outlet_id']);
								if(	$itemdel )
								{
									$itemdel->delete();
								}

								$addcart = new Cartitem;
								$addcart->user_id =$decoded['user_id'] ? $decoded['user_id'] : '';
								$addcart->store_id =$decoded['store_id'] ? $decoded['store_id'] : '';
								$addcart->outlet_id =$decoded['outlet_id'] ? $decoded['outlet_id'] : '';
								$addcart->product_id =$decoded['product_id'] ? $decoded['product_id'] : '';
								$addcart->qty =  $decoded['qty'] ? $decoded['qty'] : '';
								$addcart->price =$decoded['price'] ? $decoded['price'] : '';
								$addcart->save();
							}
							
							$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
							$usercartitems = Cartitem::select('cart_item.store_id','cart_item.outlet_id','cart_item.id','cart_item.product_id','cart_item.user_id',
							'cart_item.qty','product_inventories.discount_price')
							->leftJoin('products', 'products.id', '=', 'cart_item.product_id')	
							->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
							->where('cart_item.user_id',$decoded['user_id'])
							->get();

                            $total=0;
								foreach( $usercartitems as $data1)
								{
									
									$subtotal=$data1->qty * $data1->discount_price;
                                    
									$total+=$subtotal;
								}	
                                $data['total']=number_format($total,2, '.', '');
							$data['count'] = (string)$usercartitemcount;
						    $data1=$data;
							$status = 1;
							$message = "Your Cart Item save successfully.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function CartDetail() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	   
	
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id']) ) 
					{
						
							// $addcart = new Cartitem;
							// $addcart->user_id =$decoded['user_id'] ? $decoded['user_id'] : '';
							// $addcart->product_id =$decoded['product_id'] ? $decoded['product_id'] : '';
							// $addcart->price =$decoded['price'] ? $decoded['price'] : '';
							// $addcart->save();
							$usercartitems = Cartitem::select('cart_item.store_id','cart_item.outlet_id','cart_item.id','cart_item.product_id','cart_item.user_id',
							'cart_item.qty','cart_item.price','products.name','products.name','product_inventories.price as product_price',
							'product_inventories.discount_price','product_inventories.weight',
							'category.name as cat_name','stores.name as storename')
							->leftJoin('products', 'products.id', '=', 'cart_item.product_id')	
							->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
							->leftJoin('category', 'products.cat_id', '=', 'category.id')	
							->leftJoin('stores', 'cart_item.store_id', '=', 'stores.id')
							->where('cart_item.user_id',$decoded['user_id'])
							->get();
							//dd($usercartitems);
							$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
							$data['count'] = (string)$usercartitemcount;
							if($usercartitemcount==0)
							{
								$message = "Your cart is empty.";
							}else
							{
								$total=0;
								foreach( $usercartitems as $data1)
								{
									$prd_img_data = Productimages::where('product_id',$data1->product_id)->first();
									$image="";
									if(isset($prd_img_data) && $prd_img_data->image!=""){
										$image=URL::to('/media/products').'/'.$prd_img_data->image;
									}
									
									//dd($prd_img_data);
									$subtotal=$data1->qty * $data1->discount_price;
									$items[] = array('id' => $data1->id,
									'user_id' => $data1->user_id,
									'store_id' => $data1->store_id,
									'outlet_id' => $data1->outlet_id,
									'store_name' => $data1->storename,
									'product_id' =>$data1->product_id,
									'product_name' =>$data1->name,
									'weight' =>!empty($data1->weight) ? $data1->weight : '',
									'offer'=>round((($data1->product_price - $data1->discount_price)*100) / $data1->product_price).'%',
									'product_image'=>$image,
									'qty'=>$data1->qty,
									'orignal_price'=>$data1->product_price,
									'discount_price'=>$data1->discount_price,
									'subtotal'=>number_format($subtotal,2, '.', ''),
									'category'=>$data1->cat_name,
									
									);
									$data['items']=$items;
									$total+=$subtotal;
								}	$data['total']=number_format($total,2, '.', '');
								
								   
									$status = 1;
									$message = "Cart Item List Below.";
						}
								
						
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function DeleteCart() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	//	$items=(object) array();
	
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id']) && !empty($decoded['product_id']) ) 
					{
						$cartitem = Cartitem::where('user_id',$decoded['user_id'])->where('product_id',$decoded['product_id'])->first();
						
						if($cartitem)
						{
							$cartitem->delete();
							$usercartitems = Cartitem::select('cart_item.id','cart_item.product_id','cart_item.user_id',
							'cart_item.qty','cart_item.price','products.name','products.name','product_inventories.price as product_price',
							'product_inventories.discount_price','category.name as cat_name')
							->leftJoin('products', 'products.id', '=', 'cart_item.product_id')	
							->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
							->leftJoin('category', 'products.cat_id', '=', 'category.id')	
							->where('user_id',$decoded['user_id'])
							->get();
							//dd($usercartitems);
							$usercartitemcount = Cartitem::where('user_id',$decoded['user_id'])->count();
							
							if($usercartitemcount>0)
							{
								$data['count'] = (string)$usercartitemcount;
								foreach( $usercartitems as $data1)
									{
										$prd_img_data = Productimages::where('product_id',$data1->product_id)->first();
										$image="";
										if(isset($prd_img_data) && $prd_img_data->image!=""){
											$image=URL::to('/media/products').'/'.$prd_img_data->image;
										}
										
										//dd($prd_img_data);
										$subtotal=$data1->qty * $data1->discount_price;
										$items[] = array('id' => $data1->id,
										////'store_id' => $data1->store_id,
										//'store_name' => $data1->storename,
										'user_id' => $data1->user_id,
										'product_id' =>$data1->product_id,
										'product_name' =>$data1->name,
										'product_image'=>$image,
										'qty'=>$data1->qty,
										//'weight' =>$data1->weight,
										'offer'=>round((($data1->product_price - $data1->discount_price)*100) / $data1->product_price).'%',
										'orignal_price'=>$data1->product_price,
										'discount_price'=>$data1->discount_price,
										'subtotal'=>$subtotal,
										'category'=>$data1->cat_name,
										
										);
										$data['items']=$items;
									
									}
							}else{
								$data['items']=array();
							}
							$usercartitems = Cartitem::select('cart_item.store_id','cart_item.outlet_id','cart_item.id','cart_item.product_id','cart_item.user_id',
							'cart_item.qty','product_inventories.discount_price')
							->leftJoin('products', 'products.id', '=', 'cart_item.product_id')	
							->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
							->where('cart_item.user_id',$decoded['user_id'])
							->get();

                            $total=0;
								foreach( $usercartitems as $data1)
								{
									
									$subtotal=$data1->qty * $data1->discount_price;
                                    
									$total+=$subtotal;
								}	
                                $data['total']=number_format($total,2, '.', '');

									$status = 1;
									$message = "Cart item deleted.";

						}else{
							$message = 'No record found.';
						}	
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
		echo json_encode(array('response' => $response_data));
		die;
	}

	public function CheckOut() { 
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    $data1 = (object) array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['delivery_type']) && !empty($decoded['payment_mode']) && !empty($decoded['user_id']) && !empty($decoded['store_id']) && !empty($decoded['outlet_id']) && !empty($decoded['address']) ) 
					{
						$r=0;
						$checkcartitem = Cartitem::where('user_id',$decoded['user_id'])->first();
						//dd($checkcartitem);
						if(empty($checkcartitem) || $checkcartitem=="")
						{$r=1;
							
							$message = "Your Cart Item Empty.";
						}
						if($r==0)
						{ 
								$cartitem = Cartitem::where('user_id',$decoded['user_id'])->get();
								$c="";
								$item_prd_ids="";
								$total_amount ="0";
								$total_quantity='0';
								foreach($cartitem as $item)
								{
									$item_prd_ids.=$c.$item->product_id;


									if (is_numeric($item->qty) && is_numeric($item->price)) {
										$total_amount +=($item->qty*$item->price);
									  }
									  $total_quantity+=$item->qty;

									$c=",";

								}
								//echo $total_amount;exit;
								$store_detail = Stores::where('id',$decoded['store_id'])->first();
									
								$orders_save = new Orders;
								if(!empty($decoded['couponcode_id']))
								{
								  $couponcode = CouponCode::find($decoded['couponcode_id']);
								  $last_uses = $couponcode->max_use - 1;
								  $couponcode->max_use =  $last_uses;
								  $couponcode->save();
								}
								
								
								$orders_save->user_id =$decoded['user_id'] ? $decoded['user_id'] : '';
								$orders_save->store_id =$decoded['store_id'] ? $decoded['store_id'] : '';
								$orders_save->zone_id =$decoded['zone_id'] ? $decoded['zone_id'] : '';
								$orders_save->vendor_id =$store_detail->user_id;
								
								$orders_save->outlet_id =$decoded['outlet_id'] ? $decoded['outlet_id'] : '';
								$orders_save->order_latitude =$decoded['latitude'] ? $decoded['latitude'] : '';
								$orders_save->order_longitude =$decoded['longitude'] ? $decoded['longitude'] : '';

								$orders_save->product_id =$item_prd_ids;
								$orders_save->total_amount =$total_amount;
								$orders_save->net_amount =$total_amount+$decoded['shipping_amount'];
								$orders_save->total_quantity =$total_quantity;

								$orders_save->payment_mode =  $decoded['payment_mode'] ? $decoded['payment_mode'] : '';
								$orders_save->total_shipping_amount  =$decoded['shipping_amount'] ? $decoded['shipping_amount'] : '';
								$orders_save->instructions  =$decoded['instructions'] ? $decoded['instructions'] : '';

								$orders_save->order_address  =$decoded['address'] ? $decoded['address'] : '';
								$orders_save->coupon_code =$decoded['coupon_code'] ? $decoded['coupon_code'] : '';
								if($decoded['delivery_type']=='now')
								{
									$orders_save->delivery_type ='now';
								}else{
									$orders_save->delivery_type ='schedule';
									$orders_save->delivery_time  =$decoded['delivery_time'];

								}

								$orders_save->save();
								if($orders_save->id)
								{
									
									foreach($cartitem as $item)
										{
											$orders_items_save = new Orderitems;
											$orders_items_save->order_id=$orders_save->id;
											$orders_items_save->user_id =$orders_save->user_id;
											$orders_items_save->vendor_id =$orders_save->vendor_id;
											$orders_items_save->store_id=$orders_save->store_id ;
											$orders_items_save->product_id =$item->product_id;
											
											$orders_items_save->quantity =$item->qty;
											$orders_items_save->price =$item->price;
											$orders_items_save->subtotal =$item->qty*$item->price;

											$orders_items_save->coupon_code =$decoded['coupon_code'] ? $decoded['coupon_code'] : '';

											$orders_items_save->save();

							
											$product_qty_update = Productinventory::where('product_id',$item->product_id)->first();
											$product_qty_update->qty=$product_qty_update->qty-$item->qty;
											$product_qty_update->save();

											$cartitemdelete = Cartitem::where('user_id',$decoded['user_id'])->where('product_id',$item->product_id)->first();
											$cartitemdelete->delete();

										}
								
									

								}
								$userstoken = User::where('status','1')->where('is_notification','1')->where('type',3)->where('is_online',1)->where('vendor_id',$store_detail->user_id)->where('order_status',0)->where('outlet_id',$decoded['outlet_id'])->where('order_process_status',0)->select('device_id', DB::raw('CONCAT(first_Name, " ", last_Name) AS full_name'))->whereNotNull('auth_token')->pluck('device_id')->all();
									//$tokenList = $userstoken;
									//echo '<pre>'; print_r($userstoken); die;
									$tokenList = array();
									foreach($userstoken as $token)
									{   if(!empty($token)){
											$tokenList[] = $token;
										}
									}
									$customers = DB::table('users')->where('id',$decoded['user_id'])->first();
									$customersname =   $customers->first_name.' '.$customers->last_name;
									$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
									//echo '<pre>'; print_r($tokenList); die;			
										$notification = [
											'title' => 'Bringoo',
											'body' =>'New Request Received',
											//'icon' => $imageUrl,
											'sound' => 'mySound',
										];
										
										$extraNotificationData = ["order_id" => $orders_save->id,"notification_type" =>'new_request','user_name'=>$customersname,'delviery_time'=>$orders_save->delivery_time];
										$fcmNotification = [
											'registration_ids' => $tokenList, //multple token array
											//'to'        => $token, //single token
											'notification' => $notification,
											'data' => $extraNotificationData
										];
								
										$headers = ['Authorization:key=AAAAT71gU2E:APA91bHCZ3D-gIXICfe8IcT1sCcG4Yp0-Ydz3pkVH8NFA_cyj8tFHEhFGrFqJHGzFSrsMT4Ka8fza7M3ahF8cixHdzyg-_im07pzyzpDe9ffHVkQJHYSF1Rd9BtNpN3iUUvu42iuZCAj',
											'Content-Type: application/json'
										];
						
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_URL,$fcmUrl);
										curl_setopt($ch, CURLOPT_POST, true);
										curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
										curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
										$result = curl_exec($ch);
										curl_close($ch);
										$result = json_decode($result, true);
										//echo $result['success']; die;
										if($result['success']){
											
											$userdata = User::where('status','1')->where('order_process_status',0)->where('type',3)->where('is_notification','1')->where('is_online',1)->where('outlet_id',$decoded['outlet_id'])->where('vendor_id',$store_detail->user_id)->where('order_status',0)->select('device_id', DB::raw('CONCAT(first_Name, " ", last_Name) AS full_name'),'id')->whereNotNull('auth_token')->get();
											
											        foreach($userdata as $udata)
													{
														$notification1 = new Notification;
														
														$notification1->noti_type = "Bringoo";
														$notification1->notification = "New Request Received";
														//$notification->user_type = "order complete";
														$notification1->user_id = $udata->id;
														$notification1->is_read = 0;
														$notification1->save();
													}
											
											
														$notification = new Notification;
														
														$notification->noti_type = "order complete";
														$notification->notification = "your order is complete ";
														//$notification->user_type = "order complete";
														$notification->user_id = $decoded['user_id'] ? $decoded['user_id'] : '';
														$notification->is_read = 0;
														$notification->save();
								        }	
						
							$status = 1;
							//echo $orders_save->created_at; exit;
							$message = "Thank You Your Order is Confirm.";
							$data['order_id']=$orders_save->id;
							$data['store_name']=$store_detail ->name;
							$data['delevery_time']="45 mins";
							$data['total_amount']=$total_amount+$decoded['shipping_amount'];
							
							$data['order_date']=date("d/m/Y h:i:s A",strtotime($orders_save->created_at));
							
							$data1=$data;
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}


	public function OrderHistory() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data1 = array();
	   
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['user_id'])) 
					{
						$r=0;
						
							$orderdata = Orders::select('orders.id')
							->where('orders.user_id',$decoded['user_id'])->first();
							//dd($orderdata);
							if(empty($orderdata) || $orderdata=="")
							{ 
								$r=1;
								$message = "No Order Found.";
							}
							if($r==0)
							{
								$orderdata = Orders::select('orders.id','orders.order_address',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name',
								'stores.image','rating.total_rating')
								->where('orders.user_id',$decoded['user_id'])
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->orderBy("orders.created_at","DESC")	
								->get();
								foreach($orderdata as $data)
								{
									$product_ids=explode(',',$data->product_id);
									//count($product_ids);
									$var="";
									$items="";
									for($i=0;$i<count($product_ids);$i++)
									{
										$orderdata = Product::select('products.name')
										->where('id',$product_ids[$i])
										->first();
										$items.=$var.$orderdata->name;
										$var=",";
									}
									
									$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','category.name as cat_name')
									->where('order_items.order_id',$data->id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();
									$product_data=array();
									foreach($orderitems as $dat)
									{
											$prd_img_data = Productimages::where('product_id',$dat->product_id)->first();
											$image="";
											if(isset($prd_img_data) && $prd_img_data->image!=""){
												$image=URL::to('/media/products').'/'.$prd_img_data->image;
											}
											
											//dd($prd_img_data);
											$subtotal=$dat->quantity * $dat->price;
											$product_data[] = array('id' => $dat->id,
											
											'store_id' => $dat->store_id,
											'product_id' =>$dat->product_id,
											'product_name' =>$dat->name,
											'product_image'=>$image,
											'qty'=>$dat->quantity,
											'orignal_price'=>$dat->product_price,
											'discount_price'=>$dat->discount_price,
											'subtotal'=>number_format($subtotal,2, '.', ''),
											'category'=>$dat->cat_name,
											
											);
									}

									
									
									if($data->payment_mode=='1')
									{
										$payment_type="Cash On Delivery";
									}else if($data->payment_mode=='2'){
										$payment_type="Card Payment";
									}else{
										$payment_type="Onlline Payment";
									}
									$rating="";
												if($data->total_rating){
													$rating=$data->total_rating;
												}
								$data1[]=array('store_name'=>$data->name,'image'=>URL::to('/media/store').'/'.$data->image,
									'order_id'=>$data->id,'is_cancelled'=>$data->is_cancelled,'address'=>$data->order_address,'items'=>$items,
									'payment_mode'=>$payment_type,
									'total_amount'=>$data->total_amount,
									'total_shipping_amount'=>$data->total_shipping_amount,
									'net_amount'=>$data->net_amount,
									'rating'=>$rating,
									'order_delivery_status' =>  $data->order_delivery_status,
									'instructions'=>$data->instructions,
									'order_date'=>date("d/m/Y h:i:s A",strtotime($data->created_at)),
									'products'=>$product_data,
								);
									
									
								}
							
							$status = 1;
							$message = "Order History List.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}
	
	public function OrderDetails() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data1 = (object) array();
	   
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['order_id'])) 
					{
						$r=0;
						
							$orderdata = Orders::select('orders.id')
							->where('orders.id',$decoded['order_id'])->first();
							//dd($orderdata);
							if(empty($orderdata) || $orderdata=="")
							{ 
								$r=1;
								$message = "No Order Found.";
							}
							if($r==0)
							{
								$orderdata = Orders::select('orders.id','orders.order_address',
								'orders.net_amount','orders.product_id','orders.payment_mode','orders.total_amount',
								'orders.total_shipping_amount','orders.instructions','orders.order_delivery_status','orders.is_cancelled','orders.created_at','stores.name',
								'stores.image','rating.total_rating')
								->where('orders.id',$decoded['order_id'])
								->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
								->leftJoin('rating', 'orders.id', '=', 'rating.order_id')
								->orderBy("orders.created_at","DESC")	
								->first();
								//foreach($orderdata as $data)
								//{
									$product_ids=explode(',',$orderdata->product_id);
									//count($product_ids);
									$var="";
									$items="";
									for($i=0;$i<count($product_ids);$i++)
									{
										$orderdatas = Product::select('products.name')
										->where('id',$product_ids[$i])
										->first();
										$items.=$var.$orderdatas->name;
										$var=",";
									}
									
									$orderitems = Orderitems::select('order_items.*','products.name','product_inventories.price as product_price',
									'product_inventories.discount_price','category.name as cat_name')
									->where('order_items.order_id',$orderdata->id)
									->leftJoin('products', 'order_items.product_id', '=', 'products.id')
									->leftJoin('product_inventories','order_items.product_id', '=', 'product_inventories.product_id')
							        ->leftJoin('category', 'products.cat_id', '=', 'category.id')	
									->orderBy("order_items.created_at","DESC")	
									->get();
									$product_data=array();
									foreach($orderitems as $dat)
									{
											$prd_img_data = Productimages::where('product_id',$dat->product_id)->first();
											$image="";
											if(isset($prd_img_data) && $prd_img_data->image!=""){
												$image=URL::to('/media/products').'/'.$prd_img_data->image;
											}
											
											//dd($prd_img_data);
											$subtotal=$dat->quantity * $dat->price;
											$product_data[] = array('id' => $dat->id,
											
											'store_id' => $dat->store_id,
											'product_id' =>$dat->product_id,
											'product_name' =>$dat->name,
											'product_image'=>$image,
											'qty'=>$dat->quantity,
											'orignal_price'=>$dat->product_price,
											'discount_price'=>$dat->discount_price,
											'subtotal'=>number_format($subtotal,2, '.', ''),
											'category'=>$dat->cat_name,
											
											);
									}

									
									
									if($orderdata->payment_mode=='1')
									{
										$payment_type="Cash On Delivery";
									}else if($orderdata->payment_mode=='2'){
										$payment_type="Card Payment";
									}else{
										$payment_type="Onlline Payment";
									}
									$rating="";
												if($orderdata->total_rating){
													$rating=$orderdata->total_rating;
												}
								$data1=array('store_name'=>$orderdata->name,'image'=>URL::to('/media/store').'/'.$orderdata->image,
									'order_id'=>$orderdata->id,'is_cancelled'=>$orderdata->is_cancelled,'address'=>$orderdata->order_address,'items'=>$items,
									'payment_mode'=>$payment_type,
									'total_amount'=>$orderdata->total_amount,
									'total_shipping_amount'=>$orderdata->total_shipping_amount,
									'net_amount'=>$orderdata->net_amount,
									'rating'=>$rating,
									'order_delivery_status' =>  $orderdata->order_delivery_status,
									'instructions'=>$orderdata->instructions,
									'order_date'=>date("d/m/Y h:i:s A",strtotime($orderdata->created_at)),
									'products'=>$product_data,
								);
									
									
								//}
							
							$status = 1;
							$message = "Order Data below.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
		echo json_encode(array('response' => $response_data));
		die;
	}
    public function cancelOrder() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded){
					if(!empty($decoded['user_id']) && !empty($decoded['order_id'])) 
					{
						$orders = DB::table('orders')->where('id',$decoded['order_id'])->select('order_delivery_status')->first();	
						if($orders->order_delivery_status <= 2)
						{
							$cancelOrder =    DB::table('orders')->where('id',$decoded['order_id'])->where('user_id',$decoded['user_id'])->update(['is_cancelled' => 1]);
							//$cancelOrderItem = 	DB::table('order_items')->where('order_id',$decoded['order_id'])->where('user_id',$decoded['user_id'])->delete();
										
										if(!empty($cancelOrder))
										{
											$status = 1;
											$message = "Order Cancel Successfully.";
											
										} else{ 
											$message = "No Order Found.";
										}
						}else{
								$message = "Sorry, Order is in progress, Now you cannot cancel the order.";
						}				
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				 }else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function OrderRepeat(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data1 = array();
	   
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['order_id']) && !empty($decoded['user_id'])) 
					{
						$r=0;

						       $itemdel = Cartitem::whereUser_id($decoded['user_id']);
								if(	$itemdel )
								{
									$itemdel->delete();
								}
						
							$orderdata = Orders::select('orders.id')
							->where('orders.id',$decoded['order_id'])->first();
							//dd($orderdata);
							if(empty($orderdata) || $orderdata=="")
							{ 
								$r=1;
								$message = "No Order Found.";
							}
							if($r==0)
							{
								
									
									$orderitems = Orderitems::select('order_items.*')
									->where('order_items.order_id',$decoded['order_id'])
									->orderBy("order_items.created_at","DESC")	
									->get();
									$product_data=array();
									foreach($orderitems as $dat)
									{
										$addcart = new Cartitem;
										$addcart->user_id =$dat->user_id;
										$addcart->store_id =$dat->store_id;
										$addcart->product_id =$dat->product_id;
										$addcart->qty =  $dat->quantity;
										$addcart->price =$dat->price;
										$addcart->save();
											
									}

									
							$status = 1;
							$message = "Order Repeat.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function OrderRating() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data1 = array();
	   
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				if($decoded) {
					if(!empty($decoded['order_id']) && !empty($decoded['user_id'])) 
					{
						$r=0;

						    
							$orderdata = Orders::select('orders.*')
							->where('orders.id',$decoded['order_id'])->first();

							$rateingchackdata = Rating::select('rating.id')
							->where('order_id',$decoded['order_id'])->where('user_id',$decoded['user_id'])->first();
						
							if(empty($orderdata) || $orderdata=="")
							{ 
								$r=1;
								$message = "No Order Found.";
							}
							if(!empty($rateingchackdata) || $rateingchackdata!="")
							{ 
								$r=1;
								$message = "You have already given rating.";
							}
							if($r==0)
							{
								
										$rating = new Rating;
										$rating->user_id =$decoded['user_id'];
										$rating->store_id =$orderdata->store_id;
										$rating->vendor_id =$orderdata->vendor_id;
										$rating->picker_id =$orderdata->picker_id;
										$rating->order_id =$decoded['order_id'];

										$rating->picker_rating =$decoded['picker_rating'] ? $decoded['picker_rating'] : '';
										$rating->picker_review =$decoded['picker_review'] ? $decoded['picker_review'] : '';
										$rating->time_quality_rating =$decoded['time_quality_rating'] ? $decoded['time_quality_rating'] : '';
										$rating->time_quality_review =$decoded['time_quality_review'] ? $decoded['time_quality_review'] : '';					
										$rating->total_rating =($decoded['picker_rating'] + $decoded['time_quality_rating']) / 2;
										$rating->save();

						 $nodes = Rating::select(DB::raw( 'count( rating.id ) as count'),'total_rating as average')->where('store_id',$orderdata->store_id)->groupBy('total_rating')->get();
						 
						 $rating_count=0;
						 $rating_total=0;
						 foreach ($nodes as $node) {
							$rating_count += $node->count;
							$rating_total += $node->average * $node->count;
						}
					
						if ($rating_total == 0) {
							$store_rating = 0;
						} else {
							 $store_rating = $rating_total / $rating_count;
						}
						//echo round($store_rating,1);exit;
					
						$store_data = Stores::find($orderdata->store_id)->first();
						$store_data->rating=round($store_rating,1);
						$store_data->save();
							$status = 1;
							$message = "Order Rating Successfully Given.";
						}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}
			
		$response_data = array('status'=>$status,'message'=>$message);
		echo json_encode(array('response' => $response_data));
		die;
	}
	public function StoreOffers(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		
				
						$CouponCode = CouponCode::select('coupon_codes.*')
						//->leftJoin('stores', 'store_offers.store_id', '=', 'stores.id')
						->where('status','1')
						//->paginate(10);
						->get();
						
							if($CouponCode->count()>0)
							{

									
							foreach($CouponCode as $data1){
								if($data1->coupon_image!=""){
									$store_image=URL::to('/media/coupon').'/'.$data1->coupon_image;
								}else{
									$store_image="";
								}
								$expiry_date=date('d M Y',strtotime($data1->expires_on));
								$data[] = array('id' => $data1->id,
								'discount_type' => $data1->discount_type,
								'offer_code' => $data1->coupon_code,
								'discount' =>$data1->discount_type == 1 ? $data1->discount : $data1->discount.'%',
								'expiry_date'=> !empty($data1->expires_on) ? $expiry_date : '',
								'offer_image'=>$store_image,
								'coupon_description'=> !empty($data1->coupon_description) ? $data1->coupon_description :'',
								'min_order_amount'=>$data1->min_order_amount,
								'max_limit'=>$data1->max_limit,
								);
								
								$status = 1;
								$message = "Offers List Below.";
							}
						}else{
							$status = 0;
							$message = "No offer found.";
						}
							
							
							$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function applyCouponCode(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = (object)array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['coupon_code']) && !empty($decoded['total_amount'])) 
					{  
//echo $decoded['total_amount']; die;
				$cur_date = date("Y-m-d");
						$CouponCode = CouponCode::select('coupon_codes.*')
						//->leftJoin('stores', 'store_offers.store_id', '=', 'stores.id')
						->where('coupon_code',$decoded['coupon_code'])
						->where('expires_on', ">=", $cur_date)
						->where('max_use',">",'0')
						->where('min_order_amount', "<=", $decoded['total_amount'])
						->where('status','1')
						->first();
						
							if(!empty($CouponCode) && $CouponCode->min_order_amount <= $decoded['total_amount'])
							{

							      $last_price = "";
							 	
								if($CouponCode->discount_type == 1)
									{
										$last_price =  $decoded['total_amount'] - $CouponCode->discount;
										$per = $CouponCode->discount;
									} else {
										$per = round(($decoded['total_amount']*$CouponCode->discount)/100);
									    $last_price = $decoded['total_amount'] - $per; 
									}
									
									if($per >= $CouponCode->max_limit)
									{
										$last_price = $CouponCode->max_limit;
									}
							
								$data = array(
								'id'=> $CouponCode->id,
								'coupon_code' => $decoded['coupon_code'],
								'total_amount' => $decoded['total_amount'],
								'discount_amount' => $per,
								'final_amount' => $last_price,
								'min_order_amount' => !empty($CouponCode->min_order_amount) ? $CouponCode->min_order_amount : '',
								);
								
								$status = 1;
								$message = "Offers apply successfully.";
							
						}else{
							$data = $data;
							$status = 0;
							$message = "Coupon code not found.";
						}
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	
	public function notificationStatus(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{   
						$userdata = User::find($decoded['user_id']);
				
						if($decoded['is_notification']=="1")
							{
								$userdata->is_notification = "1";
								$userdata->save();
								$status = 1;
								$message = "Notification Active successfully.";
					
								
					
							}else
							{
								$userdata->is_notification = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Deactive successfully.";
					
								
								
							}
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	
	public function notificationDelete(){ 
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{   
				        //echo $decoded['type']; die;
						if($decoded['type'] == "All")
						{	
								$notificationdata =  DB::table('notification')->where('user_id',$decoded['user_id'])->delete();
								
								if(!empty($notificationdata))
								{
									$status = 1;
									$message = "Notification Delete successfully.";
								}else{
									 $message = "Notification not found.";
								}	
								
						
						} else{
							$notificationdata = DB::table('notification')->where('user_id',$decoded['user_id'])->where('id',$decoded['notification_id'])->delete();
							if(!empty($notificationdata))
								{
									$status = 1;
									$message = "Notification Delete successfully.";
								}else{
									 $message = "Notification not found.";
								}	
						
						}					
					
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	
	public function notificationReadStatus(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['notification_id'])) 
					{      //echo $decoded['notification_id']; die;
							$id = $decoded['notification_id'];
						$userdata = Notification::find($id);
						//$userdata =  Notification::where('id',$id)->first();
				           //echo '<pre>'; print_r($userdata); die;
						if(!empty($userdata))
						{		
							if($decoded['is_read']=="1")
								{
									$userdata->is_read = "1";
									$userdata->save();
									$status = 1;
									$message = "Notification Read successfully.";
						
									
						
							}
						}else{
							$message = "Notification Not found";
						}
							/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function logout(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['user_id'])) 
					{     
							$id = $decoded['user_id'];
							$userdata = User::find($id);
						
						if(!empty($userdata))
						{		
							
									$userdata->device_id = "";
									$userdata->save();
									$status = 1;
									$message = "Logout successfully.";
						
									
						
							
						}else{
							$message = "User Not found";
						}
							/*else
							{
								$userdata->is_read = "0";
								$userdata->save();
								$status = 1;
								$message = "Notification Unread successfully.";
					
								
								
							}*/
						
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function searchProduct(){
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = array();
	    
		$user_locations = array();
		$search_detail_data=array();
		//$search_product_data=array();
		$home_store_data=array();
		$sflag = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		    if($decoded){
					if(!empty($decoded['product_names']) && !empty($decoded['store_id'])) 
					{  

							$product_cat_data = Product::select('products.id','products.cat_id','products.name as product_name','category.name as category_name')
							->leftJoin('category', 'products.cat_id', '=', 'category.id')	
							->leftJoin('product_inventories', 'products.id', '=', 'product_inventories.product_id')	
							->whereNotNull('product_inventories.product_id')->where('products.status','1')->where('products.store_id',$decoded['store_id'])->orderBy("products.id","ASC")
							->groupBy('products.cat_id');
							
								$product_names = explode(",",$decoded['product_names']);
								
								$product_cat_data->where(function ($query) use ($product_names) {
								foreach ($product_names as $field){
										if (!empty($field)){
												$query->orWhere('products.name','LIKE','%'.trim($field).'%');
										}
									}
								});
						//$product_data=$product_data->paginate(10);
						$product_cat_data=$product_cat_data->get();
						//die;
					//echo '<pre>'; print_r($product_data); die;
					//dd($product_data);
					
					if($product_cat_data->count()>0){
					foreach($product_cat_data as $datas)
					{  //echo $datas->cat_id; die;
						$product_data = Product::select('products.id','products.cat_id','products.name as product_name','category.name as category_name','stores.name as store_name','stores.category_id','product_inventories.price'
								,'product_inventories.discount_price','product_inventories.stock','product_images.image')
								->leftJoin('stores', 'products.store_id', '=', 'stores.id')	
								->leftJoin('category', 'products.cat_id', '=', 'category.id')	
								->leftJoin('product_inventories', 'products.id', '=', 'product_inventories.product_id')	
								->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')	
								->whereNotNull('product_inventories.product_id')->where('products.status','1')->where('products.store_id',$decoded['store_id'])
								->where('products.cat_id',$datas->cat_id)
								->orderBy("products.id","ASC");
									
									//$product_names = explode(",",$decoded['product_names']);
									$product_data->where(function ($query) use ($product_names) {
									foreach ($product_names as $field){
											if (!empty($field)){
													$query->orWhere('products.name','LIKE','%'.trim($field).'%');
											}
										}
									});	
						$product_data=$product_data->get();
						//echo '<pre>'; print_r($product_data); 
						//$search_detail_data['product_count']= $product_data->count();
						//$search_detail_data['category']['name']=$datas->category_name;
						foreach($product_data as $data)
						{
							$cart_is="no";
							$cart_qty='0';
							if(isset($decoded['user_id']) && $decoded['user_id']!="")
							{
								$itemcheckcart = Cartitem::where('user_id',$decoded['user_id'])
								->where('product_id',$data->id)->first();
								
								if(	$itemcheckcart )
								{
									$cart_is='yes';
									$cart_qty=$itemcheckcart->qty;
								}
							}
					        
							if(!empty($decoded['user_id']))
								{   
									$ProductFaviourite = DB::table('favorites')->where('user_id',$decoded['user_id'])->where('product_id',$data->id)->pluck('product_id')->all();
								}
							$search_product_data[] =  array('id' =>$data->id,'product_name' =>$data->product_name,'category_id' =>$data->cat_id,'category_name' =>$data->category_name,'image'=>URL::to('/media/products').'/'.$data->image,'store_name'=>$data->store_name
							,'orignal_price' =>$data->price,'discount_price' =>$data->discount_price,'offer'=>round((($data->price - $data->discount_price)*100) /$data->price).'%','cart_is'=>$cart_is,'cart_qty'=>$cart_qty,'is_faviourte' => !empty($ProductFaviourite) ? 1 : 0,'stock_status'=>$data->stock);
							
							 //$search_detail_data['category']['products']=$search_product_data;	
							}
							
							$home_store_data[] =  array('cat_id' =>$datas->cat_id,'cat_name' =>!empty ($datas->category_name) ? $datas->category_name:'','products'=>$search_product_data);
							$search_product_data = [];
						
						}	
						$search_detail_data=$home_store_data;
							$status = '1';
							$message='Products listed below.';	
					}else {
						$search_detail_data=$home_store_data;
						$message = 'No product data found.';
					}
					}else {
						$message = 'One or more required fields are missing. Please try again.';
					}		
				}else {
					$message = 'Opps! Something went wrong. Please try again.';
				}				
							$response_data = array('status'=>$status,'message'=>$message,'data'=>$search_detail_data);
							echo json_encode(array('response' => $response_data));
							die;
	}
	public function EditProfile(Request $request) 
	{
		 $user_id =  $request->input('user_id');
		// echo json_encode(array('user_id' => $user_id),JSON_UNESCAPED_SLASHES);
		// exit;
		$first_name =  $request->input('first_name');
		$last_name =  $request->input('last_name');
		$image = $request->file('image');
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			 ]);
		if ($validator->fails()) 
		{$response_data = array('status'=>'0','message'=>'All fields are required!');
		}else
		{
			$userArray =  User::where('id',$user_id)->first();
			if(empty($userArray))
			{
				$response_data = array('status'=>'0','message'=>'Invalid User!');
			}else
			{
					$userArray->first_name =$first_name ? $first_name : 'gdfg';
					$userArray->last_name =$last_name ? $last_name : 'dgdfg';
					if(isset($image))
					{
						$imageName = time().$image->getClientOriginalName();
						$image->move(public_path().'/media/users', $imageName);
						$userArray->profile_pic = $imageName;
					}
					$userArray->save();

					$data['user_id'] = (string)$userArray->id;
								$data['first_name'] = $userArray->first_name;
								$data['last_name'] = $userArray->last_name;
								$data['email'] = $userArray->email;
								
								$data['country_code'] = $userArray->country_code;
								$data['mobile_number'] = $userArray->mobile;
								$data['latitude'] = $userArray->latitude;
								$data['longitude'] = $userArray->longitude;
								$data['is_notification'] = $userArray->is_notification;
								$mobile_verify="";
								if($userArray->otp_verify=='yes')
								{
									$mobile_verify='1';
								}else{
									$mobile_verify='0';
								}
								$data['is_mobile_verify'] = $mobile_verify;
								
								
								$data['address'] = $userArray->address ? $userArray->address : '';
					
					if($userArray->profile_pic &&  file_exists(public_path('/media/users/').$userArray->profile_pic ))
					{
						$data['image_url'] = url('/').'/media/users/'.$userArray->profile_pic;
					}else{
						$data['image_url'] ='';
					}
									
					$data1 = $data;
					$message = "Edit Profile Sucessfully.";
					$response_data = array('status'=>'1','message'=>$message,'data'=>$data1);

				}
		}
		echo json_encode(array('response' => $response_data),JSON_UNESCAPED_SLASHES);
		die;
	}
	
	public function contactUs(Request $request) 
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'message' => 'required',
			'phone'  			=> 'nullable|numeric|digits_between:8,15',
			 ]);
		if ($validator->fails()) 
		{	
			$response_data = array('status'=>'0' ,'message'=>'All fields are required!');
		}else
		{ 
	
			$date = date("Y-m-d H:i:s");
				DB::table('contact_us')->insert(
					array(
						'name'			=> $request->input('name'),
						'email' 		=> $request->input('email'),
						'phone' 		=> $request->input('phone'),
						'message' 		=> $request->input('message'),
						'created_at' 	=> $date,
						'updated_at' 	=> $date,
					)
				);
				$emailData = Emailtemplates::where('slug','=','contact_us')->first();
							$settingsEmail 		= Config::get("Site.email");	
							$full_name = $request->input('name');
						  if($emailData){ //echo 'asd'; die;
							$messageBody = strip_tags($emailData->description);
							$subject = $emailData->subject;
							
								$messageBody = str_replace(array('{NAME}','{EMAIL}','{MOBILE}','{MESSAGE}'), array($request->input('name'),$request->input('email'), $request->input('phone'),$request->input('message')),$messageBody);
								$this->sendMail(Config::get("Site.contact_email"),'Admin',$subject,$messageBody,$settingsEmail);
							
							
						}
	
					//$data1 = $data;
					$message = "Message has been send.";
					$response_data = array('status'=>1,'message'=>$message);

				}
		
		echo json_encode(array('response' => $response_data),JSON_UNESCAPED_SLASHES);
	die;
	}
	function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) { 
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
		
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex"; 
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
		$vertices_count = count($vertices);
		
		/* foreach($vertices as $aas){
			$vertices_x[] = $aas['x'];
			$vertices_y[] = $aas['y'];
		}
		 $vertices_count = count($vertices_x) - 1;
		//pr( $point) ; die;
		$longitude_x =  $point['x'];
		$latitude_y = $point['y'];
		
		 $i = $j = $c = 0;
		  for ($i = 0, $j = $vertices_count ; $i < $vertices_count; $j = $i++) { 
			 if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) ) {
			   $c = !$c; 
			    break; 
			   }
		  }
		  return $c; */
         
        for ($i=1; $i < $vertices_count; $i++) {  
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) { 
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
		
        if ($intersections % 2 != 0) {
            return 1;
        } else {
            return 0;
        } 
    }
 
    function pointOnVertex($point, $vertices) { 
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }
 
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
	
	public function availability_check() {
		header('Content-Type: application/json');
		$status = 0;
		$message = NULL;
		$data = (object) array();
		$data1 = (object) array();
		$is_deactivate = '0';
		$is_deleted = '0';
		$data_row 		= 	file_get_contents("php://input");
		$decoded 	    = 	json_decode($data_row, true);
		//$decoded        = $_REQUEST;
		if($decoded) {
			if(!empty($decoded['user_id'])  && !empty($decoded['latitude']) && !empty($decoded['longitude'])) {
				
						$zoneId = NULL;
						## Find Zone 
						$zoneData = DB::table('zone')->where('status',1)->get();
						foreach($zoneData as $zoneValue) {
							$point = array();
							$zoneDetailData = DB::table('zone_details')->where('zone_id',$zoneValue->id)->get();
							$polygons = array();
							foreach($zoneDetailData as $zoneDetailValue) {
								$polygon = array();
								$polygon[] = $zoneDetailValue->latitude;
								$polygon[] = $zoneDetailValue->longitude;
								$polygons[] = implode(" ",$polygon);
							}
							$point[] =  $decoded['latitude'];
							$point[] =  $decoded['longitude'];
							//echo '<pre>'; print_r($polygons); die;
							$points = implode(" ",$point);
							if(!empty($polygons)) { 
								$res = $this->pointInPolygon($points, $polygons);
								//echo $zoneValue['Zone']['id'].' -> '.$res ;
								//echo '<br>';
								if($res) {
									$zoneId = $zoneValue->id;
									break;
								}
							}
						}
						//pr($zoneId); die;

						if(!empty($zoneId)) {
							$data = array('zone_id'=> $zoneId);
							//$data1 = $data;
							$status = 1;
							$message = 'Delivery is provided in this area.';
							
							//$response_data = array('status'=>$status,'message'=>$message,'data'=>$data1);
							//echo json_encode(array('response' => $response_data));
							//die;
						}else{
								$message = 'Sorry! Delivery is not provided in this area.';
						}
					//}
			}
			else {
				$message = 'One or more required parameters are missing. Please try again.';
			}
        } else {
			$message = 'Opps! Something went wrong. Please try again.';
			
		}	
       $response_data = array('status'=>$status,'message'=>$message,'data'=>$data);
							echo json_encode(array('response' => $response_data));
							die;
	     
		
	}
}