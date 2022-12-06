<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\api\AuthRequest;
use App\Models\Favorites;
use App\Models\Stores;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use App\Constants\Constant;
use App\Http\Resources\FavoriteStoreResource;
use DB;

class FavoriteController extends BaseController
{
    /**
     * Add to favorate
     * @ @param int $store_id 
     * @ @param int $user_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function addToFavorite(Request $request)
    {             
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'user_id'  => 'required|exists:users,id',
                'store_id' =>  'required|exists:stores,id'
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }

            $exist = Favorites::where('user_id',request('user_id'))
            ->where('store_id',request('store_id'))
            ->count();
            if($exist)
            {
                return $this->sendError(trans('message.ALREADY_EXIST'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
            Favorites::create($request->all());
            return $this->sendResponse([], trans('message.ADD_IN_FAVORITE'));
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove form favorate
     * @ @param int $store_id 
     * @ @param int $user_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function removeFromFavorite(Request $request)
    {             
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'user_id'  => 'required|exists:users,id',
                'store_id' =>  'required|exists:stores,id'
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }

            $isDeleted = Favorites::where('user_id',request('user_id'))
            ->where('store_id',request('store_id'))
            ->delete();
            if($isDeleted)
            {
                return $this->sendResponse([], trans('message.DELETE_FROM_FAVORITE'));
            }
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     *  favorate list
     * @ @param int $store_id 
     * @ @param int $user_id 
     *
     * @return \Illuminate\Http\Response
     */
    public function favoriteList(Request $request)
    {             
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }

            $validator = Validator::make($request->all(), [
                'user_id'  => 'required|exists:users,id',
                'lat'      => 'required',
                'lng'      => 'required',
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );   
            }

            $storesArr = Favorites::where('user_id', request('user_id'))->pluck('store_id')->toArray();
            $stores = self::getStore($storesArr, request('lat'), request('lng'));
            return $this->sendResponse($this->__paginate(FavoriteStoreResource::collection($stores), $stores), trans('message.GET_DATA'));
         
        }catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStore(array $storeId = [], $latitude, $longitude)
    {
        $distance_query='(6371* acos( cos( radians('.$latitude.') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( stores.lat ) ) ) )';

	
		$stores = Stores::select('stores.*',DB::raw($distance_query.' AS distance'))
            ->whereIn('id', $storeId)
			->where('stores.status','1')
			->where('stores.is_deleted','0')
			->orderBy('distance','ASC')
			->paginate(Constant::API_RECORD_PER_PAGE);
		return $stores;
    }

}
