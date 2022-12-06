<?php

namespace App\Http\Controllers\vendor;

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
        //--------Earning-----------
        $earningChart = self::earningChart();
        return view('vendor.report.index', compact('earningChart'));
    }

    public function earningChart()
    {
        $startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
		$endDate = Carbon::now()->format('Y-m-d');
		$labelArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $year = date('Y');
        $storeId = Helper::getStoreId();
        $orderData = Order::where('store_id', $storeId)->where("status", "DELIVERED")
        ->select(
            DB::raw('sum(amount_payable_to_store) as earning'), 
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

    /**
     * Earning report view
     * */ 
    public function earningView(Request $request)
    {
        $storeId = Helper::getStoreId();
        $earning = Order::with('store')->where('store_id', $storeId)->where('status','DELIVERED');
        $start_date = $request->start_date;
		$end_date = $request->end_date;
        
        if ($start_date!="" && $end_date!="") {
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
			$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));
			$earning = $earning->whereBetween('created_at', [$start_date, $end_date]);
		}
        $earning = $earning->paginate(Constant::VENDOR_RECORD_PER_PAGE);
        if ($request->ajax()){
            return view('vendor.report.earning_table', compact('earning'));
        }
        return view('vendor.report.earning', compact('earning'));
    }

    /**
     * Export earning report in csv
    */
    public function exportEarning(Request $request)
    {
        $storeId = Helper::getStoreId();
		$earning = Order::with('store')->where('store_id', $storeId)->where('status','DELIVERED');
      
		$start_date = $request->startDate;
		$end_date = $request->endDate;

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
				'order amount'    => $value->grand_total,
				'earning' => $value->amount_payable_to_store,
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
}
