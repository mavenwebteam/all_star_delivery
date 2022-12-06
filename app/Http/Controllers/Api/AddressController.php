<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Address;
use App\Http\Requests\api\AddAddressRequest;
use App\Http\Resources\AddressResource;
use Validator;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
/**
 * Class AddressController
 * @package App\Http\Controllers\Api
 * @version May 18, 2021, 4:00 pm IST
*/

class AddressController extends BaseController
{
    /**
     * add user address in DB
     * * @param AddAddressRequest $request
     * @return \Illuminate\Http\Response
     */
    public function addAddress(AddAddressRequest $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $addressCount = Address::where('user_id',$request->user_id)->count();
            if($addressCount >= 5){
                return $this->sendError(trans('message.ADD_ADDRESS_MAX'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
            }
            $user = Address::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return $this->sendResponse([], trans('message.ADDRESS_ADDED'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * remove address from DB
     * * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:addresses,id'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            Address::destroy($request->id);
            return $this->sendResponse([], trans('message.ADDRESS_DELETED'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Address listing
     * * @param AddAddressRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getUserAddresses(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $addresses = Address::where('user_id',$request->user_id)
                                ->whereNull('deleted_at')
                                ->get();
            $addressResult = AddressResource::collection($addresses);
            $data = [
                'addresses' => $addressResult
            ];              
            return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
