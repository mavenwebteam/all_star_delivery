<?php

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/test-noti', 'Api\OrderController@testNotification');

Route::group(['middleware' => 'localization'], function () {

    Route::post('/check-company-email', 'apiController@index');
    Route::post('/cdsl-upload-file', 'apiController@uploadfile');
    Route::post('/get-cdsl-info', 'apiController@getcdslinfo');
    Route::post('/get-cdsl-file', 'apiController@getcdslfile');
    // Route::post('/logout', 'apiController@logout'); 
    Route::post('/search_product', 'apiController@searchProduct'); 


    // --------------feb-2021 start-------------------------

    /*
    | for unauthenticated response 
    */ 
    Route::get('/login', function () {
        $response = [
            'message' => 'Your session has been expired'
        ];
        return response()->json($response, JsonResponse::HTTP_UNAUTHORIZED);
    })->name('login');


    Route::post('/login', 'Api\CustomerAuthController@login');

    // --------------Driver Routes---------------
    Route::post('/driver_signup', 'Api\DriverAuthController@signup')->name('api.driver.signup');
    Route::post('/driver_login',  'Api\DriverAuthController@login')->name('api.driver.login');
    Route::post('/driver_otp_verify',  'Api\DriverAuthController@otpVerify')->name('api.driver.otpverify');
    Route::post('/driver_otp_resend',  'Api\DriverAuthController@resendOtp')->name('api.driver.resendotp');
    Route::post('/driver_forgot_password',  'Api\DriverAuthController@forgotPassword')->name('api.driver.forgot_password');
    Route::post('/driver_reset_password',  'Api\DriverAuthController@resetPassword')->name('api.driver.reset_password');

    // ==========home api==============
    Route::get('/home', 'Api\HomeController@home');
    Route::get('/dashboard', 'Api\HomeController@dashboard');
    Route::post('/update_coordinates', 'Api\HomeController@updateCoordinates');

    // ==========store details api============
    Route::get('/store_details', 'Api\StoreController@storeDetails');
    Route::get('/product_list', 'Api\StoreController@getStoreProducts');
    Route::get('/store_item_search', 'Api\StoreController@storeItemSearch');

    // =========Contact us========================
    Route::get('/get_content', 'Api\StaticContantController@index');

    // --------promocode----------------------
    Route::get('/get-promocode-list', 'Api\PromocodeController@getPromocode');

    Route::middleware('auth:api')->group(function () {
        // --------------customers route---------------
        Route::post('/otp-verify', 'Api\CustomerAuthController@otpVerify');
        Route::post('/resend-otp', 'Api\CustomerAuthController@resendOtp');
        Route::post('/logout', 'Api\CustomerAuthController@logout');
        Route::post('/update-profile', 'Api\CustomerAuthController@updateProfile');
        Route::post('/add_to_cart', 'Api\CartController@store');
        Route::get('/cart_details', 'Api\CartController@index');
        Route::post('/address_add', 'Api\AddressController@addAddress');
        Route::delete('/address_delete', 'Api\AddressController@destroy');
        Route::get('/address_list', 'Api\AddressController@getUserAddresses');
        
        // ----------------Driver Routes-----------
        Route::post('/driver_logout',  'Api\DriverAuthController@logout')->name('api.driver.logout');
        
        // ---------------Favorite Route-------------
        Route::post('/add_to_favorite', 'Api\FavoriteController@addToFavorite');
        Route::delete('/remove_from_favorite', 'Api\FavoriteController@removeFromFavorite');
        Route::get('/favorite_list', 'Api\FavoriteController@favoriteList');

        //-----------Order Route------------------
        Route::get('/checkout', 'Api\OrderController@checkout');
        Route::post('/place-order', 'Api\OrderController@placeOrder');
        Route::post('/re_order', 'Api\OrderController@reOrder');
        Route::get('/order-history', 'Api\OrderController@orderHistory');
        Route::post('/cancel-order', 'Api\OrderController@cancelOrder');
        Route::get('/order_details', 'Api\OrderController@orderDetails');

        // -----------Driver Route---------
        Route::group(['prefix' => 'driver'], function() {
            Route::get('/order-history', 'Api\DriverOrderController@orderHistory');
            Route::get('/order_details', 'Api\DriverOrderController@orderDeatils');
            Route::get('/recent-order', 'Api\DriverOrderController@newOrderRequest');
            Route::post('/order_action', 'Api\DriverOrderController@acceptOrRejectOrder');
            Route::post('/online_offline', 'Api\DriverAuthController@driverOnlineOffline');
            Route::post('/profile_update', 'Api\DriverAuthController@profileUpdate');
            Route::put('/update_order', 'Api\DriverOrderController@updateOrderStatus');
        });

        // ----------- ./Driver Route---------
        Route::group(['prefix' => 'chat'], function() {
            Route::post('/send_message', 'Api\ChatController@chatStore');
            Route::get('/get_messages', 'Api\ChatController@getChatMsgList');
        });
        // ----------Rating Route-----------------
        Route::post('/product-rating', 'Api\RatingController@productRating');
        Route::post('/rating_save', 'Api\RatingController@ratingSave');
        
        //---------Notification-------------------
       
        Route::get('/get-notification-list', 'Api\NotificationController@notificationList');
        Route::post('/notification_read', 'Api\NotificationController@notificationReaded');
        Route::delete('/notification_clear', 'Api\NotificationController@notificationClear');
        Route::post('/notification_on_off', 'Api\NotificationController@notificationOnOff');
    });
});