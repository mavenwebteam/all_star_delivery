<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent, App, DB, Config;
use Session;
use App\Models\BusinessCategory;
use App\Models\Product;
use App\Models\Favorites;
use App\User;

class Stores extends Model
{

    protected $table = 'stores';
	protected $guarded = [];  

    public function getStoreImage()
    {
        $storeDesc = Stores::select('image', 'slug', 'id')->where('status', '1')
            ->where('is_deleted', 0);
        //->where('plan_status',1)
        $getLocSession = Session::get('searchAddress');
        if (!empty($getLocSession))
        {
            $current_loc = Session::get('searchAddress');
            $lat = $current_loc['latitude'];
            $long = $current_loc['longitude'];
            $distance_query = '(6371* acos( cos( radians(' . $lat . ') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians(' . $long . ') ) + sin( radians(' . $lat . ') ) * sin( radians( stores.lat ) ) ) )';
            $storeDesc = $storeDesc->select('image', 'slug', 'id', DB::raw($distance_query . ' AS distance'));
            $storeDesc = $storeDesc->orderBy('distance', 'ASC');

        }
        else
        {
            $storeDesc = $storeDesc->orderBy('created_at', 'DESC');
        }

        $storeDesc = $storeDesc->limit(6)
            ->get();

        //echo '<pre>'; print_r($storeDesc); die;
        return $storeDesc;

    }
    public function getStoreDetails()
    {

        $storeDesc = Stores::select('stores.image', 'stores.slug', 'stores.id', 'stores.rating', 'stores.name', 'stores.lat', 'stores.lng')->where('stores.status', '1')
        //->where('stores.plan_status',1)
        
            ->where('is_deleted', 0)
            ->orderBy('stores.created_at', 'DESC')
            ->limit(10)
            ->get();
        return $storeDesc;

    }

    public function getStoreData($slug)
    {
        $storeDesc = Stores::select('image', 'slug', 'id', 'rating', 'name', 'lat', 'lng', 'category_id')->where('slug', $slug)->first();
        return $storeDesc;

    }


    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function favorites()
    {
       return $this->hasMany(Favorites::class, 'store_id');  
    }
  
}

