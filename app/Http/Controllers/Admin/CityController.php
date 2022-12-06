<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Country;
use App\Models\City;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class CityController extends Controller {
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
		$name = $request->input('name');
		$city_data = City::select('city.id','city.name','city.created_at','city.status','country.name as country_name')
		->leftJoin('country', 'city.country_id', '=', 'country.id')	
		->orderBy("created_at","DESC");
		if($name!="")
		{ $city_data = $city_data->where('city.name','like',"%$name%"); }
		 $city_data=$city_data->paginate(10);	
		 //echo '<pre>'; print_r($city_data); exit;
		 if ($request->ajax()) 
		{
			return view('admin.city.search', compact('city_data'));  
		}
		
		$admindata = Admin::find(session('admin')['id']);

        return view('admin.city.show', compact('city_data','admindata'));
	}

	public function addcity(Request $request)
	{
		$country_data = Country::orderBy("name","DESC")->get();
		$country_box=array(''=>'Select Country');
		foreach($country_data as $key=>$value){
			$country_box[$value->id]=$value->name;
		}
		//echo '<pre>';print_r($country_box);exit;

		return view('admin.city.add',['country_box'=>$country_box]);
	} 
	public function addcitypost(Request $request)
	{
			$validator = Validator::make($request->all(), [
			'name' => 'required',
			'country_id' => 'required',
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$City = new City();				
				$name =  $request->input('name');	
				$country_id =  $request->input('country_id');	
				$City->name = $name;
				$City->country_id = $country_id;
                $City->save();
                echo json_encode(array('class'=>'success','message'=>'City Added successfully.'));die;
				
			}
		
	} 
	
	public function editcity($id)

	{
		$id = base64_decode($id);
		$city_data = City::find($id);
		$country_data = Country::orderBy("name","DESC")->get();
		$country_box=array(''=>'Select Country');
		foreach($country_data as $key=>$value){
			$country_box[$value->id]=$value->name;
		}
		//echo '<pre>';print_r($country_box);exit;
		return view('admin.city.edit',["city_data" => $city_data,'country_box'=>$country_box]);

	}
	

	public function editcitypost(Request $request)

	{
		$id = $request->input('id');
		 $id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			
			'name' => 'required',
			'country_id' => 'required',

			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$City = City::find($id);
			
				$name =  $request->input('name');
				$country_id =  $request->input('country_id');
				$City->name = $name;
				$City->country_id = $country_id;
                $City->save();
				echo json_encode(array('class'=>'success','message'=>'City Edit successfully.'));die;
				
			}	

	}



	public function citystatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$citydata = City::find($id);
		
		if($citydata->status=="1")
		{
			$citydata->status = "0";
			$citydata->save();
			echo json_encode(array('class'=>'success','message'=>'City Deactive successfully.'));die;
			
		}else
		{
			$citydata->status = "1";
			$citydata->save();
			echo json_encode(array('class'=>'success','message'=>' City Active successfully.'));die;
			
			  
		}
		
	}

	// public function cityremove(Request $request)
	// {
    //      $id = base64_decode($request->input('id'));
    //     $City = City::find($id);
    //     $City->delete();

    //     return response()->json(['success'=>true ,'message'=>'<div class="alert alert-success alert-dismissible">
    //     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    //     <h4><i class="icon fa fa-check"></i> Alert!</h4>
    //     City Remove Successfully
    //   </div>']);

	//    }
	
}

?>