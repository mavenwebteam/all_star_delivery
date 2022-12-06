<?php 
namespace App\Http\Controllers\Vendor;
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
use App\Models\Package;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class StoresController extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('vendor')['id'])
		{
			$admindata = User::find(session('vendor')['id']);
			$this->user = $admindata;
		}

    }

	public function index(Request $request)

	{  
		$name=$request->input('name');
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');
		$storedata = Stores::select("stores.*","users.id as user_id",DB::raw('CONCAT(users.first_name, "  ", users.last_name) as vendoremail'))
		->leftJoin('users', 'stores.user_id', '=', 'users.id')	
		->where('stores.user_id',Auth::user()->id)
		->orderBy("stores.created_at","DESC");
		if($name!="")
		{
			$storedata = $storedata->where('stores.name',"like","%$name%");
		}

		if ($start_date!="" && $end_date!="") {

            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
			$storedata = $storedata->whereBetween('stores.created_at', [$_start_date, $_end_date]);
            

        }  else if ($start_date!="" && $end_date=="") {

			$_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$storedata = $storedata->where('stores.created_at',">=",$_start_date)->orderBy("created_at","DESC");

           
        }else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime( $request->input('end_date')));
			$storedata = $storedata->where('stores.created_at',"<=",$end_date)->orderBy("created_at","DESC");

        }

		$storedata=$storedata->paginate(10);
		if ($request->ajax()) 
		{
			return view('vendor.store.search', compact('storedata'));  
        }
		$admindata = Admin::find(session('admin')['id']);

        return view('vendor.store.show', compact('storedata','admindata'));

		

	}
	public function addstore(Request $request)

	{
		
		$shopcatedata = Category::orderBy("created_at","DESC")->get();
		$shopcatedata_select_box=array();
		foreach ($shopcatedata as $key => $value) {
			$shopcatedata_select_box[$value->id]=$value->name;
		}
		
		$packagedata = Package::orderBy("created_at","DESC")->get();
		$packagedata_select_box=array(''=>'Select Package');
		foreach ($packagedata as $key => $value) {
			$packagedata_select_box[$value->id]=$value->name;
		}
		$countryList		=	DB::table('countries')->orderBy('name','ASC')->pluck('name','id')->toArray();
		return view('vendor.store.add',['shopcatedata_select_box'=>$shopcatedata_select_box,'packagedata_select_box'=>$packagedata_select_box,'countryList'=>$countryList]);

	}
	
	public function editstore($id)

	{
		$userId = base64_decode($id);
		$storedata = Stores::find($userId);
		
		$shopcatedata = Category::orderBy("created_at","DESC")->get();
		$shopcatedata_select_box=array();
		foreach ($shopcatedata as $key => $value) {
			$shopcatedata_select_box[$value->id]=$value->name;
		}
		$packagedata = Package::orderBy("created_at","DESC")->get();
		$packagedata_select_box=array(''=>'Select Package');
		foreach ($packagedata as $key => $value) {
			$packagedata_select_box[$value->id]=$value->name;
		}
		
		$countryList		=	DB::table('countries')->orderBy('name','ASC')->pluck('name','id')->toArray();
		
		$stateList			=	DB::table('states')->where('country_id', $storedata->country_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
			$cityList			=	DB::table('cities')->where('state_id', $storedata->state_id)->orderBy('name','ASC')->pluck('name','id')->toArray();
		return view('vendor.store.edit',["storedata" => $storedata,'shopcatedata_select_box'=>$shopcatedata_select_box,'packagedata_select_box'=> $packagedata_select_box,'countryList'=> $countryList,'stateList'=>$stateList,'cityList'=>$cityList ]);

	}
	public function editstorepost(Request $request)

	{
		$id = $request->input('id');
		$user_id =  base64_decode($id);
	
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:50',
			'description' => 'required',
			//'user_id' => 'required',
			'category_id' => 'required|array',
			'open_at' => 'required',
			'close_at' => 'required',
			'address' => 'required',
			'delivery_radius' => 'nullable|numeric',
			'zipcode' => 'required|numeric',
			'image' =>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);

		  if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$store = Stores::find($user_id);
				$name =  $request->input('name');
				$description =  $request->input('description');
				//$user_id =  $request->input('user_id');
				$category_id =  $request->input('category_id');
				$open_at =  $request->input('open_at');
				$close_at =  $request->input('close_at');
				$address =  $request->input('address');
				$zipcode =  $request->input('zipcode');
				$is_open =  $request->input('is_open');
				$image = $request->file('image');

				if($category_id !=""){
					$store->category_id = implode(',', $category_id);
				}

				if(isset($image))
				{
					$imageName = time().$image->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image->move(public_path().'/media/store', $imageName);
					$store->image = $imageName;
				}

				$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
				$oldslug = Stores::where('slug', '=', $slug)->where('id', '!=', $id)->first();
				if ($oldslug === null) 
				{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));}
				else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name.'-'.time())));}


				$store->name = $name;
				$store->slug = $newslug;
				$store->description = $description;
				$store->user_id = Auth::user()->id;
				$store->open_at = $open_at;
				$store->close_at = $close_at;
				$store->address = $address;
				$store->zipcode = $zipcode;
				$store->is_open = $is_open;
				$store->closing_day = $request->input('closing_day');
				$store->delivery_radius = $request->input('delivery_radius');
				$store->package_id = $request->input('package_id');
				
				$store->country_id = $request->input('country_id');
				$store->state_id = $request->input('state_id');
				$store->city_id = $request->input('city_id');
				$store->lat = $request->input('latitude');
				$store->lng = $request->input('longitude');
				
				$store->save();
				return response()->json(['class'=>'success','message'=>'Store Edit Successfully.']);
				
			}	

	}
	public function addstorepost(Request $request)

	{
		
	
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:50',
			'description' => 'required',
			//'user_id' => 'required',
			'category_id' => 'required|array',
			'open_at' => 'required',
			'close_at' => 'required',
			'address' => 'required',
			'zipcode' => 'required|numeric',
			'delivery_radius' => 'nullable|numeric',
			'image' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);

		  if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$store = new Stores;
				$name =  $request->input('name');
				$description =  $request->input('description');
				$user_id =  $request->input('user_id');
				$category_id =  $request->input('category_id');
				$open_at =  $request->input('open_at');
				$close_at =  $request->input('close_at');
				$address =  $request->input('address');
				$zipcode =  $request->input('zipcode');
				$is_open =  $request->input('is_open');
				$image = $request->file('image');

				if($category_id !=""){
					$store->category_id = implode(',', $category_id);
				}

				if(isset($image))
				{
					$imageName = time().$image->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image->move(public_path().'/media/store', $imageName);
					$store->image = $imageName;
				}

				$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
				$oldslug = Stores::where('slug', '=', $slug)->first();
				if ($oldslug === null) 
				{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));}
				else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name.'-'.time())));}


				$store->name = $name;
				$store->slug = $newslug;
				$store->description = $description;
				$store->user_id = Auth::user()->id;
				$store->open_at = $open_at;
				$store->close_at = $close_at;
				$store->address = $address;
				$store->zipcode = $zipcode;
				$store->is_open = $is_open;
				$store->closing_day = $request->input('closing_day');
				$store->delivery_radius = $request->input('delivery_radius');
				$store->package_id = $request->input('package_id');
				$store->country_id = $request->input('country_id');
				$store->state_id = $request->input('state_id');
				$store->city_id = $request->input('city_id');
				$store->lat = $request->input('latitude');
				$store->lng = $request->input('longitude');
				
				$store->save();
				return response()->json(['class'=>'success','message'=>'Store Edit Successfully.']);
				
			}	

	}
	
	
	public function getState($id = null) { 
		$stateList = DB::table('states')->where('country_id',$id)->pluck('name','id')->all();
		//return  View::make('admin.store.state',compact("stateList"));
			return view('vendor.store.state',["stateList" => $stateList]);
	}

	public function getCity($id = null) { 
		$cityList = DB::table('cities')->where('state_id',$id)->pluck('name','id')->all();
		//return  View::make('admin.store.city',compact("cityList"));
			return view('vendor.store.city',["cityList" => $cityList]);
	}
	public function viewstore($id)

	{
		$store_id = base64_decode($id);
		$storedata = Stores::select("stores.*",DB::raw('CONCAT(users.first_name, "  ", users.last_name) AS username'))
		->leftJoin('users', 'stores.user_id', '=', 'users.id')
		->where('stores.id',$store_id)	
		->orderBy("stores.created_at","DESC")->first();
		//dd();
		return view('vendor.store.view',["storedata" => $storedata]);

	}

	public function storestatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$storedata = Stores::find($id);
		
		if($storedata->status=="1")
		{
			$storedata->status = "0";
			$storedata->save();
			return response()->json(['class'=>'success','message'=>'Store Deactive Successfully.']);
			

		}else
		{
			$storedata->status = "1";
			$storedata->save();
			return response()->json(['class'=>'success','message'=>'Store Active Successfully.']);
			
			  
		}
		
	}

	public function usersdelete($id)
	{$id = base64_decode($id);$user = User::find($id);$user->delete();
		 Session::put('msg', '<strong class="alert alert-success"> User successfully deleted.</strong>');
		 return redirect('/admin/user-management/users');	

	}
	
}

?>