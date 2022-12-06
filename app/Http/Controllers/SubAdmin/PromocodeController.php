<?php

namespace App\Http\Controllers\SubAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use App\Models\BusinessCategory;
use App\Constants\Constant;
use Validator;
use Image;

class PromocodeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $promocodes = Promocode::with('businessCategory')->whereNull('deleted_at');
        if(!empty($request->status)){
            if($request->status == 'Active'){
                $promocodes = $promocodes->where('status', '1');
            }else{
                $promocodes = $promocodes->where('status', '0');
            }
        }
       
        if(!empty($request->date)){
            $startDate = date('Y-m-d H:i:s', strtotime($request->date));
            $promocodes = $promocodes->whereDate('start_date', '<=', $startDate)
                                     ->whereDate('end_date', '>=', $startDate);
        }
        $promocodes = $promocodes->orderBy('id','DESC')
                        ->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if($request->ajax()){
            return view('sub_admin.promocode.search', compact('promocodes'));
        }
        
        return view('sub_admin.promocode.show', compact('promocodes'));
    }

    /**
     * Create a promocode| show promocode form modal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $businessCategories = BusinessCategory::where('status','1')
        ->where('is_deleted','0')
        ->get();

        return view('sub_admin.promocode.add', compact('businessCategories'));
    }

    public function store(Request $request)
	{    
		$validator = Validator::make($request->all(), [
			'business_category_id' => 'required|exists:business_category,id',
			'image'	    => 'required|mimes:jpeg,png,jpg,gif|max:2048',
			'title'	    => 'required|max:30|min:2',
			'description' => 'nullable|min:2|max:1000',
			'code'	 => 'required|min:5|max:10',
			'start_date' => 'required|before_or_equal:end_date',
			'end_date'	 => 'required|after_or_equal:start_date',
			'discount_present' => 'required|numeric|between:0,100',
			'cap_limit' => 'required|numeric|between:1,999999',
			'total_no_of_times_use' => 'required|numeric|between:1,999999',
			'no_of_times_for_same_user' => 'required|numeric|between:1,999999',
			'no_of_times_in_each_day' => 'required|numeric|between:1,999999',
			]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}

			$data = $request->all();

			$data['status'] = 1;
			$data['start_date'] = date("Y-m-d",strtotime($request->start_date));
			$data['end_date'] = date("Y-m-d",strtotime($request->end_date));
			$image = $request->file('image');
			if(isset($image))
			{        
				$imageName = time().$image->getClientOriginalName();
				$imageName = str_replace(" ", "", $imageName);
				$image_resize = Image::make($image->getRealPath());              
				$image_resize->resize(118, 118);
				$image_resize->save(public_path('media/promocode/thumb/' .$imageName));
				$image->move(public_path().'/media/promocode/', $imageName);
				$data['image'] = $imageName;
			}
			$driver = Promocode::create($data);
		    return response()->json(['success'=>true, 'message'=> 'Promocode has been added successfully'],200);
	}

    /**
     * edit promocode.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $promocode = Promocode::find($id);
        $businessCategories = BusinessCategory::where('status','1')
        ->where('is_deleted','0')
        ->get();

        return view('sub_admin.promocode.edit', compact('promocode','businessCategories'));
    }

    /**
     * update promocode
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
			'business_category_id' => 'required|exists:business_category,id',
			'image'	    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
			'title'	    => 'required|max:30|min:2',
			'descripton' => 'nullable|min:2|max:1000',
			'code'	 => 'required|min:2|max:10',
			'start_date' => 'required|before_or_equal:end_date',
			'end_date'	 => 'required|after_or_equal:start_date',
			'discount_present' => 'required|numeric|between:0,100',
			'cap_limit' => 'required|numeric|between:1,999999',
			'total_no_of_times_use' => 'required|numeric|between:1,999999',
			'no_of_times_for_same_user' => 'required|numeric|between:1,999999',
			'no_of_times_in_each_day' => 'required|numeric|between:1,999999',
			]);
			if ($validator->fails()) 
			{
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}

            $promocode = Promocode::find($id);

            $promocode->title = $request->title;
            $promocode->description = $request->description;
            $promocode->business_category_id = $request->business_category_id;
            $promocode->code = $request->code;
            $promocode->start_date = date("Y-m-d",strtotime($request->start_date));
            $promocode->end_date = date("Y-m-d",strtotime($request->end_date));
            $promocode->discount_present = $request->discount_present;
            $promocode->cap_limit = $request->cap_limit;
            $promocode->total_no_of_times_use = $request->total_no_of_times_use;
            $promocode->no_of_times_for_same_user = $request->no_of_times_for_same_user;
            $promocode->no_of_times_in_each_day = $request->no_of_times_in_each_day;

			$image = $request->file('image');
            if(isset($image))
			{      
                $oldFilethumb = public_path().'/media/promocode/thumb/'.$promocode->image;
                $oldFile = public_path().'/media/promocode/'.$promocode->image;
				$imageName = time().$image->getClientOriginalName();
				$imageName = str_replace(" ", "", $imageName);
				$image_resize = Image::make($image->getRealPath());              
				$image_resize->resize(118, 118);
				$image_resize->save(public_path('media/promocode/thumb/' .$imageName));
				$image->move(public_path().'/media/promocode/', $imageName);
				$promocode->image = $imageName;
                // delete old file
                parent::deleteFile($oldFilethumb);
                parent::deleteFile($oldFile);
			}
            $promocode->save();
			
		    return response()->json(['success'=>true, 'message'=> 'Promocode has been updated successfully'],200);

    }


    /**
     * Update promocode status using ajax
     * */ 
    public function updateStatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$promocodeData = Promocode::find($id);
		
		if($promocodeData->status=="1")
		{
			$promocodeData->status = "0";
			$promocodeData->save();
			return response()->json(['class'=>'success','message'=>'Promocode has been deactivated']);
		}else
		{
			$promocodeData->status = "1";
			$promocodeData->save();
			return response()->json(['class'=>'success','message'=>'Promocode has been activated']);
		}
	}

    public function destroy($id)
	{   
		$id = base64_decode($id);
		$promocode = Promocode::destroy($id);
		return response()->json(['class'=>'success','message'=> 'Promocode has been removed.']);
	}
}
