<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Cashorderlimit;
use App\Models\City;
use App\Models\Package;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
class PackageController extends Controller {
 
	public function index(Request $request)

	{
		$email = $request->input('email');
		$package_data = Package::select('package.*', 'users.email as vendoremail')
		->leftJoin('users', 'package.vendor_id', '=', 'users.id')	
        ->orderBy("package.created_at","DESC");
        
		if($email!="")
        { $package_data = $package_data->where('users.email',"like","%$email%");
        }
		 $package_data=$package_data->paginate(10);	
		 //echo '<pre>'; print_r($city_data); exit;
		 if ($request->ajax()) 
		{
			return view('admin.package.search', compact('package_data'));  
		}
		
	

        return view('admin.package.show', compact('package_data'));
	}

	public function addpackagelimit(Request $request)
	{
		

		return view('admin.package.add');
	} 
	public function addpackagepost(Request $request)
	{
			$validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'days' => 'required',
			'amount' => 'required',
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
                		

				$Package = new Package();				
                $vendor_id =  $request->input('vendor_id');	

                $Cashorderlimitceck =  Package::select("id")->where('vendor_id',$vendor_id )->first();

                if(!empty($Cashorderlimitceck)){
                    echo json_encode(array('class'=>'success','message'=>'Package Already Added.'));die;
                }else{
                    $amount =  $request->input('amount');	
                    $days =  $request->input('days');	
                    $Package->vendor_id = $vendor_id;
                    $Package->amount = $amount;
                    $Package->days = $days;
                    $Package->save();
                    echo json_encode(array('class'=>'success','message'=>'Package Added successfully.'));die;
                }

				
				
			}
		
	} 
	
	public function editpackage($id)

	{
		$id = base64_decode($id);
		$Packagedata = Package::find($id);
		
		//echo '<pre>';print_r($country_box);exit;
		return view('admin.package.edit',["Packagedata" => $Packagedata]);

	}
	

	public function editpackagepost(Request $request)

	{
		$id = $request->input('id');
		 $id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			'vendor_id' => 'required',
            'days' => 'required',
			'amount' => 'required',

			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$Package = Package::find($id);
			
               			
               $vendor_id =  $request->input('vendor_id');	

               $Cashorderlimitceck =  Package::select("id")->where('id','!=',$id)->where('vendor_id',$vendor_id)->first();

               if(!empty($Cashorderlimitceck)){
                   echo json_encode(array('class'=>'success','message'=>'Package Already Added.'));die;
               }else{
                   $amount =  $request->input('amount');	
                   $days =  $request->input('days');	
                   $Package->vendor_id = $vendor_id;
                   $Package->amount = $amount;
                   $Package->days = $days;
                   $Package->save();
                   echo json_encode(array('class'=>'success','message'=>'Package Edit successfully.'));die;
               }


				
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

	
}

?>