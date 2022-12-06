<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Settings;
use Session;

class SettingsController extends Controller {

	public function index()
	{
		$setting = Settings::first();
		return view('admin.settings.edit', compact('setting'));	
	}

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'tax' => 'required|numeric|between:1,100',
			'min_order_amount_for_delivery' => 'required|numeric|between:1,500',
			'driver_range_1' => 'required|numeric|between:1,50',
			'driver_range_2' => 'required|numeric|between:1,50',
			'driver_range_3' => 'required|numeric|between:1,5000',
			'video_customer' => 'nullable|string',
			'video_vendor' => 'nullable|string',
			'video_driver' => 'nullable|string',
		])->validate();		

		$setting = Settings::find($id);
		$setting->tax = $request->tax;
		$setting->driver_range_1 = $request->driver_range_1;
		$setting->driver_range_2 = $request->driver_range_2;
		$setting->driver_range_3 = $request->driver_range_3;
		$setting->video_customer = $request->video_customer;
		$setting->video_vendor = $request->video_vendor;
		$setting->video_driver = $request->video_driver;
		$setting->min_order_amount_for_delivery = $request->min_order_amount_for_delivery;
		$setting->save();
		Session::flash('success', 'Setting has been updated successfully');
		return redirect()->back();
	}
}

