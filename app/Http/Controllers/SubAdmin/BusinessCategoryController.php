<?php 
namespace App\Http\Controllers\SubAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Brands;
use App\Models\BusinessCategory;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail,Excel;
class BusinessCategoryController extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('admin')['id'])
		{
			$admindata = Admin::find(session('admin')['id']);
			$this->user = $admindata;
		}

    }
	//-------------business category -feb-2021---------
	public function index(Request $request)
	{
		$business_category_data = BusinessCategory::where('is_deleted',0)->orderBy("name_en","ASC");
		$name = $request->name;
	// dd($name);
		if($name!="")
		{ 
			$business_category_data = $business_category_data->where('name_en','like',"%$name%");
		}

		$business_category_data = $business_category_data->paginate(10);

		if ($request->ajax()) 
		{
			return view('sub_admin.business-category.search', compact('business_category_data'));  
        }
		$admindata = Admin::find(session('admin')['id']);

        return view('sub_admin.business-category.show', compact('business_category_data','admindata'));
	}

	//-------------business category -feb-2021---------
	public function addbusinesscategory(Request $request)
	{
		return view('sub_admin.business-category.add');
	} 
	//-------------business category -feb-2021---------
	public function addbusinesscategorypost(Request $request)
	{
		$validator = Validator::make($request->all(), [
		'name_en' => 'required|max:30|min:2|regex:/^[\pL\s\-]+$/u|unique:business_category,name_en',
		'name_burmese' => 'required|max:30|min:2|unique:business_category,name_burmese',
		'image' =>'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		if ($validator->fails()) 
		{
			return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
		}else
		{    
			$image = $request->file('image');
			$BusinessCategory = new BusinessCategory();	
			if(isset($image))
				{
					$imageName = time().$image->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image->move(public_path().'/media/business_category', $imageName);
					$BusinessCategory->image = $imageName;
				}
						
			$name_en =  $request->input('name_en');	
			$name_burmese =  $request->input('name_burmese');
			$BusinessCategory->name_en = $name_en;
			$BusinessCategory->name_burmese = $name_burmese;
			$BusinessCategory->save();
			echo json_encode(array('class'=>'success','message'=>'Business Category Added successfully.'));die;
			
		}
		
	} 
	
	//-------------business category -feb-2021---------
	public function editbusinesscategory($id)
	{
		$id = base64_decode($id);
		$business_category_data = BusinessCategory::find($id);
		return view('sub_admin.business-category.edit',["business_category_data" => $business_category_data]);
	}

	
   
	//-------------business category -feb-2021---------
	public function editbusinesscategorypost(Request $request)
	{
		$id = $request->input('id');
		$id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			'name_en' => 'required|max:30|min:2|regex:/^[\pL\s\-]+$/u|unique:business_category,name_en,'.$id,
			'name_burmese' => 'required|max:30|min:2|unique:business_category,name_burmese,'.$id,
			'image' =>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$BusinessCategory = BusinessCategory::find($id);
				$image = $request->file('image');
			   
		       if(isset($image))
					{
						$imageName = time().$image->getClientOriginalName();
						$imageName =str_replace(" ", "", $imageName);
						$image->move(public_path().'/media/business_category', $imageName);
						$BusinessCategory->image = $imageName;
					}
			
				$name_en =  $request->input('name_en');
				$name_burmese =  $request->input('name_burmese');
				$BusinessCategory->name_en = $name_en;
				$BusinessCategory->name_burmese = $name_burmese;
                $BusinessCategory->save();
				
				echo json_encode(array('class'=>'success','message'=>'Business Category Edit successfully.'));die;

			}	

	}


	//-------------business category -feb-2021---------
	public function businesscategorystatus(Request $request)
	{
	    $id = base64_decode($request->input('id'));
		$businesscategorydata = BusinessCategory::find($id);
		
		if($businesscategorydata->status=="1")
		{
			$businesscategorydata->status = "0";
			$businesscategorydata->save();
			echo json_encode(array('class'=>'success','message'=>'Business Category Deactive successfully.'));die;
		}else
		{
			$businesscategorydata->status = "1";
			$businesscategorydata->save();
			echo json_encode(array('class'=>'success','message'=>'Business Category Active successfully.'));die;
		}
		
	}
	
	//-------------business category -feb-2021---------
	public function viewbusinesscategory($id)
	{
		$productId = base64_decode($id);
		$businessCategoryData = BusinessCategory ::where('id',$productId)
		->first();
		return view('sub_admin.business-category.view',["businessCategoryData" => $businessCategoryData]);
	}

	
	// public function businesscategoryremove(Request $request)
	// {  
	// 	dd('fdgg');
	// 	$id = base64_decode($request->input('id'));
	// 	$user = Brands::find($id);
	// 	$user->is_deleted = 1;
	// 	$user->save();
	// 	return response()->json(['class'=>'success','message'=>'Brand Delete Successfully.']);
	// }
	
	
}

?>