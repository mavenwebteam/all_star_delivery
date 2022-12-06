<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\User;
use App\Models\Vehicle;
use App\Models\Emailtemplates;
use App\Uniqcode;
use Hash;
use Auth;
use DB,Image;
use Session,Input;
use App\Constants\Constant;
use Illuminate\Support\Str;
use Config, URL, Mail;
use App\Rules\UserRoleAlreadyExist;

class DriverController extends Controller {
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
		if($request->ajax()){
			$query = User::query()->where('role_id','=','2')->where('is_admin','0')
					->where('is_deleted',0)
					->where(function ($query) {
						$query->where('is_mobile_verify','1');
						$query->orWhere('email_verify','yes');
				});
			//$query =  User::query()->where('is_admin','0')->where('is_deleted',0)->where('role_id','2');
			$query->when(request('keyword') != '', function ($q) {
				return $q->where('first_name', 'like', "%".request('keyword')."%")
				->orWhere('email', 'like', "%".request('keyword')."%")
				->orWhere('mobile', 'like', "%".request('keyword')."%");
			});
			$query->when(request('uu_id') != '', function ($q) {
				return $q->where('uu_id', '=', request('uu_id'));
			});
			$driverData = $query->where('is_admin','0')->where('is_deleted',0)->where('role_id','=','2')->orderBy("created_at","DESC")->paginate(Constant::VENDOR_RECORD_PER_PAGE);
			return view('admin.drivers.search', compact('driverData'));	
			die;
		}
		$driverData = User::where(function ($query) {
			$query->where('is_mobile_verify','1');
			$query->orWhere('email_verify','yes');
		})->where('is_admin','0')
		->where('is_deleted',0)->where('role_id','2')
		->orderBy("created_at","DESC")->paginate(Constant::ADMIN_RECORD_PER_PAGE);
		return view('admin.drivers.show', compact('driverData'));
	}


	public function updateStatus(Request $request)
	{
		$id = base64_decode($request->input('id'));
		$driver = User::find($id);
		
		if($driver->status=="1")
		{
			$driver->status = "0";
			$driver->save();
			return response()->json(['class'=>'success','message'=>'Driver Deactive successfully.']);
		}else
		{
			$driver->status = "1";
			$driver->save();
			return response()->json(['class'=>'success','message'=>'Driver Active successfully.']);
		}
	}


	public function create(Request $request)
	{   
		return view('admin.drivers.add');		
    } 
	
	
    
    
	public function store(Request $request)
	{    
		$validator = Validator::make($request->all(), [
		
			'first_name' 	    => 'required|alpha|min:2|max:15',
			'last_name' 	    => 'required|alpha|min:2|max:15',
			'email' 			=> ['required','max:50','email',new UserRoleAlreadyExist(2, $request->email, NULL, 'email')],
			'mobile' 			=> ['required','regex:/[0-9]{9}/','min:7','max:15', new UserRoleAlreadyExist(2, NULL, $request->mobile, 'mobile')],
			'password'		    => 'required|string|min:8',
            'brand_name'	    => 'required_if:vehicle_type,Motorbike|max:100',
			'year' 			    => 'nullable|required_if:vehicle_type,Motorbike|integer|min:1900|max:'.date('Y'),
			'vehicle_type'   	=> 'required|in:Motorbike,Bicycle',
			'licence_num'	    => 'required_if:vehicle_type,Motorbike|max:20',
			'licence_img'	    => 'required_if:vehicle_type,Motorbike|mimes:jpeg,png,jpg,gif|max:2048',
			'profile_pic'	    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
			'vehicle_num'		=> 'required_if:vehicle_type,Motorbike',
			'vehicle_num_img'	=> 'required_if:vehicle_type,Motorbike|mimes:jpeg,png,jpg,gif|max:2048',
			'model'	            => 'required_if:vehicle_type,Motorbike|max:100',
			]);
			if ($validator->fails()) 
			{
				// dd($validator->errors());
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				if(self::checkDriverExist($request)){
					return response()->json(['success'=>false ,'msg'=>'Driver already exist with this email or mobile !']);
				}

				$data = [
					'role_id'	 	=> '2',
					'first_name' 	=> request('first_name'),
					'last_name' 	=> request('last_name'),
					'email' 		=> request('email'),
					'mobile' 		=> request('mobile'),
					'password' 		=> Hash::make(request('password')),
					'is_mobile_verify' => 1,
					'email_verify' => 'yes',
					'status' => '1'
				];
				$data['uu_id'] = parent::generateUniqueId();
				$profileImg = $request->file('profile_pic');
				if(isset($profileImg))
				{        
					$imageName = time().$profileImg->getClientOriginalName();
					$imageName = str_replace(" ", "", $imageName);
					$image_resize = Image::make($profileImg->getRealPath());              
					$image_resize->resize(118, 118);
					$image_resize->save(public_path('media/users/thumb/' .$imageName));
					$profileImg->move(public_path().'/media/users/', $imageName);
					$data['profile_pic'] = $imageName;
                }
				$driver = User::create($data);
				if( request('vehicle_type') == 'Motorbike')
				{
					$vehicleData = [
						'user_id' 		=> $driver->id,
						'brand_name' 	=> request('brand_name'),
						'year' 			=> request('year'),
						'vehicle_num' 	=> request('vehicle_num'),
						'licence_num' 	=> request('licence_num'),
						'vehicle_type' 	=> request('vehicle_type'),
						'model' 		=> request('model'),
					];
									
					if($request->hasFile('vehicle_num_img')){
                		$image = $request->file('vehicle_num_img');
						//randon name generate
						$img_name = time().'-'.rand(0000,9999).'.'.$image->getClientOriginalExtension();
						//img comprace thumbnail
						$destinationPath = public_path('/media/vehicle');
						$image->move($destinationPath,  $img_name);
						$vehicleData['vehicle_num_img'] = $img_name;
					}

					if($request->hasFile('licence_img')){
                		$image = $request->file('licence_img');
						//randon name generate
						$img_name = time().'-'.rand(0000,9999).'.'.$image->getClientOriginalExtension();
						//img comprace thumbnail
						$destinationPath = public_path('/media/vehicle');
						$image->move($destinationPath,  $img_name);
						$vehicleData['licence_img'] = $img_name;
					}
					
				}else{
					$vehicleData = [
						'user_id' 		=> $driver->id,
						'vehicle_type' 	=> request('vehicle_type'),
					];
				}

				$vehicle = Vehicle::create($vehicleData);
				
				$emailData = Emailtemplates::where('slug', '=', 'driver-registration-by-admin')->first();
				if ($emailData) {
					$textMessage = strip_tags($emailData->description);
					$driver->subject = $emailData->subject;
					if ($driver->email != '') {
						$textMessage = str_replace('{USERNAME}',$driver->first_name, $textMessage);
						Mail::raw($textMessage, function ($messages) use ($driver) {
							$to = $driver->email;
							$messages->to($to)->subject($driver->subject);
						});
					}
				}
                echo json_encode(array('class'=>'success','message'=>'Driver Added successfully'));die;
			}
	} 
	


	public function edit($id)
	{
		$id = base64_decode($id);
		$data = User::with(['vehicle'])->find($id);
		
		if($data)
		{
			return view('admin.drivers.edit',compact('data'));
		}else{
			return response()->json(['warning' => 'Driver Not found !']);
		}		
    }


	public function update(Request $request, $id)
	{  
		$validator = Validator::make($request->all(), [
			'first_name' 	    => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:15',
			'last_name' 	    => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:15',
			'email' => ['required','max:50','email',new UserRoleAlreadyExist(2, $request->email, NULL, 'email', $id)],
			'mobile' => ['required','regex:/[0-9]{9}/','min:7','max:15', new UserRoleAlreadyExist(2, NULL, $request->mobile, 'mobile', $id)],
            'brand_name'	    => 'required_if:vehicle_type,Motorbike|max:100',
			'year' 			    => 'nullable|required_if:vehicle_type,Motorbike|integer|min:1900|max:'.date('Y'),
			'vehicle_type' 	    => 'required|in:Motorbike,Bicycle',
			'licence_num'	    => 'required_if:vehicle_type,Motorbike|max:20',
			'licence_img'	    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
			'profile_pic'	    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
			'vehicle_num'	    => 'required_if:vehicle_type,Motorbike|max:20',
			'vehicle_num_img'	=> 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
			'model'	            => 'required_if:vehicle_type,Motorbike|max:100',
			]);
			if ($validator->fails()) 
			{
				// dd($validator->errors());
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				if(self::checkDriverExist($request, $id)){
					return response()->json(['success'=>false ,'msg'=>'Driver already exist with this email or mobile !']);
				}

				$user = User::with('vehicle')->find($id);

				$data = [
					'first_name' 	=> request('first_name'),
					'last_name' 	=> request('last_name'),
					'email' 		=> request('email'),
					'mobile' 		=> request('mobile'),
				];
				$profileImg = $request->file('profile_pic');
				if(isset($profileImg))
				{   
					parent::deleteFile(public_path('media/users/thumb/'.$user->profile_pic));
					parent::deleteFile(public_path('media/users/'.$user->profile_pic));
					$imageName = time().$profileImg->getClientOriginalName();
					$imageName = str_replace(" ", "", $imageName);
					$image_resize = Image::make($profileImg->getRealPath());              
					$image_resize->resize(118, 118);
					$image_resize->save(public_path('media/users/thumb/' .$imageName));
					$profileImg->move(public_path().'/media/users/', $imageName);
					$data['profile_pic'] = $imageName;
                }
				$driver = User::where('id',$id)->update($data);
				
				if( request('vehicle_type') == 'Motorbike')
				{
					$vehicleData = [
						'brand_name' 	=> request('brand_name'),
						'year' 			=> request('year'),
						'vehicle_num' 	=> request('vehicle_num'),
						'licence_num' 	=> request('licence_num'),
						'vehicle_type' 	=> request('vehicle_type'),
						'model' 		=> request('model'),
					];
									
					if($request->hasFile('vehicle_num_img')){
						parent::deleteFile(public_path('media/vehicle/'.$user->vehicle->vehicle_num_img));
                		$image = $request->file('vehicle_num_img');
						//randon name generate
						$img_name = time().'-'.rand(0000,9999).'.'.$image->getClientOriginalExtension();
						//img comprace thumbnail
						$destinationPath = public_path('/media/vehicle');
						$image->move($destinationPath,  $img_name);
						$vehicleData['vehicle_num_img'] = $img_name;
					}

					if($request->hasFile('licence_img')){
						parent::deleteFile(public_path('media/vehicle/'.$user->vehicle->licence_img));
                		$image = $request->file('licence_img');
						//randon name generate
						$img_name = time().'-'.rand(0000,9999).'.'.$image->getClientOriginalExtension();
						//img comprace thumbnail
						$destinationPath = public_path('/media/vehicle');
						$image->move($destinationPath,  $img_name);
						$vehicleData['licence_img'] = $img_name;
					}	
				}else{
					if(object_get($user,'vehicle.vehicle_num_img', NULL)){
						parent::deleteFile(public_path('media/vehicle/'.$user->vehicle->vehicle_num_img));
					}
					if(object_get($user,'vehicle.licence_img', NULL)){
					parent::deleteFile(public_path('media/vehicle/'.$user->vehicle->licence_img));
					}
					$vehicleData = [
						'vehicle_type' 	=> request('vehicle_type'),
						'brand_name' 	=> NULL,
						'year' 			=> NULL,
						'vehicle_num' 	=> NULL,
						'licence_num' 	=> NULL,
						'model' 		=> NULL,
					];
				}

				$vehicle = Vehicle::where('id',$user->vehicle->id)->update($vehicleData);
                echo json_encode(array('class'=>'success','message'=>'Driver has been updated successfully'));die;
			}

	}

	public function show($id)
	{
		$driverId = base64_decode($id);
		$driverData = User::with(['vehicle'])->find($driverId);
		if($driverData)
		{
			return view('admin.drivers.view',compact('driverData'));
		}else{
			return response()->json(['warning' => 'Driver Not found !']);
		}
	}


	public function destroy($id)
	{   
		$id = base64_decode($id);
		$product = User::find($id);
		$product->deleted_at = date('Y-m-d H:i:s');
		$product->is_deleted = 1;
		$product->save();
		return response()->json(['class'=>'success','message'=>'Driver has been deleted successfully.']);
	}

	public function checkDriverExist($request, $id='')
	{
		$checkDriverExist = User::where('role_id',2)->where('id','!=',$id);
				$checkDriverExist = $checkDriverExist->where(function ($query) use ($request) {
					$query->where('email', '=',$request->email);
					$query->orWhere('mobile', '=', $request->mobile);
                })->count();
		if($checkDriverExist)
		{return true;}else{return false;}
	}
}

?>