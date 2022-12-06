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
use App\Models\BusinessCategory;
use App\Models\Package;
use App\Models\DeliverySlot;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
use Image,Input;

class StoreController extends Controller {
    
	public function editstore()
	{
		$vendor = Auth::user();
		$storedata = Stores::where('user_id',Auth::user()->id)->first();
		$businessCategory = BusinessCategory::where('status','1')->get();
		$countryList = DB::table('countries')->orderBy('name','ASC')->get();	
		return view('vendor.store.edit',["storedata" => $storedata,'businessCategory'=>$businessCategory,'countryList'=> $countryList, 'vendor'=>$vendor]);
	}

	public function editstorepost(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:50',
            'name_burmese' => 'required|max:50',
            'description' => 'required|string|max:700',
            'description_burmese' => 'required|string|max:700',
            'email' => 'required|email',
            'mobile' => 'required|regex:/[0-9]{9}/|min:7|max:15',
            'address' => 'required',
            'latitude' => 'required|numeric', 
            'longitude' => 'required|numeric', 
            'logo' => 'nullable|mimes:jpeg,jpg,png,svg|max:2048', 
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'country_code' => 'required',
			'open_at' => 'required',
			'close_at' => 'required|after:open_at',
			'city' => 'required|max:30|regex:/^[\pL\s\-]+$/u|min:2',
			'business_category_id' => 'required|exists:business_category,id'
		],
		[
			'close_at.after'=> 'Close time must be time after open time',
			'latitude.required' => 'Business address not getting proper, try again!',
		])->validate();

		
			$status = 0;
			$is_approved = 0;
			$store = Stores::where('user_id',Auth::user()->id)->first();
			if($store)
			{
				$status = $store->status;
				$is_approved = $store->is_approved;
			}
			$store = Stores::updateOrCreate([
				'user_id'   => Auth::user()->id,
			],[
				'email' => $request->input('email'),
				'mobile' => $request->input('mobile'),
				'name' => $request->input('name'),
				'name_burmese' => $request->input('name_burmese'),
				'description' => $request->input('description'),
				'description_burmese' => $request->input('description_burmese'),
				'user_id'     => Auth::user()->id,
				'closing_day' => $request->input('closing_day'),
				'delivery_radius' => $request->input('delivery_radius'),
				'open_at'  =>  $request->input('open_at'),
				'close_at' =>  $request->input('close_at'),
				'address'  =>  $request->input('address'),
				'city'  =>  $request->input('city'),
				'lat'  =>  $request->input('latitude'),
				'lng'  =>  $request->input('longitude'),
				'country_code'  =>  $request->input('country_code'),
				'business_category_id'  =>  $request->input('business_category_id'),
				'status'  =>  $status,
				'is_approved'  =>  $is_approved
			]);
			
			$image = $request->file('image');
			if (isset($image)) {
                $image_path = public_path('media/store/thumb/'.$store->image);
				parent::deleteFile($image_path);
                $image_path = public_path('media/store/'.$store->image);
				parent::deleteFile($image_path);
                $imageName = time().'-'.$image->getClientOriginalName();
                $imageName = str_replace(" ", "", $imageName);
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/store/thumb/' . $imageName));
                $image->move(public_path() . '/media/store', $imageName);
                $store->image = $imageName;
            }
            $logo = $request->file('logo');
		
            if (isset($logo)) {
                $image_path = public_path('media/store/thumb/'.$store->store_logo);
				parent::deleteFile($image_path);
                $image_path = public_path('media/store/'.$store->store_logo);
				parent::deleteFile($image_path);
                $imageName = time() . $logo->getClientOriginalName();
                $imageName = str_replace(" ", "", $imageName);
                $image_resize = Image::make($logo->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/store/thumb/' . $imageName));
                $logo->move(public_path() . '/media/store', $imageName);
                $store->store_logo = $imageName;
            }	
			$store->save();
			Session::flash('success', trans('vendor.store_updated_success'));			
			return redirect()->back();	
	}
	
	/**
	 * Search store for select2 dropdown
	 * use in Order filter by store
	 * */ 
	public function getState($id = null) { 
		$stateList = DB::table('states')->where('country_id',$id)->pluck('name','id')->all();
		return view('vendor.store.state',["stateList" => $stateList]);
	}

	public function getCity($id = null) { 
		$cityList = DB::table('cities')->where('state_id',$id)->pluck('name','id')->all();
		//return  View::make('admin.store.city',compact("cityList"));
			return view('vendor.store.city',["cityList" => $cityList]);
	}

	/**
	 * Change store online/offline
	 * */ 
	public function storeOnOff(Request $request) { 
		$store = Stores::where('user_id', $request->id)->first();
		$state = $request->is_open ? 1 : 0;

		if($store){
			$store->is_open = $state;
			$store->save();
			$msg = trans('vendor.store_is_offline');
			if($state){
				$msg = trans('vendor.store_is_online');
			}
			return response()->json(['class'=>'success','message'=>$msg]);
		}
		return response()->json(['class'=>'error','message'=>'Store not found']);
	}
}
