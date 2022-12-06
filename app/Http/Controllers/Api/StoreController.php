<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Helpers\Helper;
use App\Http\Requests\api\StoreDetailRequest;
use App\Http\Requests\api\StoreItemRequest;
use App\Http\Requests\api\StoreItemSearchRequest;
use App\Http\Resources\StoreResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ItemCategoryResource;
use App\Models\Stores;
use App\Models\Product;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Validator;
use App\Constants\Constant;
use DB;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api
 * @version March 3, 2021, 1:00 pm IST
*/

class StoreController extends BaseController
{
    /**
     * Home Api
 
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function storeDetails(StoreDetailRequest $request) 
	{
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
			$store = [];
			$itemCategory = [];
			$userId = $request->user_id;

			if(!empty($request->latitude) && !empty($request->longitude))
			{
				$latitude =  $request->latitude;
				$longitude =  $request->longitude;

				$distance_query = '(6371* acos( cos( radians('.$latitude.') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( stores.lat ) ) ) )';

				$store = Stores::select('stores.*',DB::raw($distance_query.' AS distance'))
				->where('id',request('store_id'))
				->first();
			}else{
				$store = Stores::where('id',request('store_id'))->first();
			}
			if($store){
				if(empty($userId)){
					$store->favorites = 0;
				}

				$itemCategory = ItemCategory::where('category_id',$store->business_category_id)->where('status', '1')->get();
				if($itemCategory)
				{
					$itemCategory = ItemCategoryResource::collection($itemCategory);
				}
				$store = new StoreResource($store);
			}
			$data = [
				'itemCategory' => $itemCategory,
				'store' => $store,
			];
			return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

	public function getStoreProducts(StoreItemRequest $request)
	{
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
			$products = [];
			$userId = $request->user_id;
			
			$products = Product::with(['unit','images'])
			->where(['store_id'=>request('store_id'),'item_category_id'=>request('item_category_id')])
			->where('status','1')
			->whereNull('deleted_at')
			->paginate(Constant::API_RECORD_PER_PAGE);
			
			$products = $this->__paginate(ProductResource::collection($products), $products);
			return $this->sendResponse($products, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
	}

	/**
     * Search store item by name
	 * @param storeId
	 * @param keyword search keyword
     * @return \Illuminate\Http\JsonResponse
     */
	public function storeItemSearch(StoreItemSearchRequest $request){
		try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
			$products = Product::with(['unit','images'])
			->where('store_id',$request->store_id)
			->where('name_en', 'LIKE', "%{$request->keyword}%") 
			->where('status','1')
			->whereNull('deleted_at')
			->paginate(Constant::API_RECORD_PER_PAGE);
			$products = $this->__paginate(ProductResource::collection($products), $products);
			return $this->sendResponse($products, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
	}

}
