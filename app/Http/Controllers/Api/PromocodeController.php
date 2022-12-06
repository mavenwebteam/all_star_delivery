<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Promocode;
// use App\Http\Requests\api\AddAddressRequest;
use App\Http\Resources\PromocodeResource;
use Validator;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
/**
 * Class HomeController
 * @package App\Http\Controllers\Api
 * @version Jun 17, 2021, 12:00 pm IST
*/

class PromocodeController extends BaseController
{

    /**
     * Promocode listing
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function getPromocode(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $todayDate = Carbon::now()->format('Y-m-d');        
            $promocode = Promocode::whereNull('deleted_at')
                    ->whereDate('start_date', '<=', $todayDate)
                    ->whereDate('end_date', '>=', $todayDate)
                    ->where('status','1')
                    ->get();
            $promocodeResult = PromocodeResource::collection($promocode);
            $data = [
                'promocodes' => $promocodeResult
            ];              
            return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
