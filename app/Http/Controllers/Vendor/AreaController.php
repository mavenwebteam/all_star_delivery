<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Pagination\Paginator;

use Validator;

use App\Models\Country;

use App\Models\City;

use App\Models\Area;

use App\Uniqcode;

use App\Models\Admin;

use Hash;

use Auth;

use DB;

use App\Helpers;

use Config;

use Session;

use Mail;

class AreaController extends Controller {

 


	public function index(Request $request)



	{

		$name = $request->input('name');

		$area_data = Area::select('area.id','area.name','area.created_at','area.status','city.name as city_name')

		->leftJoin('city', 'area.city_id', '=', 'city.id')	

		->orderBy("created_at","DESC");



		if($name!="")

		{ $area_data = $area_data->where('area.name','like',"%$name%"); }

		$area_data = $area_data->paginate(10);



		if ($request->ajax()) 

		{

			return view('admin.area.search', compact('area_data'));  

        }

		$admindata = Admin::find(session('admin')['id']);



		return view('admin.area.show', compact('area_data','admindata'));

		

         

	}

	

	public function addarea(Request $request)

	{

		$city_data = City::orderBy("name","DESC")->get();

		$city_box=array(''=>'Select City');

		foreach($city_data as $key=>$value){

			$city_box[$value->id]=$value->name;

		}

		//echo '<pre>';print_r($country_box);exit;



		return view('admin.area.add',['city_box'=>$city_box]);

	} 

	public function addareapost(Request $request)

	{

			$validator = Validator::make($request->all(), [

			'name' => 'required',

			'city_id' => 'required',

			]);

			if ($validator->fails()) 

			{//echo '<pre>';print_r($validator->errors());exit;

				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);

			}else

			{

				$Area = new Area();				

				$name =  $request->input('name');	

				$city_id =  $request->input('city_id');	

				$Area->name = $name;

				$Area->city_id = $city_id;

				$Area->save();

				

				echo json_encode(array('class'=>'success','message'=>'Area Added successfully.'));die;

                

				

			}

		

	} 

	

	public function editarea($id)



	{

		$id = base64_decode($id);

		$area_data = Area::find($id);

		$city_data = City::orderBy("name","DESC")->get();

		$city_box=array(''=>'Select City');

		foreach($city_data as $key=>$value){

			$city_box[$value->id]=$value->name;

		}

		//echo '<pre>';print_r($country_box);exit;

		return view('admin.area.edit',["area_data" => $area_data,'city_box'=>$city_box]);



	}

	



	public function editareapost(Request $request)



	{

		$id = $request->input('id');

		 $id =  base64_decode($id);

		$validator = Validator::make($request->all(), [

			'name' => 'required',

			'city_id' => 'required',



			 ]);



			  if ($validator->fails()) 

			  {

					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);

			}else

			{

				$Area = Area::find($id);

			

				$name =  $request->input('name');

				$city_id =  $request->input('city_id');

				$Area->name = $name;

				$Area->city_id = $city_id;

                $Area->save();

				echo json_encode(array('class'=>'success','message'=>'Area Edit successfully.'));die;



			

			}	



	}







	public function areastatus(Request $request)

	{

		 $id = base64_decode($request->input('id'));

		$areadata = Area::find($id);

		

		if($areadata->status=="1")

		{

			$areadata->status = "0";

			$areadata->save();



			echo json_encode(array('class'=>'success','message'=>'Area Deactive successfully.'));die;





		}else

		{

			$areadata->status = "1";

			$areadata->save();

			echo json_encode(array('class'=>'success','message'=>'Area Active successfully.'));die;

		

			  

		}

		

	}



	// public function arearemove(Request $request)

	// {

    //      $id = base64_decode($request->input('id'));

    //     $Area = Area::find($id);

    //     $Area->delete();



    //     return response()->json(['success'=>true ,'message'=>'<div class="alert alert-success alert-dismissible">

    //     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    //     <h4><i class="icon fa fa-check"></i> Alert!</h4>

    //     Area Remove Successfully

    //   </div>']);



	//    }

	

}



?>