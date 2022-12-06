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
use Hash;
use Auth;
use Excel;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class SubAdminController extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('admin')['id'])
		{
			$admindata = Admin::find(session('admin')['id']);
			$this->user = $admindata;
		}

    }

	public function index(Request $request)

	{	
		$userdata = User::where('is_admin','1')->where('id','!=','1')->orderBy("created_at","DESC");
		$type=$request->input('type');
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');
		$username=$request->input('username');
		$email=$request->input('email');
		$mobile=$request->input('mobile');
		$uniq_id=$request->input('uniq_id');
		$status=$request->input('status');
		$perpage=$request->input('perpage');

		if($uniq_id!="")
		{ 
			$userdata=$userdata->where('uniq_id',$uniq_id);
			
		}
		if($email!="")
		{ 
			$userdata=$userdata->where('email',$email);
			
		}
		if($mobile!="")
		{ 
			$userdata=$userdata->where('mobile','LIKE',"%$mobile%");
		}
		if($status!="")
		{ 
			$userdata=$userdata->where('status',$status);
			
		}
		if($username!="")
		{ 
			$userdata=$userdata->where(DB::raw('CONCAT(users.first_name, "  ", users.last_name)'),'like',"%$username%");
			
		}
		if($type!="")
		{ 
			$userdata=$userdata->where('type',$type);
			
		} else if ($start_date!="" && $end_date!="") {

			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$userdata = $userdata->whereBetween('created_at', [$start_date, $end_date]);
			

		} else if ($start_date!="" && $end_date=="") {

			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$userdata =$userdata->where('created_at',">=",$start_date);

		
		} else if ($start_date=="" && $end_date!="") {

			$end_date = date('Y-m-d H:i:s', strtotime($end_date));
			$userdata = $userdata->where('created_at',"<=",$end_date);

		}
		if(!empty($perpage)){
		    $userdata = $userdata->paginate($perpage);
		   } else {
			 $userdata = $userdata->paginate(10);
		   }
		if ($request->ajax()) 
		{
			return view('admin.subadmin.search', compact('userdata'));  
        }
		$admindata = Admin::find(session('admin')['id']);

        return view('admin.subadmin.show', compact('userdata','admindata'));
	}
	public function exportusers(Request $request)

	{	
		$userdata = User::where('is_admin','0')->orderBy("created_at","DESC");
		$type=$request->input('search_user_type');
		$start_date= $request->input('datepicker');
		$end_date=$request->input('datepicker2');
		$username=$request->input('user_search_name');
		$uniq_id=$request->input('user_search_uniq_id');
		$status=$request->input('search_user_status');

		if($uniq_id!="")
		{ 
			$userdata=$userdata->where('uniq_id',$uniq_id);
			
		}
		if($status!="")
		{ 
			$userdata=$userdata->where('status',$status);
			
		}
		if($username!="")
		{ 
			$userdata=$userdata->where(DB::raw('CONCAT(users.first_name, "  ", users.last_name)'),'like',"%$username%");
			
		}
		if($type!="")
		{ 
			$userdata=$userdata->where('type',$type);
			
		} else if ($start_date!="" && $end_date!="") {

			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$userdata = $userdata->whereBetween('created_at', [$start_date, $end_date]);
			

		} else if ($start_date!="" && $end_date=="") {

			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$userdata =$userdata->where('created_at',">=",$start_date);

		
		} else if ($start_date=="" && $end_date!="") {

			$end_date = date('Y-m-d H:i:s', strtotime($end_date));
			$userdata = $userdata->where('created_at',"<=",$end_date);

		}
		$userdata = $userdata->get();
		return Excel::create('itsolutionstuff_example', function($excel) use ($userdata) {

            $excel->sheet('mySheet', function($sheet) use ($userdata)

            {

                $sheet->fromArray($userdata);

            });

        })->download('csv');


      //  return view('admin.users.export', compact('userdata'));
	}
	
	public function adduser(Request $request)
	{
		return view('admin.subadmin.add');
	} 
	public function adduserpost(Request $request)
	{
			$validator = Validator::make($request->all(), [
			//'type' => 'required',
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50',
			'email' => 'required|email|unique:users',
			'mobile' => 'required|regex:/[0-9]{9}/',
			'password' => 'required',
			'country_code' => 'required',
			
		
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$user = new User();
				$type =  $request->input('type');
				$first_name =  $request->input('first_name');
				$last_name =  $request->input('last_name');
				$email =  $request->input('email');
				$mobile =  $request->input('mobile');
				$password =  $request->input('password');
				$country_code =  $request->input('country_code');
				$image = $request->file('image');


				if(isset($image))
					{
						$imageName = time().$image->getClientOriginalName();
						$imageName =str_replace(" ", "", $imageName);
						$image->move(public_path().'/media/users', $imageName);
						$user->profile_pic = $imageName;
					}

				$user->type = $type;
				$user->uniq_id = uniqid();
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->email = $email;
				
				$user->country_code = $country_code;
				$user->mobile = $mobile;
				$user->password = Hash::make($password);
				$user->is_admin = 1;
				//echo '<pre>';print_r($user);exit;
				$user->save();
				echo json_encode(array('class'=>'success','message'=>'Sub Admin Added successfully.'));die;

			}
		
	} 
	
	public function edituser($id)

	{
		$userId = base64_decode($id);
		$userdata = User::find($userId);
		return view('admin.subadmin.edit',["userdata" => $userdata]);

	}
	public function viewuser($id)
	{
		$userId = base64_decode($id);
		$userdata = User::find($userId);
		return view('admin.subadmin.view',["userdata" => $userdata]);

	}
	

	public function edituserpost(Request $request)

	{
		$userId = $request->input('user_id');
		 $user_id =  base64_decode($userId);
		$validator = Validator::make($request->all(), [
			//'type' => 'required',
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50',
			'email' => 'required|email|unique:users,email,'.$user_id,
			'mobile' => 'required|regex:/[0-9]{9}/|unique:users,mobile,'.$user_id,
			'country_code' => 'required',
			// 'profile_img' => 'max:2048|mimes:jpg,jpeg,gif,png',

			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$user = User::find($user_id);
				$type =  $request->input('type');
				$first_name =  $request->input('first_name');
				$last_name =  $request->input('last_name');
				$email =  $request->input('email');
				$mobile =  $request->input('mobile');
				$password =  $request->input('password');
				$country_code =  $request->input('country_code');
				$image = $request->file('image');


				if(isset($image))
					{
						$imageName = time().$image->getClientOriginalName();
						$imageName =str_replace(" ", "", $imageName);
						$image->move(public_path().'/media/users', $imageName);
						$user->profile_pic = $imageName;
					}

				$user->type = $type;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->country_code = $country_code;
				$user->email = $email;
				$user->mobile = $mobile;
				$user->is_admin = 1;
				
				if(!empty($password))
				{
					$user->password =Hash::make($password);
				}
				
				$user->save();
				
				echo json_encode(array('class'=>'success','message'=>'Sub Admin Edit successfully.'));die;

			}	

	}



	public function userstatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$userdata = User::find($id);
		
		if($userdata->status=="1")
		{
			$userdata->status = "0";
			$userdata->save();
			echo json_encode(array('class'=>'success','message'=>' Sub Admin Deactive successfully.'));die;

			

		}else
		{
			$userdata->status = "1";
			$userdata->save();
			echo json_encode(array('class'=>'success','message'=>' Sub Admin Active successfully.'));die;

		
			  
		}
		
	}

		

		public function usersdelete($id)

	   {

		$id = base64_decode($id);

		$user = User::find($id);

		$user->delete();

		 Session::put('msg', '<strong class="alert alert-success"> User successfully deleted.</strong>');

		 return redirect('/admin/user-management/users');	

	   }
	
}

?>