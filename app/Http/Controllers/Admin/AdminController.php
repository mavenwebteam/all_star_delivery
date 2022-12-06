<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App\User;
use App\Models\Promocode;
use App\Models\Stores;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Order;
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
use File,Input;
use Cookie;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Charts\OrderChart;
use App\Jobs\PushNotification;
use App\Constants\Constant;
use App\Jobs\WebPushNotification;

class AdminController extends Controller {
	
	public function index(Request $request)
	{  
		
		$orderMonth = 0;		
		$admindata = [];
		
		$total_order = DB::table('orders')->whereDate('created_at', date("Y-m-d"))->count();
		
		$startDate = Carbon::now()->format('Y-m-d');
		$ongoingOffer = Promocode::whereNull('deleted_at')
           		->where('status', '1')
           		->whereDate('start_date', '<=', $startDate)
          		->whereDate('end_date', '>=', $startDate)
				->count();
		// todays earning
		$total_earning = Order::where("status","DELIVERED")->whereDate('created_at', date("Y-m-d"))->sum('grand_total');

		$todays_cancel_order = Order::whereDate('created_at', date("Y-m-d"))
			->where('status', "CANCELLED")
			->count();

		// Orders not yet accept in more than 1 min 
		$order_not_yet_accepted = Order::where("status", "ORDERED")
		
		->where('updated_at', '<=' , Carbon::now()->addMinutes(1)->toDateTimeString())
		->count();

		$todays_commission_amount = Order::where('status', 'DELIVERED')
			->whereDate('created_at', date("Y-m-d"))
			->sum('admin_commission_amount');
		
		$todays_offline_store = Stores::where('status', '1')
			->where('is_approved', '1')
			->where('is_open', 'close')
			
			->count();

		// ------order chart-------------
		/**
		 * Line Chart Orders
		*/
		$startDate = Carbon::now()->subMonths(3)->format('Y-m-d');
		$endDate = Carbon::now()->format('Y-m-d');
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
		$orderData = Order::where("created_at",">=", $startDate)
		->where("created_at","<=", $endDate)
		->orderBy('created_at','desc')->select('id','created_at')->get()->groupBy(function ($val) {
			return Carbon::parse($val->created_at)->format('M');
		})->toArray();
		foreach($orderData as $key => $val){
			$allOrder[] = count($val);
		}

		$completedOrder = [];
		$orderData = Order::where("created_at",">=",$startDate)
		->where("created_at","<=", $endDate)
		->orderBy('created_at','desc')->where('status','DELIVERED')->select('id','created_at')->get()->groupBy(function ($val) {
			return Carbon::parse($val->created_at)->format('M');
		})->toArray();
		foreach($orderData as $key => $val){
			$completedOrder[] = count($val);
		}

		$canceledOrder = [];
		$orderData = Order::where("created_at",">=",$startDate)
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
		// ------order chart end-------
		return view('admin.dashboard', compact('admindata', 'total_order', 'total_earning', 'ongoingOffer', 'todays_cancel_order', 'order_not_yet_accepted', 'todays_commission_amount', 'todays_offline_store', 'orderchart'));	
	}
	
	public function login()
	{
		if (Auth::check())
		{
			return redirect('/admin');
		}
	  	return view('admin.login');
	}
	
	public function checkuserlogin()
	{
		if(!empty(Auth::user()))
		{
			return 1;
		}else{
			return 2;
		}
		
	}	
	public function loginpost(Request $request)
	{
	   $validator = Validator::make($request->all(), [ 'email' => 'required', 
	   'password' => 'required',
	   
	    ]);
		if ($validator->fails()) 
		{
		 return redirect('/admin/login')->withErrors($validator)->withInput();
		}else
		{
		   // $email =  $request->input('email');
			//$password =  $request->input('password');
			if(is_numeric($request->input('email'))){
				$userdata = array(
				
					'mobile' 		=> $request->input('email'),
					'password' 		=> $request->input('password'),
					'is_admin' 		=>'1',
				);
				}
			elseif (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
				$userdata = array(
				
					'email' 		=> $request->input('email'),
					'password' 		=> $request->input('password'),
					'is_admin' 		=>'1',
				);
				
			}
			
			//echo "<pre>";print_r($userdata);die; 
			//$admin = Admin::where('email', $email)->where('password', md5($password))->first();
			 $remember_me = $request->has('remember') ? true : false; 
			if (Auth::attempt($userdata, $remember_me)){	
			
				if($remember_me)
				{
					setcookie ("email",$request->email,time()+ (86400 * 30));
					setcookie ("password",$request->password,time()+ (86400 * 30));
				}else{ 
						unset($_COOKIE['email']);
						setcookie('email', '', 1);
						unset($_COOKIE['password']);
						setcookie('password', '', 1);
				}
				return redirect('/admin');
			}else{ 
				Session(['msg' => '<strong class="alert alert-danger">Invalid email and password.</strong>']);
				return redirect('/admin/login');
			}
		}
		
	}
	
