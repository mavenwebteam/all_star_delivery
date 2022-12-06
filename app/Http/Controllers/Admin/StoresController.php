<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\User;
use App\Uniqcode;
use App\Models\Admin;
use App\Models\Stores;
use App\Models\Product;
use App\Models\ItemCategory;
use App\Models\BusinessCategory;
use App\Models\Emailtemplates;

use App\Models\DeliverySlot;
use App\Models\Package;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;
use Image, Input;

class StoresController extends Controller {
    private $admin;
    public function __construct() {
        if (session('admin') ['id']) {
            $admindata = Admin::find(session('admin') ['id']);
            $this->user = $admindata;
        }
    }
    public function index(Request $request) {
        $name = $request->input('name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $storedata = Stores::where('stores.is_deleted', 0)->with(['businessCategory','vendor']);
       
        if ($name != "") {
            $storedata = $storedata->where('stores.name', "like", "%$name%");
        }
        if ($start_date != "" && $end_date != "") {
            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
            $_end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date') . ' 23:59:59'));
            $storedata = $storedata->whereBetween('stores.created_at', [$_start_date, $_end_date]);
        } else if ($start_date != "" && $end_date == "") {
            $_start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
            $storedata = $storedata->where('stores.created_at', ">=", $_start_date)->orderBy("created_at", "DESC");
        } else if ($start_date == "" && $end_date != "") {
            $end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date')));
            $storedata = $storedata->where('stores.created_at', "<=", $end_date)->orderBy("created_at", "DESC");
        }
        $storedata = $storedata->paginate(10);
        if ($request->ajax()) {
            return view('admin.store.search', compact('storedata'));
        }
        $admindata = Admin::find(session('admin') ['id']);
        return view('admin.store.show', compact('storedata', 'admindata'));
    }
    
    public function todayofflinestores(){
        $storedata = Stores::where('stores.is_deleted', 0)->with(['businessCategory','vendor']);
        $storedata = $storedata->where('is_open',"=","close");
        $storedata = $storedata->paginate(10);
        $admindata = Admin::find(session('admin') ['id']);
        return view('admin.store.show', compact('storedata', 'admindata'));
    }
    
    
    public function addstore(Request $request) {
        $userdata = DB::table('users AS u')->select('u.id', 'u.first_name', 'u.last_name')->leftJoin('stores AS s', 's.user_id', '=', 'u.id')->where('u.status', '=', '1')->where('u.role_id', '=', '3')->whereNull('s.user_id')->get();
        
        $user_box = array('' => 'Select Vendor');
        foreach ($userdata as $key => $value) {
            if (!empty($value->first_name) || !empty($value->last_name)) {
                $user_box[$value->id] = $value->first_name . ' ' . $value->last_name;
            }
        }
        $businessCategoryList = DB::table('business_category')->orderBy('name_en', 'ASC')->pluck('name_en', 'id')->toArray();
        $countrydata = DB::table('countries')->orderBy('name', 'ASC')->get();
        // $countrycode_box = array('' => 'Select Country Code');
        // foreach ($countrydata as $key => $value) {
        //     $countrycode_box[$value->phonecode] = $value->phonecode . ' (' . $value->name . ')';
        // }
        return view('admin.store.add', ['user_box' => $user_box, 'businessCategoryList' => $businessCategoryList, 'countryData' => $countrydata]);
    }




    public function getVandor(Request $request)
    {
       
        $page = Input::get('page');
        $term = $request->input('search');
        $resultCount = 8;
        $offset = ($page - 1) * $resultCount;

        $userdata = DB::table('users AS u')->select('u.id', DB::raw("CONCAT(u.first_name,' ',u.last_name) as text")
        )->leftJoin('stores AS s', 's.user_id', '=', 'u.id')->where('u.status', '=', '1')
       // ->where('u.role_id', '=', '3')->whereNull('s.user_id')

        ->when(!empty($term), function ($q) use ($term) {
            return $q->where('u.first_name','like','%'.$term.'%');
            return $q->orWhere('u.last_name','like','%'.$term.'%');
        })
        ->skip($offset)->take($resultCount)
        ->orderBy('text')
        ->get();


        $count = DB::table('users AS u')->select('u.id', DB::raw("CONCAT(u.first_name,' ',u.last_name) as text")
        )->leftJoin('stores AS s', 's.user_id', '=', 'u.id')->where('u.status', '=', '1')
        //->where('u.role_id', '=', '3')->whereNull('s.user_id')
        ->when(!empty($term), function ($q) use ($term) {
            return $q->where('u.first_name','like','%'.$term.'%');
            return $q->orWhere('u.last_name','like','%'.$term.'%');
        })
        ->count();

      

        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count ? false : true;
        return [
                'results' => $userdata,
                "pagination" => array(
                    "more" => $morePages
                  )
            ];
    }





    public function editstore($id) {
        $userId = base64_decode($id);
        $storedata = Stores::find($userId);
        $vendordata = User::find($storedata->user_id);
       
        $userdata = User::where('role_id', '3')->where('status', '1')->orderBy("created_at", "DESC")->get();
        $user_box = array('' => 'Select Vendor');
        foreach ($userdata as $key => $value) {
            if (!empty($value->first_name) || !empty($value->last_name)) {
                $user_box[$value->id] = $value->first_name . ' ' . $value->last_name;
            }
        }
        $countryList = DB::table('countries')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $businessCategoryList = DB::table('business_category')->orderBy('name_en', 'ASC')->pluck('name_en', 'id')->toArray();
        $stateList = DB::table('states')->where('country_id', $storedata->country_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $cityList = DB::table('cities')->where('state_id', $storedata->state_id)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $countrydata = DB::table('countries')->get();
        $countrycode_box = array('' => 'Select Country Code');
        foreach ($countrydata as $key => $value) {
            $countrycode_box[$value->phonecode] = $value->phonecode . ' (' . $value->name . ')';
        }
        return view('admin.store.edit', ["storedata" => $storedata, 'user_box' => $user_box, 'countryList' => $countryList, 'stateList' => $stateList, 'cityList' => $cityList, 'vendordata' => $vendordata, 'countrycode_box' => $countrycode_box, 'businessCategoryList' => $businessCategoryList]);
    }
    
    
    
    public function editstorepost(Request $request) {
        Input::replace($this->arrayStripTags(Input::all()));
        $formData = Input::all();
        Validator::extend('custom_password', function ($attribute, $value, $parameters) {
            if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
                return true;
            } else {
                return false;
            }
        });
        $id = $request->input('id');
        $store_id = base64_decode($id);
        $vendor_id = $request->input('vendor_id');
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|max:50',
            'store_name_burmese' => 'required|max:50',
            'description' => 'required',
            'description_burmese' => 'required',
            'email' => 'required|max:50|email',
            'mobile' => 'required|regex:/[0-9]{9}/|min:7|max:15',
            'user_id' => 'required',
            'business_category_id' => 'required|exists:business_category,id',
            'store_open_time' => 'required', 
            'store_close_time' => 'required|after:open_at', 
            'address' => 'required',
            'city' => 'required|max:30',
            'latitude' => 'required', 
            'longitude' => 'required', 
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country_code' => 'required', 'store_open_time' => 'required', 
            'comission' => 'required|numeric|gte:0|max:99', 
            'store_close_time' => 'required|after:delivery_start_time', 
        ], 
        [
            'user_id.unique' => 'This Vendor already added store', 
            'user_id.required' => 'Please select vendor.',
            'latitude.required' => 'Please fill valid address.', 
            'longitude.required' => 'Please fill valid address.', 
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors() ]);
        } else {
            $store = Stores::find($store_id);
            $store->user_id = $request->input('user_id');
            $store->name = $request->input('store_name');
            $store->name_burmese = $request->input('store_name_burmese');
            $image = $request->file('image');
            if (isset($image)) {
                $image_path = public_path('media/store/thumb/'.$store->image);
				parent::deleteFile($image_path);
                $image_path = public_path('media/store/'.$store->image);
				parent::deleteFile($image_path);
                $imageName = time() . $image->getClientOriginalName();
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
            $store->email = $request->input('email');
            $store->country_code = $request->input('country_code');
            $store->mobile = $request->input('mobile');
            $store->address = $request->input('address');
            $store->city = $request->input('city');
            $store->lat = $request->input('latitude');
            $store->lng = $request->input('longitude');
            $store->business_category_id = $request->input('business_category_id');
            $store->open_at = $request->input('store_open_time');
            $store->close_at = $request->input('store_close_time');
            $store->closing_day = $request->input('closing_day');
            $store->description = $request->input('description');
            $store->description_burmese = $request->input('description_burmese');
            $name = $request->input('store_name');
            $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
            $oldslug = Stores::where('slug', '=', $slug)->where('id', '!=', $id)->first();
            if ($oldslug === null) {
                $newslug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
            } else {
                $newslug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name . '-' . time())));
            }
            $store->slug = $newslug;
            $store->status = '1';
            $store->comission = $request->input('comission');
            $store->save();
            return response()->json(['class' => 'success', 'message' => 'Store Edit Successfully.']);
        }
    }
    
    
    
    public function addstorepost(Request $request) {
        Input::replace($this->arrayStripTags(Input::all()));
        $formData = Input::all();
        Validator::extend('custom_password', function ($attribute, $value, $parameters) {
            if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
                return true;
            } else {
                return false;
            }
        });
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|max:50',
             'store_name_burmese' => 'required|max:50', 
             'description' => 'required', 
             'description_burmese' => 'required', 
             'email' => 'required|max:50|email|unique:stores', 
             'mobile' => 'required|regex:/[0-9]{9}/|min:7|max:15', 
             'user_id' => 'required|unique:stores', 
             'business_category_id' => 'required|exists:business_category,id',
             'store_open_time' => 'required', 
             'store_close_time' => 'required|after:store_open_time', 
             'address' => 'required', 
             'city' => 'required|max:30', 
             'latitude' => 'required', 
             'longitude' => 'required', 
             'logo' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
             'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048', 
             'country_code' => 'required', 
             'comission' => 'required|numeric|gte:0|max:99'
        ],
        [
            'latitude.required' => 'Please fill valid address.', 
            'latitlongitudeude.required' => 'Please fill valid address.', 
            'user_id.unique' => 'This Vendor already added store', 
            'user_id.required' => 'Please select vendor.'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors() ]);
        } else {
            $store = new Stores();
            $store->user_id = $request->input('user_id');
            $store->name = $request->input('store_name');
            $store->name_burmese = $request->input('store_name_burmese');
            $image = $request->file('image');
            if (isset($image)) {
                $imageName = time() . rand(0, 999).'.'.$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/store/thumb/' . $imageName));
                $image->move(public_path() . '/media/store', $imageName);
                $store->image = $imageName;
            }
            $logo = $request->file('logo');
            if (isset($logo)) {
                $imageName = time() . rand(0, 999).'.'.$logo->getClientOriginalExtension();
                $image_resize = Image::make($logo->getRealPath());
                $image_resize->resize(80, 90);
                $image_resize->save(public_path('media/store/thumb/' . $imageName));
                $logo->move(public_path() . '/media/store', $imageName);
                $store->store_logo = $imageName;
            }
            $store->email = $request->input('email');
            $store->country_code = $request->input('country_code');
            $store->mobile = $request->input('mobile');
            $store->address = $request->input('address');
            $store->city = $request->input('city');
            $store->lat = $request->input('latitude');
            $store->lng = $request->input('longitude');
            $store->business_category_id = $request->input('business_category_id');
            $store->open_at = $request->input('store_open_time');
            $store->close_at = $request->input('store_close_time');
            $store->closing_day = $request->input('closing_day');
            $store->description = $request->input('description');
            $store->description_burmese = $request->input('description_burmese');
           
            $name = $request->input('store_name');
            $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
            $oldslug = Stores::where('slug', '=', $slug)->first();
            if ($oldslug === null) {
                $newslug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name)));
            } else {
                $newslug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($name . '-' . time())));
            }
            $store->slug = $newslug;
            $store->status = '1';
            $store->comission = $request->input('comission');
            $store->save();
            return response()->json(['class' => 'success', 'message' => 'Store Add Successfully.']);
        }
    }

    /**
     * @param $id int store id
    */
    
    public function getStoreItems(Request $request, $id) {
        $store = Stores::find($id);
        $itemCategories = ItemCategory::where('category_id', $store->business_category_id)
        ->orderBy('name_en')->get();
        $products = Product::with(['images', 'itemCategory', 'unit'])
        ->where('store_id', $id);
        if(!empty($request->item_name))
		{
			$products = $products->where('name_en', 'like', '%'.$request->item_name.'%');
		}
        if(!empty($request->item_category))
		{ 
			$products = $products->where('item_category_id', $request->item_category);
		}
        $products = $products->paginate(10);

        if ($request->ajax()) 
		{
			return view('admin.store.product-table', compact('products'));
			die;
        }

        return view('admin.store.products', compact('store','products','itemCategories'));
    }


    public function getState($id = null) {
        $stateList = DB::table('states')->where('country_id', $id)->pluck('name', 'id')->all();
        return view('admin.store.state', ["stateList" => $stateList]);
    }
    public function getCity($id = null) {
        $cityList = DB::table('cities')->where('state_id', $id)->pluck('name', 'id')->all();
        //return  View::make('admin.store.city',compact("cityList"));
        return view('admin.store.city', ["cityList" => $cityList]);
    }
    public function viewstore($id) {
        $store_id = base64_decode($id);
        $storedata = Stores::select("stores.*", DB::raw('CONCAT(users.first_name, "  ", users.last_name) AS username'))->leftJoin('users', 'stores.user_id', '=', 'users.id')->where('stores.id', $store_id)->orderBy("stores.created_at", "DESC")->first();
        return view('admin.store.view', ["storedata" => $storedata]);
    }
    public function storestatus(Request $request) {
        $id = base64_decode($request->input('id'));
        $storedata = Stores::find($id);
        if ($storedata->status == "1") {
            $storedata->status = "0";
            $storedata->save();
            return response()->json(['class' => 'success', 'message' => 'Store Deactive Successfully.']);
        } else {
            $storedata->status = "1";
            $storedata->save();
            return response()->json(['class' => 'success', 'message' => 'Store Active Successfully.']);
        }
    }
    public function storeAprove(Request $request) {
        $id = base64_decode($request->input('id'));
        $storedata = Stores::find($id);
        $storedata->is_approved = "1";
        $storedata->status = "1";
        $storedata->save();
        $userdata = User::find($storedata->user_id);
        $userdata->status = "1";
        $userdata->save();
        return response()->json(['class' => 'success', 'message' => 'Store Aprove Successfully.']);
    }
    public function storeDelete(Request $request) {
        $id = base64_decode($request->input('id'));
        $user = Stores::find($id);
        $user->is_deleted = 1;
        $user->save();
        return response()->json(['class' => 'success', 'message' => 'Store Delete Successfully.']);
    }
}
?>