<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/*delete*/ 

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\AddressController;
use App\Models\Order;
use App\Models\Stores;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\DriverOrder;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\DeliveryFee;
use App\Models\Settings;
use App\Models\Promocode;
use App\User;
use App\Http\Requests\api\CheckoutRequest;
use App\Http\Requests\api\PlaceOrderRequest;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\AddressResource;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Jobs\PushNotification;
use App\Jobs\FindDriver;
use App\Constants\Constant;

/**/ 

include(app_path().'/global_constants.php');
//include(app_path().'/settings.php');

//========set vendor localization===
Route::get('language/{locale}', function ($locale) {
	if(session()->has('lang'))
	{
	 session()->forget('lang');
	}
	session()->put('lang', $locale);
	return redirect()->back();
	// 
 })->name('language');
 //========end locatization===

// --------artisan call------
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
	return "Clear all cache";
});
Route::get('/run_job', function() {
    Artisan::call('queue:work');
	return "Queue work";
});
// ---save FCM web token-------
Route::post('/save-token', function(Request $request) {
   auth()->user()->update(['device_token'=>$request->token, 'device_type'=> 'web']);
    return response()->json(['token saved successfully.']);
})->name('save-token');

// send notification to admin
Route::get('/send-web-notification', 'Admin\AdminController@sendWebNotification');
Route::get('/send-admin-web-notification', 'Admin\AdminController@sendAdminWebNotification');



Route::get('/', function () { return view('welcome'); });
Route::get('/thanks', function () { return view('vendor.thanks'); });
Route::get('/account-activate/{id}', 'homeController@accountactivate');
Route::get('/login', 'Front\homeController@login');
Route::post('/login-post', 'Front\homeController@LoginUser');
Route::get('/logout', 'Front\homeController@logout');
Route::post('/signup-post', 'Front\homeController@signupPost');
Route::get('/user-account-activate/{id}', 'Front\homeController@accountactivateweb');

Route::get('/getdriverbyradius/{latitude}/{longitude}/{orderId}','Api\OrderController@getDriverByRadius');
/*-----------------------------------Admin controllers -----------------------------------------------------------*/
Route::get('/downloadinvoice', 'Admin\EarningController@downloadinvoice');
Route::get('/admin/checkuserlogin', 'Admin\AdminController@checkuserlogin');

Route::prefix('admin')->middleware('CheckLoginUser')->group(function () {
	Route::get('/login', 'Admin\AdminController@login')->name('admin.login');
	Route::post('/loginpost', 'Admin\AdminController@loginpost');
});

Route::get('/admin/forgot-password', 'Admin\ForgotpassController@index');
Route::post('/admin/post-mail', 'Admin\ForgotpassController@postmail');
Route::get('/admin/create-password/{uniqurl}', 'Admin\ForgotpassController@createpass');
Route::post('/admin/password-save', 'Admin\ForgotpassController@createpasspost');

