<?php

namespace App\Http\Controllers\Admin\v1;

use App\Models\DeliveryFee;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input, Validator;
use App\Constants\Constant;

class DeliveryFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delivery_max_radius = Settings::select('delivery_max_radius as radius')->first();
        $radius = $delivery_max_radius->radius;
        //-------customer delivery fee-----------
        $customerFee = DeliveryFee::where('fee_for','CUSTOMER')->first();
        //-------driver delivery fee-----------
        $driverFee = DeliveryFee::where('fee_for','DRIVER')->first();
        
        return view('admin.delivery-fee.show', compact('customerFee', 'driverFee', 'radius'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('admin.delivery-fee.add');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryFee $deliveryFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
		$feeData = DeliveryFee::find($id);
		return view('admin.delivery-fee.edit',["feeData" => $feeData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		// --------calculate max radious---------
        $radius = Settings::select('delivery_max_radius as radius')->first();
          $validator = Validator::make($request->all(), [
              'id'           => 'required',
              'min_distance' => 'required|integer',
              'max_distance' => 'required|integer|gt:min_distance|max:'.$radius->radius,
              'fee'          => 'required|numeric|between:0,9999.99',
              'fee_per_km'   => 'required|numeric|between:0,9999.99',
              ]);
              if ($validator->fails()) 
              {
                return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
              }

              $id = $request->input('id');
		      $feeId =  base64_decode($id); 
              $deliveryFee = DeliveryFee::find($feeId);
              $deliveryFee->min_distance = $request->input('min_distance');
              $deliveryFee->max_distance = $request->input('max_distance');
              $deliveryFee->fee          = $request->input('fee');
              $deliveryFee->delivery_fee_per_km = $request->input('fee_per_km');
              $deliveryFee->save();
              return response()->json(['class'=>'success' ,'message'=>'Fee has been updated successfully.']);
              die;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryFee $deliveryFee)
    {
        //
    }

    /**
     * update delivery max KM radious in settings.
     *
     * @param  Requesr $radius
     * @return \Illuminate\Http\Response
     */
    public function updateDeliveryRadius(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'delivery_max_radius' => 'required|integer',
        ])->validate();

        $is_updated = Settings::where('id',1)->update(['delivery_max_radius' => request('delivery_max_radius')]);

        if($is_updated){
            $request->session()->flash('success', 'Max Delivery radius has been updated successfully.');
			return redirect()->back();
        }else{
            $request->session()->flash('error', 'Something went wrong !');
			return redirect()->back();
        }
    }
    
}
