<?php 
namespace App\Http\Controllers\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\User;
use App\Models\Stores;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Unit;
use App\Models\ItemCategory;
use Hash;
use Auth;
use DB;
use Session;
use Image;
use App\Constants\Constant;
use App\Helpers\Helper;


class ProductController extends Controller {

	public function index(Request $request, $id = null)
	{
		if($request->ajax()){
			$query = Product::query()->whereNull('deleted_at')->where('vendor_id',Auth::id());
			$query = $query->when(request('name') != '', function ($q) {
				$q->where('name_en', 'like', "%".request('name')."%");
				$q->orWhere('name_br', 'like', "%".request('name')."%");
			});
			$query = $query->when(request('uu_id') != '', function ($q) {
				return $q->where('uuid', '=', request('uu_id'));
			});
			$query = $query->when(request('item_category') != NULL, function ($q) {
				return $q->where('item_category_id', '=', request('item_category'));
			});
			$products = $query->with(['images','itemCategory'])->paginate(Constant::VENDOR_RECORD_PER_PAGE);
			return view('vendor.product.search', compact('products'));	
			die;
		}
		$products = Product::with(['images','itemCategory'])->where('vendor_id',Auth::id())->whereNull('deleted_at');
		// 1=> In stock, 2 => out of stock
		if(!empty($request->stock)){
			if($request->stock == 2){
				$products = $products->where(function ($query) use($request) {
					$query->where('available_qty','0');
					$query->orWhere('in_stock','0');
				});
			}else{
				$products = $products->where('available_qty','>','0')->where('in_stock','1');
			}
			
		}
		$products = $products->paginate(Constant::VENDOR_RECORD_PER_PAGE);
		$itemCategory = parent::getStoreItemCategory();
		return view('vendor.product.show', compact('products','itemCategory'));
	}



	public function create(Request $request)
	{   
		$store = Stores::where('user_id',Auth::id())->first();
		
		if($store)
		{
			$businessCategory = $store->business_category_id;
			$itemCategory = ItemCategory::where('status','1')
			->where('is_deleted','0')
			->where('category_id',$store->business_category_id)
			->orderBy('name_en','ASC')->get();
			$units = Unit::where('status','1')->get();
			return view('vendor.product.add',compact('itemCategory','units','businessCategory'));			

		}else{
			return response()->json(['warning' => trans('vendor.store_not_found')]);
		}
		return view('vendor.product.add',['weight_box'=>$weight_box,'outlate_box'=> $outlate_box,'categoryList'=> $categoryList]);
    } 
	
	
    
    
	public function store(Request $request)
	{     
		$validator = Validator::make($request->all(), [
		
			'name_en' 	=> 'required|regex:/^[\pL\s\-]+$/u|max:70',
			'name_br' 	=> 'required|max:70',
			'item_category_id' => 'required|integer',
			'price' 	=> 'required|numeric|min:1|max:99999',
            'discount_present' => 'nullable|numeric|min:1|max:99',
			'total_qty' 	 => 'required|numeric|min:1|max:999999',
			'available_qty'  => 'required|numeric|min:1|max:999999',
			'description_en' => 'required|max:2000',
			'description_br' => 'required|max:2000',
			'unit_id' 	         => 'required|exists:units,id',
			'size' 	         => 'required|numeric',
			'ref_id' 	     => 'nullable|string',
			'product_images' =>'required',
			],[
				'unit_id.required'=> 'The item unit is required.',
				'item_category_id.required'=> 'The Item category field is required.',
				]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$data = $request->all();
				unset($data['product_images']); 
				$vendor_id = Auth::user()->id; 
				$store = DB::table('stores')->where('user_id', $vendor_id)->first();
				
				$data['vendor_id'] = $vendor_id;
				$data['store_id'] = $store->id;
				$data['in_stock'] = request('available_qty') ? 1 : 0;
				$data['uuid'] = parent::generateUniqueId();
				if(!empty($request->discount_present))
				{
					$discountedPrice = Helper::getDiscountPrice($request->price, $request->discount_present);
					$data['discounted_price'] = $discountedPrice ? $discountedPrice : NULL;
				}

				$Product = Product::create($data);
                $last_insert_product_id=$Product->id;

                $image = $request->file('product_images');
				
				
				if(isset($image))
					{
                        foreach($image as $data)
                        {
							$imageName = time().$data->getClientOriginalName();
							$imageName =str_replace(" ", "", $imageName);
							$image_resize = Image::make($data->getRealPath());              
							$image_resize->resize(130, 130);
							$image_resize->save(public_path('media/products/thumb/' .$imageName));
                            $data->move(public_path().'/media/products', $imageName);
                            $Productimages = new ProductImage();
                            $Productimages->product_id =  $last_insert_product_id;
                            $Productimages->image = $imageName;
                            $Productimages->save();
                        }
                    }
                echo json_encode(array('class'=>'success','message'=>trans('vendor.product_added_success')));die;
			}
	} 
	


