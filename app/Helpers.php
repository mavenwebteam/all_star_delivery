<?php 
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\BusinessCategory;
use App\Models\Brands;
use App\Models\Stores;
use App\Models\Orderitems;
use App\Models\Order;
use App\User;
use Carbon\Carbon;

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
class Helpers {
public static function num_format($num=0) {
//setlocale(LC_MONETARY, 'en_IN');
//substr(money_format('%!i',2000000),0,-3);	
$host = request()->getHttpHost();
if($host=="192.168.0.165"){
return number_format($num);	
}else{
return substr(money_format('%!i',$num),0,-3);	
}
}
public static function genrateotp($length=6) {  //Genrate Otp
	 $characters = '3450216987';
     $charactersLength = strlen($characters);
     $randomString = '';

		for ($i = 0; $i < $length; $i++) {

			$randomString .= $characters[rand(0, $charactersLength - 1)];

		}
	return($randomString);
}
 public static function sendsms($mobile,$msg) {  //send sms
 $url = "http://sms.saginfotech.com/sendsms.jsp?user=saginfo&password=766efe0070XX&mobiles=+91".$mobile."&sms=".rawurlencode($msg)."&senderid=SAGRTA";
$response = file_get_contents($url);
return($response);
}   
	
public static function url_format($string) {  //remove all spacial character from string
   $output = preg_replace('!\-!', ' ', $string);
   $output = preg_replace('!\s+!', ' ', $output);
   $string = preg_replace('/\s+/', '-', $output);

   return strtolower(preg_replace('/[^A-Za-z0-9\-\$\&]/', '', $string));
}

public static function getTime($string) {  //get date diffrence in php
   $curdate = date("Y-m-d h:i:s");
$start_date = new \DateTime($string);
$since_start = $start_date->diff(new \DateTime($curdate));
$day = $since_start->days;
$hur = $since_start->h;
$minutes = $since_start->i;
$result = $day."d";
if($day<1)
{
$result = $hur."h";
if($hur<1)
{
$result = $minutes."m";
if($minutes<1)
{
$result = "a moment ago";
}
}
}
return $result;
}	

public static function SelectUserType() { 
  $status=array(''=>'Select User Type','0'=>'Customer','1'=>'Vendor');
return $status;
 
}
public static function SelectCouponType() { 
  $status=array(''=>'Select Discount Type','1'=>'Fixed','2'=>'percent');
return $status;
 
}
public static function SelectUserTypeBoy() { 
  $status=array(''=>'Select User Type','2'=>'Delivery Boy','3'=>'Picker');
return $status;
 
}
public static function GetPaymentMode() { 
   $status=array(''=>'Select Payment Mode','1'=>'COD','2'=>'Card','3'=>'Online');
 return $status;
  
 }
 
 public static function GetSize() { 
   $status=array(''=>'Select Size','1'=>'Small','2'=>'Medium','3'=>'Big');
 return $status;
  
 }
 
 public static function GetPaymentStatus() { 
   $status=array(''=>'Select Payment Status','1'=>'Complete','2'=>'Pending','3'=>'Failed');
 return $status;
  
 }
 
 public static function GetProductVat() { 
   $status=array(''=>'Select Product Vat','7'=>'7 %','19'=>'19 %');
 return $status;
  
 }
 
 
 
 public static function GetDeliveryStatus() { 
   $status=array(''=>'Select Delivery Status','0'=>'Sent to store','1'=>'Accepted by store','2'=>'Preparing order','3'=>'Picked up and flying to you','4'=>'Arrived','5'=>'Deliverd');
 return $status;
  
 }
 
 public static function GetUserType($key) { 
   $status=array('0'=>'Customer','1'=>'Vendor','2'=>'Delivery Boy','3'=>'Picker','4'=>'Outlate');
 return $status[$key];
  
 }
 
 public static function SelectUserStatus() { 
   $status=array(''=>'Select Status','0'=>'Deactive','1'=>'Active');
  return $status;
  
 }
 