Route::group(['prefix' => 'admin','as' => 'admin.','middleware' => 'adminSecurity'], function () {

	Route::get('/', 'Admin\AdminController@index')->name('dashboard');
	// ---------report route------------
	Route::get('/report', 'Admin\ReportController@index')->name('report');
	Route::get('/report/customer', 'Admin\ReportController@customerView')->name('report.customer');
	Route::get('/report/export-customer', 'Admin\ReportController@exportCustomer')->name('report.exportCustomer');
	Route::get('/report/earning', 'Admin\ReportController@earningView')->name('report.earning');
	Route::get('/report/export-earning', 'Admin\ReportController@exportEarning')->name('report.exportEarning');
	Route::get('/report/order', 'Admin\ReportController@orderView')->name('report.order');
	Route::get('/report/export-order', 'Admin\ReportController@exportOrder')->name('report.exportOrder');
	Route::get('/report/store', 'Admin\ReportController@storeView')->name('report.store');
	Route::get('/report/export-store', 'Admin\ReportController@exportStore')->name('report.exportStore');
	// ---------./report route------------

	Route::get('/profile', 'Admin\AdminController@profile');
	Route::post('/edit-profile-post', 'Admin\AdminController@editprofile');
	Route::get('/delivery-fee', 'Admin\v1\DeliveryFeeController@index');
	Route::PUT('/update-delivery-radius', 'Admin\v1\DeliveryFeeController@updateDeliveryRadius')->name('update.max-rdius');

	Route::get('/add-delivery-fee', 'Admin\v1\DeliveryFeeController@create');
	Route::get('/edit-delivery-fee/{id}', 'Admin\v1\DeliveryFeeController@edit');
	Route::post('/delivery-fee-update', 'Admin\v1\DeliveryFeeController@update');
	Route::post('/delivery-fee-store', 'Admin\v1\DeliveryFeeController@store');


	Route::get('/logout', 'Admin\AdminController@logout');
	Route::get('/change-password', 'Admin\AdminController@changepassword');
	Route::post('/change-password-post', 'Admin\AdminController@changepasswordpost');
	Route::post('/sendemail', 'Admin\AdminController@sendemail');

	Route::get('/setting', 'Admin\SettingsController@setting');
	Route::post('/setting', 'Admin\SettingsController@settingpost');

	Route::get('/site-setting', 'Admin\SettingController@prefix');
	Route::post('/site-setting/{site}', 'Admin\SettingController@updatePrefix');

	// ====================Banner manager==========
	Route::resource('/banners', 'Admin\BannerController');
	Route::post('/banners/list/store-list/{businessCatId}', 'Admin\BannerController@storeList')->name('banners.list.stores');
	Route::post('/banners/status', 'Admin\BannerController@updateStatus');

	//===================admin order manager============
	Route::get('/get-store-list', 'Controller@getStoreList')->name('select2.stores');
	Route::put('/orders/update/status/{id}', 'Admin\OrderController@orderUpdateStatus');
	Route::resource('/orders', 'Admin\OrderController')->only(['index','edit','update','show']);

	Route::get('/orders/index/today', 'Admin\OrderController@showtodayorder');
	Route::get('/orders/index/cancelled', 'Admin\OrderController@todaycancelorder');
	Route::get('/orders/index/notyetaccepted', 'Admin\OrderController@notacceptedorder')->name('orders.notacceptedorder');

	//--------admin Driver feb 2021 start---------
	Route::post('/drivers/status', 'Admin\DriverController@updateStatus');
	Route::resource('/drivers', 'Admin\DriverController');

	//--------admin USERS feb 2021 start---------
	Route::get('/add-user', 'Admin\UsersController@adduser');
	Route::post('/add-user-post', 'Admin\UsersController@adduserpost');
	Route::get('/users', 'Admin\UsersController@index');
	Route::post('/users', 'Admin\UsersController@index')->name('users.index');
	Route::get('/edit-user/{id}', 'Admin\UsersController@edituser');
	Route::post('/edit-user-post', 'Admin\UsersController@edituserpost');
	Route::get('/view-user/{id}', 'Admin\UsersController@viewuser');
	Route::post('/user-status', 'Admin\UsersController@userstatus');
	Route::post('/user-delete', 'Admin\UsersController@userdelete');
	//--------admin USERS feb 2021 end---------

	//--------admin vendor feb 2021 start---------
	Route::get('/vendor', 'Admin\VendorController@index');
	Route::post('/vendor', 'Admin\VendorController@index');
	Route::get('/add-vendor', 'Admin\VendorController@adduser');
	Route::post('/add-vendor-post', 'Admin\VendorController@adduserpost');
	Route::get('/edit-vendor/{id}', 'Admin\VendorController@edituser');
	Route::post('/edit-vendor-post', 'Admin\VendorController@edituserpost');
	Route::get('/view-vendor/{id}', 'Admin\VendorController@viewuser');
	Route::post('/vendor-status', 'Admin\VendorController@userstatus');
	// Route::get('/export-vendor', 'Admin\VendorController@exportusers');
	//--------admin vendor feb 2021 end---------


	Route::get('getstate/{id}','Admin\StoresController@getState');
	Route::get('getcity/{id}','Admin\StoresController@getCity');

	Route::get('/country', 'Admin\CountryController@index');
	Route::post('/country', 'Admin\CountryController@index');
	Route::get('/add-country', 'Admin\CountryController@addcountry');
	Route::post('/add-country-post', 'Admin\CountryController@addcountrypost');
	Route::get('/edit-country/{id}', 'Admin\CountryController@editcountry');
	Route::post('/edit-country-post', 'Admin\CountryController@editcountrypost');
	Route::post('/country-status', 'Admin\CountryController@countrystatus');

	Route::get('/city', 'Admin\CityController@index');
	Route::post('/city', 'Admin\CityController@index');
	Route::get('/add-city', 'Admin\CityController@addcity');
	Route::post('/add-city-post', 'Admin\CityController@addcitypost');
	Route::get('/edit-city/{id}', 'Admin\CityController@editcity');
	Route::post('/edit-city-post', 'Admin\CityController@editcitypost');
	Route::post('/city-status', 'Admin\CityController@citystatus');

	//--------admin dashboard feb 2021 start---------
	##Business Category Route Start
	Route::get('/business-category', 'Admin\BusinessCategoryController@index');
	Route::post('/business-category', 'Admin\BusinessCategoryController@index');
	Route::get('/add-business-category', 'Admin\BusinessCategoryController@addbusinesscategory');
	Route::post('/add-business-category-post', 'Admin\BusinessCategoryController@addbusinesscategorypost');
	Route::get('/edit-business-category/{id}', 'Admin\BusinessCategoryController@editbusinesscategory');
	Route::post('/edit-business-category-post', 'Admin\BusinessCategoryController@editbusinesscategorypost');
	// Route::post('/business-category-status', 'Admin\BusinessCategoryController@businesscategorystatus');
	Route::get('/view-business-category/{id}', 'Admin\BusinessCategoryController@viewbusinesscategory');
	
	//--------item category feb 2021 start---------
	Route::get('/item-category', 'Admin\ItemCategoryController@index');
	Route::post('/item-category', 'Admin\ItemCategoryController@index');
	Route::get('/add-item-category', 'Admin\ItemCategoryController@addcategory');
	Route::post('/add-item-category-post', 'Admin\ItemCategoryController@addcategorypost');
	Route::get('/edit-item-category/{id}', 'Admin\ItemCategoryController@editcategory');
	Route::post('/edit-item-category-post', 'Admin\ItemCategoryController@editcategorypost');
	Route::post('/item-category-status', 'Admin\ItemCategoryController@categorystatus');
	Route::get('/view-item-category/{id}', 'Admin\ItemCategoryController@viewcategory');
	// Route::post('/item-category-remove', 'Admin\ItemCategoryController@categoryremove');

	Route::get('/content', 'Admin\ContentController@index');
	Route::post('/content', 'Admin\ContentController@index');
	Route::get('/add-content', 'Admin\ContentController@addcontent');
	Route::post('/add-content-post', 'Admin\ContentController@addcontentpost');
	Route::get('/edit-content/{id}', 'Admin\ContentController@editcontent');
	Route::post('/edit-content-post', 'Admin\ContentController@editcontentpost');
	Route::post('/content-status', 'Admin\ContentController@contentstatus');
		
	Route::get('/email-templates', 'Admin\EmailTemplatesController@index');
	Route::post('/email-templates', 'Admin\EmailTemplatesController@index');
	Route::get('/edit-email-templates/{id}', 'Admin\EmailTemplatesController@editemailtemplates');
	Route::post('/edit-email-templates-post', 'Admin\EmailTemplatesController@editemailtemplatespost');

	//--------admin store feb 2021 start---------
	Route::get('/store', 'Admin\StoresController@index')->name('store.index');
	Route::post('/store', 'Admin\StoresController@index');
	Route::get('/store/add-store', 'Admin\StoresController@addstore');
	Route::post('/add-store-post', 'Admin\StoresController@addstorepost');
	Route::post('/store-status', 'Admin\StoresController@storestatus');
	Route::get('/store/edit-store/{id}', 'Admin\StoresController@editstore');
	Route::post('/edit-store-post', 'Admin\StoresController@editstorepost');
	// Route::post('/store/store-remove', 'Admin\StoresController@storeDelete');
	Route::get('/view-store/{id}', 'Admin\StoresController@viewstore');
	Route::post('/store-aprove', 'Admin\StoresController@storeAprove');
	Route::get('/vendor/list', 'Admin\StoresController@getVandor')->name('vendor.list');
	Route::get('/store/menu-items/{id}', 'Admin\StoresController@getStoreItems')->name('storeItem');

	Route::get('/store/offline','Admin\StoresController@todayofflinestores');
	//--------admin store feb 2021 end---------

	// ----------admin promocode manager------------
	Route::resource('promocode', 'Admin\PromocodeController');
	Route::post('promocode/status', 'Admin\PromocodeController@updateStatus');
	Route::get('/promocode/index/ongoingpromo','Admin\PromocodeController@showongoingpromo');
	// ----------admin promocode manager end--------
	
	// ------------admin setting--------------
	Route::resource('setting', 'Admin\SettingsController');
	
	// --------------SubAdmin----------------
	Route::resource('sub-admin', 'Admin\SubAdminController');
	Route::post('sub-admin/status-update', 'Admin\SubAdminController@updateStatus');

	// --------------Permission--------------
	Route::resource('permission', 'Admin\PermissionController');

});