	public function edit($id)
	{
		$store = Stores::where('user_id',Auth::id())->first();
		if($store)
		{
			$productId = $id;
        	$productData = Product::find($productId);
			$product_image_data = ProductImage::where('product_id',$productData->id)->orderBy("created_at","DESC")->get();
			$businessCategory = $store->business_category_id;
			$itemCategory = ItemCategory::where('status','1')
			->where('is_deleted','0')
			->where('category_id',$store->business_category_id)
			->orderBy('name_en','ASC')->get();
			$units = Unit::where('status','1')->get();
			return view('vendor.product.edit',compact('itemCategory','units','businessCategory','productData','product_image_data'));
		}else{
			return response()->json(['warning' => trans('vendor.store_not_found')]);
		}
    }


	public function update(Request $request, $id)
	{  
		// dd($request->all());
		$validator = Validator::make($request->all(), [
			'name_en' 	     => 'required|regex:/^[\pL\s\-]+$/u|max:70',
			'name_br' 	     => 'required|max:70',
			'item_category_id' => 'required|integer',
			'price' 	     => 'required|numeric|min:1|max:99999',
			'discount_present' => 'nullable|numeric|min:1|max:99',
			'total_qty' 	 => 'required|numeric|min:1|max:999999',
			'available_qty'  => 'required|numeric|min:1|max:999999',
			'description_en' => 'required|max:2000',
			'description_br' => 'required|max:2000',
			'unit_id' 	     => 'required|exists:units,id',
			'size' 	         => 'required|numeric',
			'ref_id' 	     => 'nullable|string',
			'product_images' =>'nullable',
		],[
			'unit_id.required'=> 'The item unit is required.',
			'item_category_id.required'=> 'The Item category field is required.',
		]);
		if ($validator->fails()) 
		{
			return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
		}else
		{
			$data = $request->all(); 
			$vendor_id = Auth::user()->id; 
			$store = DB::table('stores')->where('user_id', $vendor_id)->first();
			$data['in_stock'] = request('available_qty') ? 1 : 0;
			if(empty($request->discount_present))
			{
				$data['discounted_price'] = NULL;
			}else{
				$discountedPrice = Helper::getDiscountPrice($request->price, $request->discount_present);
				$data['discounted_price'] = $discountedPrice;
			}
			$Product = Product::find($id)->update($data);
			$last_insert_product_id = $id;
			$image = $request->file('product_images');

			if(isset($image))
			{
				foreach($image as $data)
				{
					$imageName = time().$data->getClientOriginalName();
					$imageName =str_replace(" ", "", $imageName);
					$image_resize = Image::make($data->getRealPath());              
					$image_resize->resize(130, 130);
					$image_resize->save(public_path('media/products/thumb/' .$imageName));
					$data->move(public_path().'/media/products', $imageName);
					$Productimages = new ProductImage();
					$Productimages->product_id =  $last_insert_product_id;
					$Productimages->image = $imageName;
					$Productimages->save();
				}
			}
			echo json_encode(array('class'=>'success','message'=>trans('vendor.product_updated')));die;
		}	
	}




    public function ajaximagedelete(Request $request)
	{
		$id = $request->input('id');
        $Productimagesdata = ProductImage::find($id);
		if (!empty($Productimagesdata)) {
            $Productimagesdata->delete();
            echo json_encode(array('class'=>'success','message'=>trans('vendor.image_has_been_deleted')));die;
        } else {
            echo json_encode(array('class'=>'error','message'=>trans('vendor.something_went_wrong')));die;
        }
    }



	public function productstatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$productdata = Product::find($id);
		
		if($productdata->status=="1")
		{
			$productdata->status = "0";
			$productdata->save();
			return response()->json(['class'=>'success','message'=>trans('vendor.product_deactiveted')]);
			

		}else
		{
			$productdata->status = "1";
			$productdata->save();
			return response()->json(['class'=>'success','message'=>trans('vendor.product_activeted')]);
			
			  
		}
		
	}

	public function updateStockStatus(Request $request)
	{
		$id = base64_decode($request->input('id'));
		$productdata = Product::find($id);
		
		if($productdata->in_stock=="1")
		{
			$productdata->in_stock = "0";
			$productdata->save();
			return response()->json(['class'=>'success','message'=>trans('vendor.item_marked_out_of_stock')]);
		}else
		{
			$productdata->in_stock = "1";
			$productdata->save();
			return response()->json(['class'=>'success','message'=>trans('vendor.item_marked_in_stock')]);
		}
		
	}



	public function show($id)
	{
		$productId = base64_decode($id);
		$productData = Product::with(['images','itemCategory','unit'])->find($productId);
		if($productData)
		{
			return view('vendor.product.view',compact('productData'));
		}else{
			return response()->json(['warning' => trans('vendor.store_not_found')]);
		}
	}


	public function destroy($id)
	{   
		$id = base64_decode($id);
		$product = Product::find($id);
		$product->deleted_at = date('Y-m-d H:i:s');
		$product->save();
		return response()->json(['class'=>'success','message'=> trans('vendor.product_deleted')]);
	}
	
}
