<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Country;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class CountryController extends Controller {
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
		$country_data = Country::orderBy("created_at","DESC");
		$name = $request->input('name');
	
		if($name!="")
		{ $country_data = $country_data->where('name','like',"%$name%"); }
		$country_data=$country_data->paginate(10);	 
        

		 if ($request->ajax()) 
		{
			return view('admin.country.search', compact('country_data'));  
        }
		$admindata = Admin::find(session('admin')['id']);

        return view('admin.country.show', compact('country_data','admindata'));
	}
	
	public function addcountry(Request $request)
	{
		return view('admin.country.add');
	} 
	public function addcountrypost(Request $request)
	{
			$validator = Validator::make($request->all(), [
			'name' => 'required',
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$Country = new Country();				
				$name =  $request->input('name');	
				$Country->name = $name;
                $Country->save();
                echo json_encode(array('class'=>'success','message'=>'Country Added successfully.'));die;
				
			}
		
	} 
	
	public function editcountry($id)

	{
		$id = base64_decode($id);
		$country_data = Country::find($id);
		return view('admin.country.edit',["country_data" => $country_data]);

	}
	

	public function editcountrypost(Request $request)

	{
		$id = $request->input('id');
		 $id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			
			'name' => 'required',

			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$Country = Country::find($id);
			
                $name =  $request->input('name');
                $Country->name = $name;
                $Country->save();
				echo json_encode(array('class'=>'success','message'=>'Country Edit successfully.'));die;
				
			}	

	}



	public function countrystatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$countrydata = Country::find($id);
		
		if($countrydata->status=="1")
		{
			$countrydata->status = "0";
			$countrydata->save();
			echo json_encode(array('class'=>'success','message'=>' Country Deactive successfully.'));die;
			

		}else
		{
			$countrydata->status = "1";
			$countrydata->save();
			echo json_encode(array('class'=>'success','message'=>'Country Active successfully.'));die;
			
			  
		}
		
	}

	// public function countryremove(Request $request)
	// {
    //      $id = base64_decode($request->input('id'));
    //     $Country = Country::find($id);
    //     $Country->delete();

    //     return response()->json(['success'=>true ,'message'=>'<div class="alert alert-success alert-dismissible">
    //     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    //     <h4><i class="icon fa fa-check"></i> Alert!</h4>
    //     Country Remove Successfully
    //   </div>']);

	//    }
	
}

?>