// --------------------------------Sub admin routes start--------------------------------------------------
Route::prefix('sub-admin')->group(function () {
	Route::get('/login', 'SubAdmin\AuthController@login')->name('subAdmin.login');
	Route::post('/login-post', 'SubAdmin\AuthController@loginPost')->name('subAdmin.loginPost');
	Route::get('/checkuserlogin', 'SubAdmin\AuthController@checkuserlogin');
	Route::get('/logout', 'SubAdmin\AuthController@logout')->name('subAdmin.logout');
	Route::get('/403', 'SubAdmin\AuthController@forbidden')->name('subAdmin.403.forbidden');

});
Route::group(['prefix' => 'sub-admin','as' => 'subAdmin.','middleware' => 'SubAdmin'], function () {
	//--------Dashboard---------
	Route::get('/', 'SubAdmin\DashboardController@index')->name('dashboard');
	//--------User manager------
	Route::get('/add-user', 'SubAdmin\UsersController@adduser')->name('add-user.index');
	Route::post('/add-user-post', 'SubAdmin\UsersController@adduserpost')->name('add-user-post.store');
	Route::get('/users', 'SubAdmin\UsersController@index')->name('users.index');
	Route::post('/users', 'SubAdmin\UsersController@index')->name('users.index');
	Route::get('/edit-user/{id}', 'SubAdmin\UsersController@edituser')->name('edit-user.edit');
	Route::post('/edit-user-post', 'SubAdmin\UsersController@edituserpost')->name('edit-user-post.store');
	Route::get('/view-user/{id}', 'SubAdmin\UsersController@viewuser')->name('view-user.show');
	Route::post('/user-status', 'SubAdmin\UsersController@userstatus')->name('user.status');
	Route::post('/user-delete', 'SubAdmin\UsersController@userdelete')->name('user.destroy');
	//---------Driver manager----------
	Route::post('/drivers/status', 'SubAdmin\DriverController@updateStatus')->name('driver.status.update');
	Route::resource('/drivers', 'SubAdmin\DriverController');
	//---------Banner manager----------
	Route::resource('/banners', 'SubAdmin\BannerController');
	Route::post('/banners/list/store-list/{businessCatId}', 'SubAdmin\BannerController@storeList')->name('banners.list.stores');
	Route::post('/banners/status', 'SubAdmin\BannerController@updateStatus')->name('banners.status');
	//----------Business Category manager------
	Route::get('/business-category', 'SubAdmin\BusinessCategoryController@index')->name('business-category.index');
	Route::post('/business-category', 'SubAdmin\BusinessCategoryController@index')->name('business-category.index');
	Route::get('/add-business-category', 'SubAdmin\BusinessCategoryController@addbusinesscategory')->name('add-business-category.index');
	Route::post('/add-business-category-post', 'SubAdmin\BusinessCategoryController@addbusinesscategorypost')->name('add-business-category-post.store');
	Route::get('/edit-business-category/{id}', 'SubAdmin\BusinessCategoryController@editbusinesscategory')->name('business-category.edit');
	Route::post('/edit-business-category-post', 'SubAdmin\BusinessCategoryController@editbusinesscategorypost')->name('edit-business-category-post.update');
	Route::get('/view-business-category/{id}', 'SubAdmin\BusinessCategoryController@viewbusinesscategory')->name('view-business-category.show');
	//--------item category---------
	Route::get('/item-category', 'SubAdmin\ItemCategoryController@index')->name('item-category.index');
	Route::post('/item-category', 'SubAdmin\ItemCategoryController@index')->name('item-category.index');
	Route::get('/add-item-category', 'SubAdmin\ItemCategoryController@addcategory')->name('add-item-category.index');
	Route::post('/add-item-category-post', 'SubAdmin\ItemCategoryController@addcategorypost')->name('add-item-category-post.store');
	Route::get('/edit-item-category/{id}', 'SubAdmin\ItemCategoryController@editcategory')->name('edit-item-category.edit');
	Route::post('/edit-item-category-post', 'SubAdmin\ItemCategoryController@editcategorypost')->name('edit-item-category-post.store');
	Route::post('/item-category-status', 'SubAdmin\ItemCategoryController@categorystatus')->name('item-category.status');
	Route::get('/view-item-category/{id}', 'SubAdmin\ItemCategoryController@viewcategory')->name('view-item-category.show');
	//--------vendor manager---------
	Route::get('/vendor', 'SubAdmin\VendorController@index')->name('vendor.index');
	Route::post('/vendor', 'SubAdmin\VendorController@index')->name('vendor.index');
	Route::get('/add-vendor', 'SubAdmin\VendorController@adduser')->name('add-vendor.index');
	Route::post('/add-vendor-post', 'SubAdmin\VendorController@adduserpost')->name('add-vendor-post.index');
	Route::get('/edit-vendor/{id}', 'SubAdmin\VendorController@edituser')->name('edit-vendor.edit');
	Route::post('/edit-vendor-post', 'SubAdmin\VendorController@edituserpost')->name('edit-vendor-post.store');
	Route::get('/view-vendor/{id}', 'SubAdmin\VendorController@viewuser')->name('view-vendor.show');
	Route::post('/vendor-status', 'SubAdmin\VendorController@userstatus')->name('vendor.status');
	// -----------Store manager------------
	Route::get('getstate/{id}','SubAdmin\StoresController@getState');
	Route::get('getcity/{id}','SubAdmin\StoresController@getCity');
	Route::get('/store', 'SubAdmin\StoresController@index')->name('store.index');
	Route::post('/store', 'SubAdmin\StoresController@index')->name('store.index');
	Route::get('/store/add-store', 'SubAdmin\StoresController@addstore')->name('store.create');
	Route::post('/add-store-post', 'SubAdmin\StoresController@addstorepost')->name('add-store-post.store');
	Route::post('/store-status', 'SubAdmin\StoresController@storestatus')->name('store.status');
	Route::get('/store/edit-store/{id}', 'SubAdmin\StoresController@editstore')->name('edit-store.edit');
	Route::post('/edit-store-post', 'SubAdmin\StoresController@editstorepost')->name('edit-store-post.store');
	Route::get('/view-store/{id}', 'SubAdmin\StoresController@viewstore')->name('view-store.show');
	Route::post('/store-aprove', 'SubAdmin\StoresController@storeAprove')->name('store.aprove');
	Route::get('/vendor/list', 'SubAdmin\StoresController@getVandor')->name('vendor.list');
	Route::get('/store/menu-items/{id}', 'SubAdmin\StoresController@getStoreItems')->name('storeItem');
	//------------Order manager------------
	Route::get('/get-store-list', 'Controller@getStoreList')->name('select2.stores');
	Route::put('/orders/update/status/{id}', 'SubAdmin\OrderController@orderUpdateStatus')->name('order.status');
	Route::resource('/orders', 'SubAdmin\OrderController')->only(['index','edit','update','show']);
	// ----------promocode manager------------
	Route::resource('promocode', 'SubAdmin\PromocodeController');
	Route::post('promocode/status', 'SubAdmin\PromocodeController@updateStatus')->name('promocode.status');
	// ----------Delivery Fee manager------------
	Route::get('/delivery-fee', 'SubAdmin\DeliveryFeeController@index')->name('delivery-fee.index');;
	Route::PUT('/update-delivery-radius', 'SubAdmin\DeliveryFeeController@updateDeliveryRadius')->name('update.max-rdius');
	Route::get('/add-delivery-fee', 'SubAdmin\DeliveryFeeController@create')->name('add-delivery-fee.index');
	Route::get('/edit-delivery-fee/{id}', 'SubAdmin\DeliveryFeeController@edit')->name('edit.delivery-fee.edit');
	Route::post('/delivery-fee-update', 'SubAdmin\DeliveryFeeController@update');
	Route::post('/delivery-fee-store', 'SubAdmin\DeliveryFeeController@store');
	//---------Content manager-------------------
	Route::get('/content', 'SubAdmin\ContentController@index')->name('content.index');
	Route::post('/content', 'SubAdmin\ContentController@index')->name('content.index');
	Route::get('/add-content', 'SubAdmin\ContentController@addcontent')->name('add-content.index');
	Route::post('/add-content-post', 'SubAdmin\ContentController@addcontentpost')->name('add-content-post.store');
	Route::get('/edit-content/{id}', 'SubAdmin\ContentController@editcontent')->name('edit-content.edit');
	Route::post('/edit-content-post', 'SubAdmin\ContentController@editcontentpost')->name('edit-content-post.store');
	Route::post('/content-status', 'SubAdmin\ContentController@contentstatus')->name('content.status');
	// -----------Email Template -----------------
	Route::get('/email-templates', 'SubAdmin\EmailTemplatesController@index')->name('email-templates.index');
	Route::post('/email-templates', 'SubAdmin\EmailTemplatesController@index')->name('email-templates.index') ;
	Route::get('/edit-email-templates/{id}', 'SubAdmin\EmailTemplatesController@editemailtemplates')->name('edit-email-templates.edit');
	Route::post('/edit-email-templates-post', 'SubAdmin\EmailTemplatesController@editemailtemplatespost')->name('edit-email-templates-post.store');
	
	Route::get('/setting/chnage-password', 'SubAdmin\AuthController@changePassword')->name('changePassword');
	Route::get('/setting/change-password-post', 'SubAdmin\AuthController@changePasswordPost')->name('change-password-post.store');
	Route::post('/sendemail', 'SubAdmin\AuthController@sendemail');
});
// --------------------------------Sub admin routes end----------------------------------------------------