	public function logout()
    { 
		User::where('id', Auth::user()->id)->update(array('device_token' => "",'device_id'=>""));
		Auth::logout();
		return redirect('/admin/login');
		// if (session('admin')['id'])
		// { 
		// 	Session::forget('admin');
		// 	Session::put('msg', '<strong class="alert alert-success">Logout Successfully.</strong>');
		// 	return redirect('/admin/login');
		// }else 
		// {return redirect('/admin/login');  }
				  
	}
	public function Setting()
	{
		$settingdata = Setting::where('id','1')->first();
		return view('admin.setting',['settingdata'=>$settingdata]);
	}
	//------admin profile feb---------- 
	public function profile()
	{
		$admindata = Auth::user();
		return view('admin.profile',['admindata'=>$admindata]);
	}
				
	public function editprofile(Request $request)
	{
		 Input::replace($this->arrayStripTags(Input::all()));
		$formData	=	Input::all();
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$id = 1;
		$validator = Validator::make($request->all(), 
		[
			
			'first_name' => 'required|max:15|min:2',
			'last_name' => 'required|max:15|min:2',
			'email' => 'required|email|max:50|unique:users,email,'.$id,
			'profile_pic' => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
			'password' => 'nullable||min:8|custom_password',
			'confirm_password' 	=> 'nullable|min:8|same:password',
		],
		["password.custom_password"	=>	"Password must have be a combination of numeric, alphabet and special characters."]);
		if ($validator->fails()) 
		{
			return redirect('/admin/profile')->withErrors($validator)->withInput();
	
		}else
		{
			$admin = User::find('1');
			
			$first_name =  $request->input('first_name');
			$last_name =  $request->input('last_name');
			$email =  $request->input('email');
			 $password =  $request->input('password');

			
			$admin->first_name = $first_name;
			$admin->last_name = $last_name;
			$admin->email = $email;
			if(!empty($password )){
				$admin->password = Hash::make($password);
			}
			$image = $request->file('profile_pic');
			if(isset($image)){
								$imageName = time().rand(0,999).$image->getClientOriginalExtension();
								$image->move(public_path().'/media/users', $imageName);
								$imageName =str_replace(" ", "", $imageName);
								$admin->profile_pic = $imageName;
							}
			$admin->save();
			Session::put('msg', '<strong class="alert alert-success">Data successfully updated.</strong>');
			return redirect('/admin/profile');
		}
	}
				
				
				
	public function changepassword()
	{
	$admin = Admin::find(session('admin')['id']);
	return view('admin.changePassword',['admindata'=>$admin]);
	}
				
	public function changepasswordpost(Request $request)
	{
		$admin = Admin::find(session('admin')['id']);
		
		$validator = Validator::make($request->all(), [
		'password' => 'required|min:6|max:16|confirmed',
		'password_confirmation' => 'required|min:6|max:16',
		]);
		if ($validator->fails()) {
		return redirect('/admin/change-password')
					->withErrors($validator)
					->withInput();

		}else{

				$password =  $request->input('password');
				$admin->password = md5($password);
				$admin->save();
				Session::put('msg', '<strong class="alert alert-success">Your password successfully changed.</strong>');
				return redirect('/admin');

		}
		
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
     * test send notification to admin
	 * use only for test web notification
	 * route "/send-web-notification"
     */
    public function sendWebNotification()
    {
        $firebaseToken = User::where('id','1')->whereNotNull('device_token')->pluck('device_token')->all();
		$title = 'Admin title';
		$body = 'Admin message body';
        $type = 'Order';
        $target_id = '000';
		Helper::notificationWeb($firebaseToken, $title, $body, $type, $target_id);
    }

	
}


?>
