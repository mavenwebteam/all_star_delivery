<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Constants\Constant;
use App\Models\Order;
use App\Models\Stores;
use App\Models\BusinessCategory;
use App\User;
use App\Charts\OrderChart;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Excel;

class ReportController extends Controller
{
    /**
     * Display report section
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ---------Orders--------
        $orderchart = self::orderChart();

        //--------Earning-----------
        $earningChart = self::earningChart();
        
        // ----------Customer------------
        $customerChart = self::customerChart();

        $activeCustomer = User::where('status', '1')
                            ->where('is_deleted','0')
                            ->where('role_id','1')
                            ->count();
        $activeStore = Stores::where('status', '1')->where('is_deleted', '0')->count();
        $totalOrder = Order::count();
        $activeVendor = User::where('status', '1')
                        ->where('is_deleted','0')
                        ->where('role_id','3')
                        ->count();


        return view('admin.report.index', compact('orderchart', 'earningChart', 'customerChart', 'activeCustomer', 'activeStore', 'totalOrder', 'activeVendor'));
    }

    public function orderChart()
    {
        $deliveredOrder = Order::where('status','DELIVERED')->count();
        $canceledOrder = Order::where('status','CANCELLED')->count();
        $runningOrder = Order::whereNotIn('status',['DELIVERED','CANCELLED'])->count();

        $orderchart = new OrderChart;
        $orderchart->displayAxes(false);
        $orderchart->labels(['On Delivery', 'Delivered', 'Canceled']);
		$dataset = $orderchart->dataset('My dataset', 'pie', array($runningOrder, $deliveredOrder, $canceledOrder));
		$dataset->backgroundColor(collect(['#7158e2','#48BB78', '#F56565']));
		$dataset->color(collect(['#7d5fff','#48BB78', '#F56565']));
        return $orderchart;
    }

    public function earningChart()
    {
        $startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
		$endDate = Carbon::now()->format('Y-m-d');
		$labelArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $year = date('Y');
                
        $orderData = Order::where("status", "DELIVERED")
        ->select(
            DB::raw('sum(admin_commission_amount) as earning'), 
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
        )
        ->whereYear('created_at', $year)
        ->groupBy('monthKey')
        ->orderBy('created_at', 'ASC')
        ->get();

        $data = [0,0,0,0,0,0,0,0,0,0,0,0];

        foreach($orderData as $order){
            $data[$order->monthKey-1] = $order->earning;
        }
        $earningChart = new OrderChart;
		$earningChart->title('Earning report '. $year);
		$earningChart->labels($labelArr);
        $earningChart->dataset('Earning in ks', 'bar', $data)->options(['fill' => true])->color("#4dafe3")->backgroundcolor("#4dafe3");

        return $earningChart;
    }

    public function customerChart()
    {
        $year = date('Y');
		$labelArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $userData = User::where("status", "1")
        ->where("is_deleted", "0")
        ->where("role_id", "1")
        ->select(
            DB::raw('count(*) as total_user'), 
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
        )
        ->whereYear('created_at', $year)
        ->groupBy('monthKey')
        ->orderBy('created_at', 'ASC')
        ->get();
        $data = [0,0,0,0,0,0,0,0,0,0,0,0];
        foreach($userData as $user){
            $data[$user->monthKey-1] = $user->total_user;
        }
        $customerChart = new OrderChart;
		$customerChart->title('Customer report '. $year);
		$customerChart->labels($labelArr);
        $customerChart->dataset('Customer in month', 'line', $data)->options(['fill' => true])->color("#fc388d")->backgroundcolor("#f569a6");

        return $customerChart;
    }

    public function customerView(Request $request)
    {
        $userdata = User::where('is_admin','0')->where('role_id',1)->orderBy("created_at","DESC");
        $start_date = $request->start_date;
		$end_date = $request->end_date;
        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$userdata = $userdata->whereBetween('created_at', [$start_date, $end_date]);
		}
        $userdata = $userdata->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if ($request->ajax()){
            return view('admin.report.user_table', compact('userdata'));
        }
        return view('admin.report.customer', compact('userdata'));
    }

    public function exportCustomer(Request $request)
    {
		$userdata = User::where('is_admin','0')->where('role_id',1)->orderBy("created_at","DESC");
        $start_date = $request->startDate;
		$end_date = $request->endDate;
		if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$userdata = $userdata->whereBetween('created_at', [$start_date, $end_date]);
		}
		$userdata = $userdata->get();
		$export_data=array();
		foreach($userdata as $key=>$value) {
            if($value->status==1) {
                $status="Active";
            }else{
                $status="Deactive";
            }
			$export_data[] = array(
                'first_name'=> $value->first_name,
                'last_name' => $value->last_name,
				'mobile'    => '+'.$value->country_code.' '.$value->mobile,
				'email'     => $value->email,
				'status'    => $status,
				'created_at'=> date("d/M/Y", strtotime($value->created_at))
            );
		}
        $name = 'customer_report_'.date('d-m-Y');
		return Excel::create($name, function($excel) use ($export_data) {
            $excel->sheet('mySheet', function($sheet) use ($export_data)
            {
                $sheet->fromArray($export_data);
            });
        })->download('csv');
    }

    /**
     * Earning report view
     * */ 
    public function earningView(Request $request)
    {
        $earning = Order::with('store')->where('status','DELIVERED');
        $start_date = $request->start_date;
		$end_date = $request->end_date;
		$store_id = $request->store_id;

        if(!empty($store_id)){
            $earning = $earning->where('store_id', $store_id);
        }

        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$earning = $earning->whereBetween('created_at', [$start_date, $end_date]);
		}
        $earning = $earning->paginate(Constant::ADMIN_RECORD_PER_PAGE);

