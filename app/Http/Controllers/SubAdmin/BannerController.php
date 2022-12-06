<?php 
namespace App\Http\Controllers\SubAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Banner;
use App\Models\BusinessCategory;
use App\Models\Stores;
// use App\User;
// use App\Models\Vehicle;
// use App\Uniqcode;
// use Hash;
// use Auth;
use DB,Image;
use Session,Input;
use App\Constants\Constant;
// use Illuminate\Support\Str;

class BannerController extends Controller {
  	public function index(Request $request)
	{
		if($request->ajax()){
			$data = Banner::with(['businessCategory','store'])->whereNull('deleted_at')
					->orderBy('created_at','DESC')
					->paginate(Constant::ADMIN_RECORD_PER_PAGE);
			return view('sub_admin.banners.search', compact('data'));	
			die;
		}
		$data = Banner::with(['businessCategory','store'])->whereNull('deleted_at')
		->orderBy('created_at','DESC')
		->paginate(Constant::ADMIN_RECORD_PER_PAGE);
		return view('sub_admin.banners.show', compact('data'));
	}


	public function updateStatus(Request $request)
	{
		$id = base64_decode($request->input('id'));
		$banner = Banner::find($id);
		
		if($banner->status=="1")
		{
			$banner->status = "0";
			$banner->save();
			return response()->json(['class'=>'success','message'=>'Banner Deactive successfully.']);
		}else
		{
			$banner->status = "1";
			$banner->save();
			return response()->json(['class'=>'success','message'=>'Banner Active successfully.']);
		}
	}


	public function create(Request $request)
	{   
		$businessCategory = BusinessCategory::where('status','1')
		->where('is_deleted','0')
		->orderBy('name_en','ASC')
		->get();
		return view('sub_admin.banners.add', compact('businessCategory'));		
    } 
	
	
    
    
	public function store(Request $request)
	{    
		$validator = Validator::make($request->all(), [
			'business_category_id' => 'required|exists:business_category,id',
			'store_id' 	        => 'required',
			'banner'	    => 'required|mimes:jpeg,png,jpg,gif|max:2048',


			]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}
			$bannerCount = Banner::whereNull('deleted_at')->get()->count();
			if($bannerCount >= 5){
				return response()->json(['toster_class'=>'warning', 'msg'=> 'Banner may not exceed five'],400);
			}

			$data = $request->all();
			$data['status'] = 1;
			$banner = $request->file('banner');
			if(isset($banner))
			{        
				$imageName = time().$banner->getClientOriginalName();
				$imageName = str_replace(" ", "", $imageName);
				$image_resize = Image::make($banner->getRealPath());              
				$image_resize->resize(118, 118);
				$image_resize->save(public_path('media/banners/thumb/' .$imageName));
				$banner->move(public_path().'/media/banners/', $imageName);
				$data['banner'] = $imageName;
			}
			$driver = Banner::create($data);
			echo json_encode(array('class'=>'success','message'=>'Banner Added successfully'));die;
	} 
	


	public function edit($id)
	{
		$id = base64_decode($id);
		$banner = Banner::find($id);

		$businessCategory = BusinessCategory::where('status','1')
		->where('is_deleted','0')
		->orderBy('name_en','ASC')
		->get();

		$stores = Stores::where('status','1')
		->where('is_deleted','0')
		->where('business_category_id',$banner->business_category_id)
		->get();
		return view('sub_admin.banners.edit', compact('businessCategory','banner','stores'));
    }


	public function update(Request $request, $id)
	{  
		$validator = Validator::make($request->all(), [
			'business_category_id' => 'required|exists:business_category,id',
			'store_id' 	        => 'required',
			'banner'	    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048'
			]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}
			$bannerData = Banner::find($id);
			$imageName = $bannerData->banner;
			$banner = $request->file('banner');
			if(isset($banner))
			{     
				parent::deleteFile(public_path('media/banners/thumb/'.$bannerData->banner));   
				parent::deleteFile(public_path('media/banners/'.$bannerData->banner));   
				$imageName = time().$banner->getClientOriginalName();
				$imageName = str_replace(" ", "", $imageName);
				$image_resize = Image::make($banner->getRealPath());              
				$image_resize->resize(118, 118);
				$image_resize->save(public_path('media/banners/thumb/' .$imageName));
				$banner->move(public_path().'/media/banners/', $imageName);
				
			}
			$bannerData->business_category_id = request('business_category_id');
			$bannerData->store_id = request('store_id');
			$bannerData->banner = $imageName;
			$bannerData->save();
			echo json_encode(array('class'=>'success','message'=>'Banner has been updated successfully'));die;
	}



	public function destroy($id)
	{   
		$id = base64_decode($id);
		$banner = Banner::find($id);
		$banner->deleted_at = date('Y-m-d H:i:s');
		$banner->save();
		return response()->json(['class'=>'success','message'=>'Banner has been deleted successfully.']);
	}

	public function storeList($businessCatId)
	{
		$stores = Stores::where('status','1')
		->where('is_deleted','0')
		->where('business_category_id',$businessCatId)
		->pluck("name","id");
	
		return response()->json(['class'=>'success','stores'=>$stores]);
	}
}

?>