/*-----------------------------------Vendor routes----------------------------------------------*/

Route::get('/vendor/signup', 'Vendor\v1\AuthControlle@signup')->name('vendor.signup.index');
Route::post('/vendor/signup', 'Vendor\v1\AuthControlle@signupStore')->name('vendor.signup.store');
Route::post('/vendor/verify-otp', 'Vendor\v1\AuthControlle@verifyOtp')->name('vendor.otp');
Route::get('/vendor/otp/{id}', 'Vendor\v1\AuthControlle@formOtp')->name('vendor.otp.form');
Route::post('/vendor/otp/resend', 'Vendor\v1\AuthControlle@resendOtp')->name('vendor.otp.resend');
Route::get('/vendor/checkuserlogin', 'Vendor\v1\AuthControlle@checkuserlogin');

Route::prefix('vendor')->middleware('CheckLoginUser')->group(function () {
	Route::get('/login', 'Vendor\v1\AuthControlle@login')->name('vendor.login');
	Route::post('/loginpost', 'Vendor\v1\AuthControlle@loginpost');
});


Route::get('/vendor/forgot-password', 'Vendor\ForgotpassController@index');
Route::post('/vendor/post-mail', 'Vendor\ForgotpassController@postmail');
Route::get('/vendor/create-password/{uniqurl}', 'Vendor\ForgotpassController@createpass');
Route::post('/vendor/password-save', 'Vendor\ForgotpassController@createpasspost');

