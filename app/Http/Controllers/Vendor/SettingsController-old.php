<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;

use Validator;

use App\User;

use App\Models\Settings;

use App\Models\Agent;

use App\Models\Admin;

use Hash;

use Auth;

use DB;

use App\Helpers;

use Config;

use Session;

use Setting;

use Mail;

use Image;

use File;



class SettingsController extends Controller {

	

  private $admindt;



  public function __construct()

    {

		if (session('admin')['id'])

		{

			$admindata = Admin::find(session('admin')['id']);

			$this->admindt = $admindata;

		}

    }

	

	public function index()

	{

		$admindata = Admin::find(session('admin')['id']);

		$user_data =  User::select('id',"email","status","created_at")->orderBy("created_at","DESC")->take(7)->get();

		return view('admin.dashboard',['admindata'=>$admindata,'user_data'=>$user_data]);	

	}

   

	public function login()

	{

		 if (session('admin')['id'])

	{ 

		return redirect('/admin'); 

	}else

	{

	  	return view('admin.login');

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

			$userdata = array(

				'email' 		=> $request->input('email'),

				'password' 		=> $request->input('password'),

				'is_admin' 		=>'1',

			);

			//echo "<pre>";print_r($userdata);die; 

			//$admin = Admin::where('email', $email)->where('password', md5($password))->first();

			 $remember_me = $request->has('remember') ? true : false;

			if (Auth::attempt($userdata, $remember_me)){	

				return redirect('/admin');

			}else{ 

				Session(['msg' => '<strong class="alert alert-danger">Invalid email and password.</strong>']);

				return redirect('/admin/login');

			}

		}

		

	}

	

	 public function logout()

    { 

		

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

	public function setting()

	{

		$settingdata = Settings::where('id','1')->first();

		//echo '<pre>'; print_r($settingdata); die;

		return view('admin.settings.edit',['settingdata'=>$settingdata]);

	}	

	

				

	public function settingpost(Request $request)

	{

				   

		$validator = Validator::make($request->all(), 

		[

			

			'max_cash_order_limi' => 'required|numeric',

			'order_cancel_duration' => 'required|numeric',

			'delivery_start_time' => 'required',

			'delivery_end_time' => 'required',

			'delivery_slots' => 'required|numeric',

			'delivery_slot_duration' => 'required|numeric',

			'min_order_value' => 'required|numeric',

			'admin_commission' => 'required|numeric',

			'invoice_note' => 'required',

			//'all_category_img' => 'nullable|mimes:jpeg,png,jpg,gif,svg',

		]);

		if ($validator->fails()) 

		{

			return redirect('/admin/setting')->withErrors($validator)->withInput();

	

		}else

		{

			$admin = Settings::find('1');

			

			$max_cash_order_limi =  $request->input('max_cash_order_limi');

			$order_cancel_duration =  $request->input('order_cancel_duration');

			$delivery_start_time =  $request->input('delivery_start_time');

			 $delivery_end_time =  $request->input('delivery_end_time');

			 $delivery_slots =  $request->input('delivery_slots');

			 $delivery_slot_duration =  $request->input('delivery_slot_duration');

			 $invoice_note =  $request->input('invoice_note');

			  $min_order_value =  $request->input('min_order_value');

			   $admin_commission =  $request->input('admin_commission');

			 //$all_category_img =  $request->input('all_category_img');



			$image = $request->file('all_category_img');

			   

		       if(isset($image))

					{

						$imageName = time().$image->getClientOriginalName();

						$imageName =str_replace(" ", "", $imageName);

						$image->move(public_path().'/media/category', $imageName);

						$admin->all_category_img = $imageName;

					}

			$admin->max_cash_order_limi = $max_cash_order_limi;

			$admin->order_cancel_duration = $order_cancel_duration;

			$admin->delivery_start_time = $delivery_start_time;

			

			$admin->delivery_end_time = $delivery_end_time;

			$admin->delivery_slots = $delivery_slots;

			$admin->delivery_slot_duration = $delivery_slot_duration;

			$admin->invoice_note = $invoice_note;

			$admin->min_order_value = $min_order_value;

			$admin->admin_commission = $admin_commission;

			//$admin->all_category_img = $delivery_start_time;

			

			$admin->save();

			Session::put('msg', '<strong class="alert alert-success">Data successfully updated.</strong>');

			return redirect('/admin/setting');

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

				

				

			









}





?>

