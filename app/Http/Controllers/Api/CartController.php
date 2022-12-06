<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Product;
use App\Models\Cart;
use App\Http\Requests\api\CartStoreRequest;
use Auth;
use App\Http\Resources\CartResource;
use App\Http\Resources\StoreResource;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $userId = Auth::id();
            $cart = Cart::with(['product','productImg','store'])->where('user_id',$userId)->get();
            // -----check product available in stock and active-------
            $flage = false;
            if(count($cart) > 0){
                $flage = self::chekProductAvailability($cart);
            }
            $msg = "";
            if($flage) {
                $cart = Cart::with(['product','productImg','store'])->where('user_id',$userId)->get();
                $msg = trans('message.CART_ITEMS_UPDATED');
            }
            
            $cartAmount = Cart::where('user_id', $userId)->sum('amount');
            $cartData = CartResource::collection($cart);
            $store = (count($cart) > 0) ? object_get($cart[0], 'store', []) : [];
            $storeData = (object) array();
            if(!empty($store)){
                $storeData = new StoreResource($store);
            }
           
            $data = [
                'cartList' => $cartData,
                'cartTotalAmount' => $cartAmount,
                'store' => $storeData,
                'cart_updated_msg' => $msg,
            ];
            return $this->sendResponse($data, trans('message.GET_DATA'));
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request)
    {
        try{
            if(Helper::apiKeyMatch($request->header('x-api-key')))
            {
                return $this->sendError(trans('message.API_KEY_NOT_MATCH'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY );
                die;
            }
            $userId = Auth::id();
            if($request->qty == 0){
                Cart::where('user_id', $userId)
                ->where('product_id',$request->product_id)
                ->delete();
                return $this->sendResponse([], trans('message.REMOVED_FROM_CART'));
            }
            else{
                $latestPrice = parent::getLatestPrice($request->product_id);
                Cart::updateOrCreate(
                    [
                    'user_id' => $userId,
                    'product_id' => $request->product_id
                    ],
                    [
                    'qty'    => $request->qty,
                    'price'  => $latestPrice,
                    'store_id'  => $request->store_id,
                    'amount' => $latestPrice*$request->qty,
                    ]);
                return $this->sendResponse([], trans('message.SUCCESS'));
            }
            
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * 
     * */ 
    public function chekProductAvailability($cart)
    {
        foreach ($cart as $item){
            $availableQty = object_get($item, 'product.available_qty', 0);
            $inStock = object_get($item, 'product.in_stock', 0);
            $status = object_get($item, 'product.status', 0);
            $flage = false;
            if(($item->qty > $availableQty) || $availableQty == 0 || $inStock == 0 || $status == 0){
                if($availableQty == 0 || $inStock == 0 || $status == 0){
                    Cart::where('id', $item->id)->delete();
                    $flage = true;
                }
                else if($item->qty > $availableQty) {
                    Cart::where('id',$item->id)->update(['qty'=> $availableQty]);
                    $flage = true;
                }
                else{}
            }
        }
        return $flage;
    }

    

}