Route::group(['prefix' => 'vendor','as'=>'vendor.','middleware' => ['vendorSecurity','localization']], function ()
{		
	Route::get('/', 'Vendor\VendorController@index')->name('dashboard');
	Route::get('/report', 'Vendor\ReportController@index')->name('report');
	Route::get('/report/earning', 'Vendor\ReportController@earningView')->name('report.earning');
	Route::get('/report/export-earning', 'Vendor\ReportController@exportEarning')->name('report.exportEarning');
	Route::get('/profile', 'Vendor\VendorController@profile');
	Route::get('/otp-form', 'Vendor\VendorController@otpForm');
	Route::post('/otp/verify', 'Vendor\VendorController@otpVerify');
	Route::get('/logout', 'Vendor\v1\AuthControlle@logout');
	Route::get('/change-password', 'Vendor\VendorController@changepassword');
	Route::post('/edit-profile-post', 'Vendor\VendorController@editprofile');
	Route::post('/change-password-post', 'Vendor\VendorController@changepasswordpost');
	Route::post('/sendemail', 'Vendor\VendorController@sendemail');
	Route::get('/update-password', 'Vendor\VendorController@updatePassword');

	Route::get('/setting', 'Vendor\SettingController@edit')->name('setting.edit');
	Route::put('/setting', 'Vendor\SettingController@update')->name('setting.update');

	Route::get('/notification', 'Vendor\NotificationController@index');
	Route::post('/notification', 'Vendor\NotificationController@index');
	Route::get('/view-notification/{id}', 'Vendor\NotificationController@viewnotification');

	Route::get('/add-notification', 'Vendor\NotificationController@addnotification');
	Route::post('/add-notification-post', 'Vendor\NotificationController@addnotificationpost');

	Route::get('/store', 'Vendor\StoreController@index');
	Route::post('/store', 'Vendor\StoreController@index');
	Route::get('/add-store', 'Vendor\StoreController@addstore');
	Route::post('/add-store-post', 'Vendor\StoreController@addstorepost');
	Route::post('/store-status', 'Vendor\StoreController@storestatus');
	Route::get('/store-profile', 'Vendor\StoreController@editstore');
	Route::get('/edit-store/{id}', 'Vendor\StoreController@editstore');
	Route::post('/edit-store-post', 'Vendor\StoreController@editstorepost')->name('store.post');
	Route::get('/view-store/{id}', 'Vendor\StoreController@viewstore');
	Route::get('getstate/{id}','Vendor\StoreController@getState');
	Route::get('getcity/{id}','Vendor\StoreController@getCity');
	Route::post('store/on-off','Vendor\StoreController@storeOnOff');

	Route::resource('/menu-manager', 'Vendor\ProductController');
	Route::post('/ajax-image-delete', 'Vendor\ProductController@ajaximagedelete');
	Route::post('/menu-manager/product-status', 'Vendor\ProductController@productstatus');
	Route::post('/menu-manager/product-stock-status', 'Vendor\ProductController@updateStockStatus');

	// -------------------Vendor order-manager---------------
	Route::get('/orders/order-history/current-orders', 'Vendor\OrderController@currentOrder')->name('orders.current');
	Route::get('/orders/order-history/past-orders', 'Vendor\OrderController@pastOrder')->name('orders.past');
	Route::resource('/orders', 'Vendor\OrderController');
	Route::put('/orders/status/update/{id}', 'Vendor\OrderController@orderStatusUpdate');

	Route::get('/orders/order-history/today-orders','Vendor\OrderController@todayOrder')->name('orders.today');
	Route::get('/orders/order-history/today-cancels','Vendor\OrderController@todayCancels')->name('orders.todaycancel');
	Route::get('/orders/order-history/today-delivered','Vendor\OrderController@todaydelivered')->name('orders.delivered');
	Route::get('/orders/order-history/notyetAccepted','Vendor\OrderController@notyetAcceptedOrder')->name('orders.notyetAccepted');
	// -----------------vendor contact-menu---------------
	Route::get('/contact', 'Vendor\ContactController@index')->name('contact');


	
    
    
	
});