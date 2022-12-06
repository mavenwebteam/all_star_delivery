<?php

namespace App\Http\Controllers\SubAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Promocode;
use App\Models\Order;
use App\Models\Stores;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Charts\OrderChart;
use App\Jobs\PushNotification;
use App\Constants\Constant;
use App\Helpers\Helper;


class DashboardController extends Controller
{
    /**
     * Dashboard
     * /sub-admin
     * */ 
    public function index(Request $request)
    {  
		
		$orderMonth = 0;
		$total_user = User::where('role_id','1')->where('is_deleted',0)->count();
		
		$total_store = Stores::where('is_deleted',0)->count();
		
		$total_order = DB::table('orders')->count();
		
		$startDate = Carbon::now()->format('Y-m-d');
		$ongoingOffer = Promocode::whereNull('deleted_at')
           		->where('status', '1')
           		->whereDate('start_date', '<=', $startDate)
          		->whereDate('end_date', '>=', $startDate)
				->count();

		$total_earning = Order::where("status","DELIVERED")->sum('admin_commission_amount');
		// ------order chart-------------
		/**
		 * Line Chart Orders
		*/
		$startDate = Carbon::now()->subMonths(3)->format('Y-m-d');
		$endDate = Carbon::now()->format('Y-m-d');
		$labelArr = array();

		if(!empty($request->start_date) && !empty($request->end_date)){
			$startDate = date('Y-m-d', strtotime($request->start_date));
			$endDate = date('Y-m-d', strtotime($request->end_date));
		}
		

		$result = CarbonPeriod::create($startDate, '1 month', $endDate);
		foreach ($result as $dt) {
			array_push($labelArr, $dt->format("F-Y"));
		}
		$allOrder = [];
		$orderData = Order::where("created_at",">=", $startDate)
		->where("created_at","<=", $endDate)
		->orderBy('created_at','desc')->select('id','created_at')->get()->groupBy(function ($val) {
			return Carbon::parse($val->created_at)->format('M');
		})->toArray();
		foreach($orderData as $key => $val){
			$allOrder[] = count($val);
		}

		$completedOrder = [];
		$orderData = Order::where("created_at",">=",$startDate)
		->where("created_at","<=", $endDate)
		->orderBy('created_at','desc')->where('status','DELIVERED')->select('id','created_at')->get()->groupBy(function ($val) {
			return Carbon::parse($val->created_at)->format('M');
		})->toArray();
		foreach($orderData as $key => $val){
			$completedOrder[] = count($val);
		}

		$canceledOrder = [];
		$orderData = Order::where("created_at",">=",$startDate)
		->where("created_at","<=", $endDate)
		->orderBy('created_at','desc')->where('status','CANCELLED')->select('id','created_at')->get()->groupBy(function ($val) {
			return Carbon::parse($val->created_at)->format('M');
		})->toArray();
		foreach($orderData as $key => $val){
			$canceledOrder[] = count($val);
		}

		$date = Carbon::now();
	
		$orderchart = new OrderChart;
		$orderchart->title('From '.date('d-M-Y',strtotime($startDate)).' to '. date('d-M-Y',strtotime($endDate)));
		$orderchart->labels($labelArr);
		
		$orderchart->dataset('All Orders', 'line', $allOrder)->options([
			'fill' => true,
			'color' =>'#FFC107',
			'borderColor' => '#FFC107'
		]);
		$orderchart->dataset('Completed Orders', 'line', $completedOrder)->options([
			'fill' => true,
			'color' =>'#457fca',
			'borderColor' => '#457fca'
		]);
		$orderchart->dataset('Canceled Order', 'line', $canceledOrder)->options([
			'fill' => true,
			'color' =>'#ec3b57',
			'borderColor' => '#ec3b57'
		]);
		// ------order chart end-------
		return view('sub_admin.dashboard', compact('total_user','total_store','total_order', 'total_earning', 'ongoingOffer','orderchart'));	
	}
}