        $stores = Stores::select('id', 'name')->where('is_deleted','0')->orderBy('name', 'ASC')->get();
        if ($request->ajax()){
            return view('admin.report.earning_table', compact('earning', 'stores'));
        }
        return view('admin.report.earning', compact('earning', 'stores'));
    }

    /**
     * Export earning report in csv
    */
    public function exportEarning(Request $request)
    {
		$earning = Order::with('store')->where('status','DELIVERED');
      
		$start_date = $request->startDate;
		$end_date = $request->endDate;
		$store_id = $request->store_id;

        if(!empty($store_id)){
            $earning = $earning->where('store_id', $store_id);
        }

        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$earning = $earning->whereBetween('created_at', [$start_date, $end_date]);
		}
		$earning = $earning->get();
		$export_data = array();
		foreach($earning as $value) {
			$export_data[] = array(
                'order Id'        => $value->order_id,
                'store name'      => object_get($value,'store.name', ''),
				'store mobile'    => '+'.object_get($value,'store.country_code', '').' '.object_get($value,'store.mobile', ''),
				'store email'     => object_get($value,'store.email', ''),
				'store address'   => object_get($value,'store.address', ''),
				'order amount'    => $value->grand_total,
				'admin comission' => $value->admin_commission_amount,
				'order date'      => date("d/M/Y", strtotime($value->created_at))
            );
		}

        $name = 'earning_report_'.date('d-m-Y');
		return Excel::create($name, function($excel) use ($export_data) {
            $excel->sheet('mySheet', function($sheet) use ($export_data)
            {
                $sheet->fromArray($export_data);
            });
        })->download('csv');
    }


    /**
     * Order report view
     * */ 
    public function orderView(Request $request)
    {
        $orders = Order::with('store','user');
        $start_date = $request->start_date;
		$end_date = $request->end_date;
		$status = $request->status;

        if(!empty($status)){
            $orders = $orders->where('status',$status);
        }

        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$orders = $orders->whereBetween('created_at', [$start_date, $end_date]);
		}
        $orders = $orders->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        if ($request->ajax()) {
            return view('admin.report.order_table', compact('orders'));
        }
        return view('admin.report.order', compact('orders'));
    }

    /**
     * Export order report in csv
    */
    public function exportOrder(Request $request)
    {
		$orders = Order::with('store','user');
      
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$status = $request->status;

        if(!empty($status)){
            $orders = $orders->where('status',$status);
        }

        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$orders = $orders->whereBetween('created_at', [$start_date, $end_date]);
		}
		$orders = $orders->get();
		$export_data = array();
		foreach($orders as $value) {
			$export_data[] = array(
                'order Id'        => $value->order_id,
                'store name'      => object_get($value,'store.name', ''),
				'store mobile'    => object_get($value,'store.country_code', '').' '.object_get($value,'store.mobile', ''),
				'store email'     => object_get($value,'store.email', ''),
				'store address'   => object_get($value,'store.address', ''),
				'order amount'    => $value->grand_total,
				'customer name'   => object_get($value,'user.fullName', ''),
				'customer email'  => object_get($value,'user.email', ''),
				'customer mobile' => '+'.object_get($value,'user.country_code', '').' '.object_get($value,'user.mobile', ''),			
				'order date'      => date("d/M/Y", strtotime($value->created_at))
            );
		}

        $name = 'order_report_'.date('d-m-Y');
		return Excel::create($name, function($excel) use ($export_data) {
            $excel->sheet('mySheet', function($sheet) use ($export_data)
            {
                $sheet->fromArray($export_data);
            });
        })->download('csv');
    }

    /**
     * Store report view
     * */ 
    public function storeView(Request $request)
    {
        $stores = Stores::where('is_deleted', '0');
        $start_date = $request->start_date;
		$end_date = $request->end_date;
		$businessCategoryId = $request->store_type;
        if(!empty($businessCategoryId)){
            $stores = $stores->where('business_category_id',$businessCategoryId);
        }
        
        if ($start_date!="" && $end_date!="") {
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$stores = $stores->whereBetween('created_at', [$start_date, $end_date]);
		}
        $stores = $stores->paginate(Constant::ADMIN_RECORD_PER_PAGE);
        $businessCategories = BusinessCategory::where('status','1')->where('is_deleted','0')->get();
        if ($request->ajax()) {
            //dd( $start_date, $end_date);
            return view('admin.report.store_table', compact('stores', 'businessCategories'));
        }
        return view('admin.report.store', compact('stores', 'businessCategories'));
    }

    /**
     * Export store report in csv
    */
    public function exportStore(Request $request)
    {
		$stores = Stores::where('is_deleted', '0');
        $start_date = $request->startDate;
		$end_date = $request->endDate;
		$businessCategoryId = $request->store_type;

        if(!empty($businessCategoryId)){
            $stores = $stores->where('business_category_id',$businessCategoryId);
        }

        if ($start_date!="" && $end_date!="") {
			$start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$stores = $stores->whereBetween('created_at', [$start_date, $end_date]);
		}
		$stores = $stores->get();
		$export_data = array();
		foreach($stores as $value) {
			$export_data[] = array(
                'store name'      => $value->name,
				'store mobile'    => '+'.$value->country_code .'-'. $value->mobile,
				'store email'     => $value->email,
				'store address'   => $value->address,			
				'order date'      => date("d/M/Y", strtotime($value->created_at))
            );
		}
        $name = 'store_report_'.date('d-m-Y');
		return Excel::create($name, function($excel) use ($export_data) {
            $excel->sheet('mySheet', function($sheet) use ($export_data)
            {
                $sheet->fromArray($export_data);
            });
        })->download('csv');
    }

}