  public static function SelectClosingDay() { 
   $status=array(''=>'Select Day','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
  return $status;
  
 }
 
 public static function getDay($key) { 
   $status=array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
 return $status[$key];
  
 }
 
 public static function SelectStockStatus() { 
   $status=array(''=>'Select Stock Status','1'=>'In Stock','2'=>'Out Of Stock');
  return $status;
  
 }
 
 public static function SelectPerPageData() { 
   $status=array(''=>'--Records Per Page--','50'=>'50','100'=>'100','500'=>'500');
   return $status;
  
 }
 public static function GetUserStatus($key) { 
    $status=array('0'=>'Deactive','1'=>'Active');
  return $status[$key];
   
  }
  public static function SelectProductCategoryType() { 
   $type=array(''=>'Select Type','0'=>'Shop','1'=>'Product');
  return $type;
  
 }
 public static function GetProductCategoryType($key) { 
    $type=array('0'=>'Shop','1'=>'Product');
  return $type[$key];
   
  }
  public static function SelectBusinessCategory() { 
    $catedata = BusinessCategory::where('status','1')->orderBy("created_at","DESC")->get();
    $business_category_select_box=array(""=>"Select Business Category");
    foreach ($catedata as $key => $value) {
       $business_category_select_box[$value->id]=$value->name_en;
    }
    return $business_category_select_box;
  //echo '<pre>'; print_r( $product_category_select_box);exit;
   
  }
  public static function SelectProductCategory() { 
   $catedata = Category::where('status','1')->orderBy("created_at","DESC")->get();
   $product_category_select_box=array(""=>"Select Category");
   foreach ($catedata as $key => $value) {
      $product_category_select_box[$value->id]=$value->name;
   }
   return $product_category_select_box;
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 public static function SelectAllCategory() { 
  $catedata = Category::where('status','1')->orderBy("created_at","DESC")->get();
  $product_category_select_box=array(""=>"Select Category");
  foreach ($catedata as $key => $value) {
     $product_category_select_box[$value->id]=$value->name;
  }
  return $product_category_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}

 public static function GetProductCategoryByid($id){ 

        $catedata = Category::select('category.name')->where('id',$id)->first();
       
        return $catedata;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 public static function GetStoreOrdersDetails($store_id){ 

  $total_order = Order::select('id')->where('store_id',$store_id)->count();
  // $total_amount = Order::where('store_id',$store_id)->sum('net_amount');
  $total_amount = 0;
  // $total_commission = Order::where('store_id',$store_id)->sum('admin_commission');
  $total_commission = 0;
  $data=array('total_order'=>$total_order,'total_amount'=>$total_amount,'total_commission'=>$total_commission );
  return $data;

}
 
 public static function GetOutlet($id){ 

        $catedata = User::select('users.id')->where('vendor_id',$id)->whereIn('type',[1,4])->count();
       
        return $catedata;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 public static function GetPicker($id){ 

        $catedata = User::select('users.id')->where('vendor_id',$id)->where('type',3)->count();
       
        return $catedata;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 
 
 
 public static function SelectProductItem($id){ 

        $productdata = Orderitems::select('order_items.*','products.name as productname')
			->leftJoin('products', 'order_items.product_id', '=', 'products.id')->where('order_items.order_id',$id)->get();
       
        return $productdata;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 public static function couponcode($id){ 

        $cur_date = date("Y-m-d");
		
		$couponcodeData = DB::table('coupon_codes')->where('store_id',$id)->where('expires_on', '>=', $cur_date)->first();
		
        return $couponcodeData;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 public static function getDeliveyBoyOrders($id){ 

        $productdata = Order::select('orders.*')
			->where('delivery_boy_id',$id)->where('order_delivery_status','5')->count();
       
        return $productdata;

   
 //echo '<pre>'; print_r( $product_category_select_box);exit;
  
 }
 
 
 public static function GetProductCategoryNameByids($ids=array()) { 
 //dd($ids);
  $catedata = Category::select('category.name')->WhereIn('id',$ids)->get();
  $cat_name="";
  $var='';
  foreach ( $catedata  as $key => $value) {
    $cat_name.=$var.$value->name;
    $var=', ';
  }
  return  $cat_name;
  //echo '<pre>'; print_r( $catedata);exit;
 
}

public static function GetBrand($ids=array()) { 
 //dd($ids);
  $catedata = Brands::select('brands.name')->WhereIn('id',$ids)->get();
  $cat_name="";
  $var='';
  foreach ( $catedata  as $key => $value) {
    $cat_name.=$var.$value->name;
    $var=', ';
  }
  return  $cat_name;
  //echo '<pre>'; print_r( $catedata);exit;
 
}

public static function GetBusinessCategory($ids) { 
  //dd($ids);
   $catedata = BusinessCategory::select('business_category.name_en','business_category.name_burmese')->where('id',$ids)->first();
   return   $catedata->name_en." (".$catedata->name_burmese.")";
   //echo '<pre>'; print_r( $catedata);exit;
  
 }
 
public static function SelectSubProductCategory() { 
  $catedata = Subcategory::orderBy("created_at","DESC")->get();
  $product_category_select_box=array(""=>"Select Category");
  foreach ($catedata as $key => $value) {
     $product_category_select_box[$value->id]=$value->name;
  }
  return $product_category_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function SelectSubProductCategoryByCatid($id) { 
  $catedata = Subcategory::where('id',$id)->orderBy("created_at","DESC")->get();
  $product_category_select_box=array(""=>"Select Category");
  foreach ($catedata as $key => $value) {
     $product_category_select_box[$value->id]=$value->name;
  }
  return $product_category_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function GetProductSubCategoryByid($id) { 
  $catedata = Subcategory::select('sub_category.name')->where('id',$id)->first();
  return $catedata;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function GetProductSubCategoryNameByids($ids=array()) { 

 $catedata = Subcategory::select('sub_category.name')->WhereIn('id',$ids)->get();
 $cat_name="";
 $var='';
 foreach ( $catedata  as $key => $value) {
   $cat_name.=$var.$value->name;
   $var=',';
 }
 return  $cat_name;
 //echo '<pre>'; print_r( $catedata);exit;

}
public static function GetProductBrandByid($id) { 
  $branddata = Brands::select('brands.name')->where('id',$id)->first();
  return $branddata;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function GetProductBrandList() { 
  $branddata = Brands::select('brands.id','brands.name')->where('status','1')->where('is_deleted',0)->get();
  $branddata_select_box=array(""=>"Select Brands");
  foreach ($branddata as $key => $value) {
     $branddata_select_box[$value->id]=$value->name;
  }
  return $branddata_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function GetVendorList() { 
  $vendrodata = User::where('type',1)->where('status','1')->orderBy("first_name","DESC")->get();
  $vendrodata_select_box=array(""=>"Select Vendor");
  foreach ($vendrodata as $key => $value) {
	  
	 if(!empty($value->first_name) || !empty($value->last_name))
	 { 
		$vendrodata_select_box[$value->id]=$value->first_name.' '.$value->last_name;
	 }
  }
  return $vendrodata_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function GetDriverList() { 
  $vendrodata = User::where('type','2')->orderBy("first_name","DESC")->get();
  $driver_select_box=array(""=>"Select Driver");
  foreach ($vendrodata as $key => $value) {
     $driver_select_box[$value->id]=sprintf('%s %s',$value->first_name,$value->last_name);
  }
  return $driver_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}
public static function  distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    
    if ($unit == "K") {
      return ($miles * 1.609344);
      
      
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
public static function GetStoreList() { 
  $storedata = Stores::orderBy("name","DESC")->where('status','1')->get();
  $storedata_select_box=array(""=>"Select Store");
  foreach ($storedata as $key => $value) {
     $storedata_select_box[$value->id]=$value->name;
  }
  return $storedata_select_box;
//echo '<pre>'; print_r( $product_category_select_box);exit;
 
}

public static function isStoreOnline($id) :bool
{ 
  $storedata = Stores::where('user_id', $id)->first();
  if($storedata){
    if($storedata->is_open == 0 || $storedata->is_open == ''){
      return false;
      die;
    }else{
      return true;
      die;
    }
  }else{
    return false;
  }

 
}




}
?>