<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Subcategory;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class SubCategoryController extends Controller {
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
		$catedata = Subcategory::orderBy("created_at","DESC");
		$name=$request->input('name');
		$parent_id=$request->input('parent_id');
		$start_date= $request->input('start_date');
		$end_date=$request->input('end_date');

		
		
		if($name!=""){ $catedata = $catedata->where('name','like',"%$name%");}

		if($parent_id!="")
		{ 
			$catedata=$catedata->where('parent_id',$parent_id);
			
		} else if ($start_date!="" && $end_date!="") {

            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$catedata = $catedata->whereBetween('created_at', [$start_date, $end_date]);
            

        } else if ($start_date!="" && $end_date=="") {

			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$catedata =$catedata->where('created_at',">=",$start_date);

           
        } else if ($start_date=="" && $end_date!="") {

            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
			$catedata = $catedata->where('created_at',"<=",$end_date);

        }


		$catedata=$catedata->paginate(10);
		

		if ($request->ajax()) 
		{
			return view('admin.subcategory.search', compact('catedata'));  
        }
		$admindata = Admin::find(session('admin')['id']);

        return view('admin.subcategory.show', compact('catedata','admindata'));
       
         
	}
	
	public function addcategory(Request $request)
	{
		return view('admin.subcategory.add');
	} 
	public function addcategorypost(Request $request)
	{
			$validator = Validator::make($request->all(), [
			'name' => 'required|max:50|unique:sub_category,name',
			'parent_id' => 'required',
			'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$category = new Subcategory();	
				$parent_id =  $request->input('parent_id');			
				$name =  $request->input('name');
				$image = $request->file('image');


				if(isset($image))
					{
					$imageName = time().$image->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image->move(public_path().'/media/category', $imageName);
					$category->image = $imageName;
					}

					$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
					$oldslug = Subcategory::where('slug', '=', $slug)->first();
					if ($oldslug === null) 
					{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));}
					else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name.'-'.time())));}


				$category->parent_id = $parent_id;
				$category->name = $name;
				$category->slug = $newslug;
                $category->save();
                return response()->json(['class'=>'success','message'=>'Sub Category Added successfully.']);
			
			}
		
	} 
	
	public function editcategory($id)

	{
		$id = base64_decode($id);
		$catdata = Subcategory::find($id);
		return view('admin.subcategory.edit',["catdata" => $catdata]);

	}
	

	public function editcategorypost(Request $request)

	{
		$id = $request->input('id');
		 $id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			
			'name' => 'required|max:50|unique:sub_category,name,'.$id,
			'parent_id' => 'required',
			
			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$category = Subcategory::find($id);	
				$parent_id =  $request->input('parent_id');			
				$name =  $request->input('name');
				$image = $request->file('image');


				if(isset($image))
					{
					$imageName = time().$image->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image->move(public_path().'/media/category', $imageName);
					$category->image = $imageName;
					}

					$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
					$oldslug = Subcategory::where('slug', '=', $slug)->where('id', '!=', $id)->first();
					if ($oldslug === null) 
					{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));}
					else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($name.'-'.time())));}

				$category->parent_id = $parent_id;
				$category->name = $name;
				$category->slug = $newslug;

                $category->save();
				return response()->json(['class'=>'success','message'=>'Sub Category Edit successfully.']);
				
			}	

	}



	public function categorystatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$catdata = Subcategory::find($id);
		
		if($catdata->status=="1")
		{
			$catdata->status = "0";
			$catdata->save();
			return response()->json(['class'=>'success','message'=>'Sub Category Deactive successfully.']);
		
		}else
		{
			$catdata->status = "1";
			$catdata->save();
			return response()->json(['class'=>'success','message'=>'Sub Category Active successfully.']);
			
			  
		}
		
	}
public function viewsubcategory($id)

	{
		$productId = base64_decode($id);
		$catData = Subcategory::where('id',$productId)
		->first();
		
			
		return view('admin.subcategory.view',["catData" => $catData]);

	}
	
	public function categoryremove(Request $request)
	{
         $id = base64_decode($request->input('id'));
        $category = Subcategory::find($id);
        $category->delete();

        return response()->json(['success'=>true ,'message'=>'<div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        Sub Category Remove Successfully
      </div>']);

	   }
	
}

?>