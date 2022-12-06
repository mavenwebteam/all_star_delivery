<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent,App,DB,Config;
class CouponCode extends Model
{

    protected $table = 'coupon_codes';
	
    public function getOffers(){
		
		$date = date('Y-m-d');
		 $date = date('Y-m-d', strtotime($date ));
		
		$couponDesc 	= 	CouponCode::where('status','1')->where('is_deleted',0)->where('expires_on','>',$date)
								->orderBy('created_at','DESC')
								->paginate(10);
		return $couponDesc;
		
	}
}

