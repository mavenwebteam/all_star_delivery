<?php 
namespace App\Http\Controllers\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App\User;
use App\Models\Company;
use App\Models\Agent;
use App\Models\Admin;
use App\Models\Stores;

use App\Models\Product;
use App\Models\Order;
use App\Models\Verifiabledocs;
use Hash;
use Auth;
use DB;
use App\Helpers;
use App\Helpers\Helper;
use Config;
use Session;
use Setting;
use Mail;
use Image;
use File;
use Cookie,heidelpayPHP,Input,Response;
use App\Charts\OrderChart;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VendorController extends Controller {

	public function index(Request $request)
	{  
		$todays_cancel_order = $todays_earning = $todays_commission_amount = $order_not_yet_accepted = $total_instock_product = $total_outofstock_product = $total_today_order = $total_order=$total_earning =$orderchart = 0;
		
		$stores = Stores::where('user_id',Auth::user()->id)->where('status','1')->first();
		$startDate = Carbon::now()->subMonths(3)->format('Y-m-d');
		$endDate = Carbon::now()->format('Y-m-d');
		if(!empty($stores))
		{
			
			$total_instock_product = Product::where('store_id',$stores->id)
				->where('in_stock','1')
				->where('status','1')
				->whereNull('deleted_at')
				->count();

			$total_outofstock_product = Product::where('in_stock','0')
				->where('status','1')
				->whereNull('deleted_at')
				->where('store_id',$stores->id)
				->count();
			
			$total_today_order = Order::where('store_id',$stores->id)
				->whereDate('created_at', date("Y-m-d"))
				->count();

			// Orders not yet accept in more than 1 min 
			$order_not_yet_accepted = Order::where('store_id',$stores->id)
			->where("status", "ORDERED")
			->where('updated_at', '<=' , Carbon::now()->addMinutes(1)->toDateTimeString())
			->count();
		
			$todays_commission_amount = Order::where('store_id',$stores->id)
			->where('status', 'DELIVERED')
			->whereDate('created_at', date("Y-m-d"))
			->sum('admin_commission_amount');

			$todays_earning = Order::where('store_id',$stores->id)
			->where('status', 'DELIVERED')
			->whereDate('created_at', date("Y-m-d"))
			->sum('amount_payable_to_store');
			
			$todays_cancel_order = Order::where('store_id',$stores->id)
			->whereDate('created_at', date("Y-m-d"))
			->where('status', "CANCELLED")
			->count();

			/**
			 * Line Chart Orders
			*/
			
			$labelArr = array();

			if(!empty($request->start_date) && !empty($request->end_date)){
				$startDate = date('Y-m-d', strtotime($request->start_date));
				$endDate = date('Y-m-d', strtotime($request->end_date));
			}

      		$result = CarbonPeriod::create($startDate, '1 month', $endDate);
			foreach ($result as $dt) {
				array_push($labelArr, $dt->format("F-Y"));
			}
			$allOrder = [];
			$orderData = Order::where('store_id',$stores->id)
			->where("created_at",">=", $startDate)
			->where("created_at","<=", $endDate)
			->orderBy('created_at','desc')->select('id','created_at')->get()->groupBy(function ($val) {
				return Carbon::parse($val->created_at)->format('M');
			})->toArray();
			foreach($orderData as $key => $val){
				$allOrder[] = count($val);
			}

			$completedOrder = [];
			$orderData = Order::where('store_id',$stores->id)
			->where("created_at",">=",$startDate)
			->where("created_at","<=", $endDate)
			->orderBy('created_at','desc')->where('status','DELIVERED')->select('id','created_at')->get()->groupBy(function ($val) {
				return Carbon::parse($val->created_at)->format('M');
			})->toArray();
			foreach($orderData as $key => $val){
				$completedOrder[] = count($val);
			}

			$canceledOrder = [];
			$orderData = Order::where('store_id',$stores->id)
			->where("created_at",">=",$startDate)
			->where("created_at","<=", $endDate)
			->orderBy('created_at','desc')->where('status','CANCELLED')->select('id','created_at')->get()->groupBy(function ($val) {
				return Carbon::parse($val->created_at)->format('M');
			})->toArray();
			foreach($orderData as $key => $val){
				$canceledOrder[] = count($val);
			}

			$date = Carbon::now();
		
			$orderchart = new OrderChart;
			$orderchart->title('From '.date('d-M-Y',strtotime($startDate)).' to '. date('d-M-Y',strtotime($endDate)));
			$orderchart->labels($labelArr);
			
			$orderchart->dataset('All Orders', 'line', $allOrder)->options([
				'fill' => true,
				'color' =>'#FFC107',
				'borderColor' => '#FFC107'
			]);
			$orderchart->dataset('Completed Orders', 'line', $completedOrder)->options([
				'fill' => true,
				'color' =>'#457fca',
				'borderColor' => '#457fca'
			]);
			$orderchart->dataset('Canceled Order', 'line', $canceledOrder)->options([
				'fill' => true,
				'color' =>'#ec3b57',
				'borderColor' => '#ec3b57'
			]);
		
		}
		
		return view('vendor.dashboard',
		[
			'total_today_order'=>$total_today_order,
		 	'order_not_yet_accepted' => $order_not_yet_accepted,
			'total_instock_product'=>$total_instock_product,
		 	'total_outofstock_product'=>$total_outofstock_product,
			'todays_earning'=> $todays_earning,
			'todays_commission_amount'=> $todays_commission_amount,
			'todays_cancel_order'=> $todays_cancel_order,
			'orderchart'=> $orderchart,
			'is_store' => $stores ? true : false,
		]);	
	}
	
	public function Setting()
	{
		$settingdata = Setting::where('id','1')->first();
		return view('admin.setting',['settingdata'=>$settingdata]);
	}
	
	public function profile()
	{
		$admindata = Auth::user();
		return view('vendor.profile',['admindata'=>$admindata]);
	}
				
	public function editprofile(Request $request)
	{ 
		$id = Auth::user()->id;   
		$validator = Validator::make($request->all(), 
		[
			'first_name' => 'required|max:50',
			'last_name'  => 'required|max:50',
			'email'      => 'required|email',
			'mobile'     => 'required|numeric',
			'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
		]);
		if ($validator->fails()) 
		{
			return redirect('/vendor/profile')->withErrors($validator)->withInput();
		}else
		{
			$vendor = User::find($id);
			$isVendorMobileExist = User::where('role_id',3)
			->where('id','!=', $id)
			->where('mobile',$request->mobile)
			->count();
		
			if($isVendorMobileExist >= 1){
				return redirect('/vendor/profile')->withErrors( ['mobile'=>trans('vendor.mobile_already_use_vendor')])->withInput();
				die;
			}
			// check mobile if update 
			$isMobileUpdate = false;
			if($request->mobile != $vendor->mobile){
				$isMobileUpdate = true;
				$otp = Helper::__generateNumericOTP(4);
				$vendor->is_mobile_verify = 0;
				$vendor->otp = $otp;
				$message = "Your otp is: ".$otp;
				Helper::__sendOtp($request->mobile, $message);
			}

			$vendor->mobile = $request->mobile;
			$image = $request->file('profile_pic');
			if(isset($image))
			{
				if($vendor->profile_pic != '')
				{
					$image_path = public_path('media/users/thumb/'.$vendor->profile_pic);
					parent::deleteFile($image_path);
					$image_path = public_path('media/users/'.$vendor->profile_pic);
					parent::deleteFile($image_path);
				}
				$imageName = time().rand(0,999).'.'.$image->getClientOriginalExtension();
				$imageName =str_replace(" ", "", $imageName);
				$image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/users/thumb/' . $imageName));
				$image->move(public_path('/media/users'), $imageName);
				$vendor->profile_pic = $imageName;
			}
			$vendor->save();
			Session::flash('success', trans('vendor.profie_updated_success'));
			if($isMobileUpdate) {
				return redirect('/vendor/otp-form');
			}
			return redirect('/vendor/profile');
		}
	}
				
				
				
	public function changepassword()
	{
		$admin = User::find(Auth::user()->id);
		return view('vendor.changePassword',['admindata'=>$admin]);
	}
				
	
						
		
	public function sendemail(Request $request)
	{
	   	$admin = $this->admin;			 
		$validator = Validator::make($request->all(), [
				'email' => 'required|email',
				'uniqcode' => 'required',
			 ]);
			  if ($validator->fails()) {
	               return redirect('/admin/code-generate')
	                           ->withErrors($validator)
	                           ->withInput();

					}else{
	                        
							$email =  $request->input('email');
							$uniqcodeval =  $request->input('uniqcode');
							
							
							$data = array( 'email' => $email, 'from' => 'noreply@demoasite1.com', 'from_name' => 'Manta Play', 'data' => $uniqcodeval );
							Mail::send( 'admin.emailtemp', $data, function( $message ) use ($data)
	{
		$message->to( $data['email'] )->from( $data['from'], "manta play" )->subject( 'Sign Up Code' );
	});
				
							Session::put('msg', '<strong class="alert alert-success">Mail successfully sent.</strong>');
							return redirect('/admin');
	
					}
					
				}	
	/**
	 * update vendor password
	 * @param string $current_password
	 * @param string $new_password
	 * @param string $password_confirmation
	 * */		
	public function updatePassword(Request $request)
	{
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$validator = Validator::make($request->all(), [
			'current_password' => 'required',
			'new_password' => 'required|custom_password|min:6|max:16',
			'password_confirmation' => 'required|min:6|max:16|same:new_password',
		],
		[
		"new_password.custom_password"	=>	trans('vendor.password_must_contain')
		]);
		if ($validator->fails()) {
			return response()->json(['toster_class'=>'error', 'msg'=> $validator->errors()->first()],400);
		}

		$user = User::find(Auth::user()->id);
		if(!Hash::check($request->current_password, Auth::user()->password)){
			return response()->json(['toster_class'=>'error', 'msg'=> trans('vendor.current_password_not_match')],400);
		} else {
			$password =  $request->new_password;
			$user->password = Hash::make($password);
			$user->save();
			return response()->json(['toster_class'=>'success', 'msg'=> trans('vendor.password_match_success')],200);
		}

	}

	public function otpForm()
	{
		$otp = Auth::user()->otp;
		return view('vendor.otp')->with('otp',$otp);
	}

	/**
	 * otp verify on vendor profile mobile no. is update
	 * @param otp
	 * */ 
	public function otpVerify(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'otp' => 'required|digits:4', 
		])->validate();
		$vendor = Auth::user();
		if($vendor->otp === $request->otp){
			$vendor->otp = NULL;
			$vendor->is_mobile_verify = 1;
			$vendor->save();
			Session::flash('success', trans('vendor.mobile_verify_success'));
			return redirect('/vendor/profile');
			die;
		}else{
			Session::flash('error', trans('vendor.otp_not_match'));
			return view('vendor.otp')->with('otp',$request->otp);
		}

	}

}
