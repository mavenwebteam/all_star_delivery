<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Pagination\Paginator;

use Validator;

use App\Models\Category;
use App\Models\ItemCategory;
use App\Uniqcode;

use App\Models\Admin;



use Hash;

use App\Models\Brands;
use App\Models\BusinessCategory;

use Auth;

use DB;

use App\Helpers;

use Config;

use Session;

use Mail;

use Image,Excel;

class ItemCategoryController extends Controller {

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
		$businessCategories = BusinessCategory::where('status','1')
							->where('is_deleted','0')
							->get();
        $catedata = ItemCategory::select('item_category.*')->where('item_category.is_deleted',0)->orderBy("item_category.created_at","DESC");
		
		$name_en=$request->input('name_en');
		$type=$request->input('type');
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');
		if($name_en!=""){ 
			$catedata = $catedata->where('item_category.name_en','like',"%$name_en%");
		}
		if(!empty($request->business_category)){ 
			$catedata = $catedata->where('item_category.category_id', $request->business_category);
		}
		if($type!="")
		{
			$catedata=$catedata->where('item_category.type',$type);
		} 
		else if($start_date!="" && $end_date!="") 
		{
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$catedata = $catedata->whereBetween('item_category.created_at', [$start_date, $end_date]);
        }
		else if($start_date!="" && $end_date=="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$catedata =$catedata->where('item_category.created_at',">=",$start_date);
        } else if ($start_date=="" && $end_date!="") {
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
			$catedata = $catedata->where('item_category.created_at',"<=",$end_date);
        }
		$catedata=$catedata->paginate(10);
		if ($request->ajax())
		{
			return view('admin.item-category.search', compact('catedata'));
		}
		$admindata = Admin::find(session('admin')['id']);
		return view('admin.item-category.show', compact('catedata','admindata','businessCategories'));
	}

	

	public function addcategory(Request $request)
	{
		//$business_category = DB::table('business_category')->where('status','1')->where('is_deleted',0)->get();
		return view('admin.item-category.add');
	} 

	public function addcategorypost(Request $request)
	{
			
			$validator = Validator::make($request->all(), [

			'name_en' => 'required|min:2|max:30|regex:/^[\pL\s\-]+$/u|unique:item_category,name_en',
			'name_burmese' => 'required|min:2|max:30|unique:item_category,name_burmese',
			'category_id' => 'required',
			'image' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

			],['category_id.required' => "The business category field is required."]);

			if ($validator->fails()) 
			{

				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);

			}else

			{ 

				$item_category = new ItemCategory();
				$name_en =  $request->input('name_en');
				$name_burmese =  $request->input('name_burmese');
				$image = $request->file('image');
				if(isset($image))

					{

					$imageName = time().$image->getClientOriginalName();

					$imageName =str_replace(" ", "", $imageName);

					$image->move(public_path().'/media/item_category', $imageName);

					$item_category->image = $imageName;

					}	

					$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en)));

					$oldslug = ItemCategory::where('slug', '=', $slug)->first();

					if ($oldslug === null) 

					{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en)));}

					else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en.'-'.time())));}
				
				$item_category->name_en = $name_en;
				$item_category->name_burmese = $name_burmese;
				$item_category->slug = $newslug;

				$category_id =  $request->input('category_id');
				$item_category->category_id = $category_id;
                $item_category->save();

				echo json_encode(array('class'=>'success','message'=>'Item Category Added successfully.'));die;
			}
		} 

	

	public function editcategory($id)
	{

		$id = base64_decode($id);

		$catdata = ItemCategory::find($id);

		return view('admin.item-category.edit',["catdata" => $catdata]);



	}

	public function editcategorypost(Request $request)
	{
		$id = $request->input('id');
		$id =  base64_decode($id);
		$validator = Validator::make($request->all(), [

			'name_en' => 'required|min:2|max:30|regex:/^[\pL\s\-]+$/u|unique:item_category,name_en,'.$id,
			'name_burmese' => 'required|min:2|max:30|unique:item_category,name_burmese,'.$id,
			'category_id' => 'required',
			'image' =>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'],
			['category_id.required' => "The business category field is required."]);
			
			if ($validator->fails()) 
			{
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);

			}else
			{
				$item_category = ItemCategory::find($id);	
				$name_en =  $request->input('name_en');
				$name_burmese =  $request->input('name_burmese');
				$image = $request->file('image');
				
				if(isset($image)){

					$imageName = time().$image->getClientOriginalName();

					$imageName =str_replace(" ", "", $imageName);

					$image->move(public_path().'/media/item_category', $imageName);

					$item_category->image = $imageName;

				}
					$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en)));

					$oldslug = ItemCategory::where('slug', '=', $slug)->where('id', '!=', $id)->first();

					if ($oldslug === null) 

					{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en)));}

					else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name_en.'-'.time())));}
				
					$item_category->name_en = $name_en;
					$item_category->name_burmese = $name_burmese;
					$category_id =  $request->input('category_id');
					$item_category->category_id = $category_id;
					$item_category->slug = $newslug;
					$item_category->save();

				echo json_encode(array('class'=>'success','message'=>'Item Category Edit successfully.'));die;

				

			}	



	}







	public function categorystatus(Request $request)

	{

		 $id = base64_decode($request->input('id'));

		$catdata = ItemCategory::find($id);

		

		if($catdata->status=="1")

		{

			$catdata->status = "0";

			$catdata->save();

			echo json_encode(array('class'=>'success','message'=>'Item Category Deactive successfully.'));die;



		



		}else

		{

			$catdata->status = "1";

			$catdata->save();

			echo json_encode(array('class'=>'success','message'=>'Item Category Active successfully.'));die;



			

			  

		}

		

	}

	public function importcategory(Request $request)

	{

		return view('admin.category.import_category');

	} 

	

	

	public function importcategorypost(Request $request)

	{

		$validator = Validator::make($request->all(), [

			'import_file' => 'required|mimes:xls,xlsx|',

			]);

			if ($validator->fails()) 

			{//echo '<pre>';print_r($validator->errors());exit;

				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);

			}else

			{

				$path = $request->file('import_file')->getRealPath();

				$excel_data = Excel::load($path)->get();

				//echo '<pre>'; print_r($excel_data); die;

				

			if(!empty($excel_data) && $excel_data->count() > 0){

					

				foreach($excel_data as $data)

				{

					

					$brand_name= $data['brand_name'] ? $data['brand_name'] :'';

					$category_name= $data['category_name'] ? $data['category_name'] :'';

					$brandData  = DB::table('brands')->where('name',$brand_name)->where('status','1')->where('is_deleted',0)->first();

					

					if(!empty($brandData))

					{

						$cat = DB::table('category')->where('name',$category_name)->first();

						if(empty($cat))

						{

						$category = new Category();	

						$category->name = $category_name;

						$category->brand_id = $brandData->id;

						$category->save();

						}

					}



				}

				

				echo json_encode(array('class'=>'success','message'=>'Category has been created.'));die;

			}else{

				echo json_encode(array('class'=>'error','message'=>'Data Not Found.'));die;

			}	

			}

	} 

public function viewcategory($id)



	{

		$productId = base64_decode($id);

		$catData = ItemCategory::select('item_category.*')->where('item_category.id',$productId)->first();

		

			//echo '<pre>'; print_r($catData); die;

		return view('admin.item-category.view',["catData" => $catData]);



	}


	// public function categoryremove(Request $request)
	// {
    //      $id = base64_decode($request->input('id'));
    //     $category = Category::find($id);
    //     $category->delete();
    //    return response()->json(['class'=>'success','message'=>'Category Delete Successfully.']);
	// }
	

}



?>