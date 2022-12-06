<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Requests\api\HomeRequest;
use App\Http\Resources\HomeResource;
use App\Http\Resources\BusinessCategoryResource;
use App\Http\Resources\BannerResource;

use Illuminate\Support\Facades\Auth;
use App\Models\Stores;
use App\Models\Settings;
use App\Models\Banner;
use App\User;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constant;
use DB;


/**
 * Class HomeController
 * @package App\Http\Controllers\Api
 * @version March 3, 2021, 1:00 pm IST
*/

class HomeController extends BaseController
{
    /**
     * Home Api
 
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function home(HomeRequest $request) 
	{
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
			$stores = [];
			$radius = Settings::select('delivery_max_radius')->first();
			$radius = $radius->delivery_max_radius ? $radius->delivery_max_radius : 20;
		
			$latitude  = $request->get('latitude');
			$longitude = $request->get('longitude');
			$search    = $request->get('search');
			$userId    = $request->get('user_id');
		
			$city = $request->get('city');
			$businessCatId = $request->get('business_category_id');
		    $stores = self::getStoreByRadius($latitude, $longitude, $radius, $city, $businessCatId, $search, $userId);
			// dd( $stores);
			$stores = HomeResource::collection($stores);
					
			// $businessCategory = BusinessCategory::where('status',1)->get();
			return $this->sendResponse(($this->__paginate(HomeResource::collection($stores), $stores)), trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
	 * Store By redious
	 * @param int $latitude 
	 * @param int $longitude 
	 * @param int $radius 
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function getStoreByRadius($latitude, $longitude, $radius = 30, $city="",$businessCatId = '', $search='',$userId = null)
	{
		$userId = !empty($userId) ? $userId : '0000';  
		
		$distance_query='(6371* acos( cos( radians('.$latitude.') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( stores.lat ) ) ) )';

	
		$stores = Stores::select('stores.*',DB::raw($distance_query.' AS distance'))
			->where(['is_open'=>'open', 'status'=>'1', 'is_approved'=>'1'])
			->when($userId != null, function ($q) use ($userId) {
				return $q->with(['favorites'=>function ($query) use ($userId){
					$query->where('user_id', $userId);
				}]);
			})
			->when($search != '', function ($q) use ($search) {
				return $q->where('name','like','%'.$search.'%');
			})
			
			->where('stores.business_category_id',$businessCatId)
			
			->where('stores.is_deleted','0')
			->orderBy('distance','ASC')
			->paginate(Constant::API_RECORD_PER_PAGE);
			//->having("distance", "<", $radius)
		return $stores;
	}

	/*
     * Home Api
 
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function dashboard(Request $request) 
	{
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

			$businessCategory = BusinessCategory::where('status','1')->paginate(Constant::API_RECORD_PER_PAGE);
			$businessCategory = $this->__paginate(BusinessCategoryResource::collection($businessCategory), $businessCategory);
			$banners = Banner::where('status','1')->paginate(Constant::API_RECORD_PER_PAGE);
			$banners = $this->__paginate(BannerResource::collection($banners), $banners);
			$data = [
				'banners' => $banners,
				'business_categories' => $businessCategory
			];
			return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


	/**
     * Update driver lat long 
	 * @param id user id
	 * @param latitude 
	 * @param longitude 
     * @return \Illuminate\Http\JsonResponse
    */
    public function updateCoordinates(Request $request) 
	{
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

			$validator = Validator::make($request->all(), [
                'id' => 'required|exists:users,id',
                'latitude' => 'required',
                'longitude' => 'required'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }

			$user = User::find($request->id);
			$user->latitude = $request->latitude;
			$user->longitude = $request->longitude;
			$user->save();
			return $this->sendResponse([], trans('message.SUCCESS'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
