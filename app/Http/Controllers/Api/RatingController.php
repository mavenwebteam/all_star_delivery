<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Order;
use App\User;
use App\Models\Stores;
use App\Http\Requests\api\ProductRatingRequest;
use App\Http\Requests\api\RatingRequest;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;

class RatingController extends BaseController
{
    /**
     * Save rating respect ot order item.
     * @param orderId
     * @param productId
     * @return \Illuminate\Http\Response
     */
    public function productRating(ProductRatingRequest $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            OrderItem::where('order_id',$request->order_id)
            ->where('product_id',$request->product_id)
            ->update(['product_rating'=> $request->rating]);

            $ratingSum = OrderItem::where('product_id',$request->product_id)->sum('product_rating');

            $numOfRating = OrderItem::where('product_id',$request->product_id)
            ->whereNotNull('product_rating')
            ->count();
            $avgRating = $numOfRating ? round($ratingSum/$numOfRating) : 0;
            $product = Product::find($request->product_id);
            $product->rating = $avgRating;
            $product->save();
            return $this->sendResponse([], trans('message.FEEDBACK'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


   

    /**
     * Save rating respect ot store and driver.
     * @param orderId
     * @param driverId
     * @param storeId
     * @param ratings for store and driver
     * @return \Illuminate\Http\Response
     */
    public function ratingSave(RatingRequest $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            Order::where('id',$request->order_id)
            ->update([
                'driver_rating'=> $request->driver_rating,
                'store_rating'=> $request->store_rating
            ]);

            // ----update driver avg rating-------
            $driverRatingSum = Order::where('driver_id',$request->driver_id)->sum('driver_rating');
            $driverNumOfRating = Order::where('driver_id',$request->driver_id)
            ->whereNotNull('driver_rating')
            ->count();
            $avgRating = $driverNumOfRating ? round($driverRatingSum/$driverNumOfRating) : 0;
            $driver = User::find($request->driver_id);
            $driver->rating = $avgRating;
            $driver->save();

            // ----update store avg rating-------
            $ratingSum = Order::where('store_id',$request->store_id)->sum('store_rating');
            $numOfRating = Order::where('store_id',$request->store_id)
            ->whereNotNull('store_rating')
            ->count();
            $avgRating = $numOfRating ? round($ratingSum/$numOfRating) : 0;
            $store = Stores::find($request->store_id);
            $store->rating = $avgRating;
            $store->save();
            return $this->sendResponse([], trans('message.FEEDBACK'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



}
