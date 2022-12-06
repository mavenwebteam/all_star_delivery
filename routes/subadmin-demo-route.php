<?php
// --------------------------------Sub admin routes start----------------------------------------------------
Route::prefix('sub-admin')->group(function () {
	Route::get('/login', 'SubAdmin\AuthController@login')->name('subAdmin.login');
	Route::post('/login-post', 'SubAdmin\AuthController@loginPost')->name('subAdmin.loginPost');
	Route::get('/checkuserlogin', 'SubAdmin\AuthController@checkuserlogin');
	Route::get('/logout', 'SubAdmin\AuthController@logout')->name('subAdmin.logout');
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
	Route::post('/drivers/status', 'SubAdmin\DriverController@updateStatus');
	Route::resource('/drivers', 'SubAdmin\DriverController');
	//---------Banner manager----------
	Route::resource('/banners', 'SubAdmin\BannerController');
	Route::post('/banners/list/store-list/{businessCatId}', 'SubAdmin\BannerController@storeList')->name('banners.list.stores');
	Route::post('/banners/status', 'SubAdmin\BannerController@updateStatus')->name('banners.status');
	//----------Business Category manager------
	Route::get('/business-category', 'SubAdmin\BusinessCategoryController@index')->name('business-category.index');
	Route::post('/business-category', 'SubAdmin\BusinessCategoryController@index');
	Route::get('/add-business-category', 'SubAdmin\BusinessCategoryController@addbusinesscategory')->name('add-business-category.index');
	Route::post('/add-business-category-post', 'SubAdmin\BusinessCategoryController@addbusinesscategorypost')->name('add-business-category-post.store');
	Route::get('/edit-business-category/{id}', 'SubAdmin\BusinessCategoryController@editbusinesscategory');
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
	Route::post('/store', 'SubAdmin\StoresController@index');
	Route::get('/store/add-store', 'SubAdmin\StoresController@addstore')->name('store.create');
	Route::post('/add-store-post', 'SubAdmin\StoresController@addstorepost')->name('add-store-post.store');
	Route::post('/store-status', 'SubAdmin\StoresController@storestatus')->name('store.store');
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
	Route::get('/delivery-fee', 'SubAdmin\DeliveryFeeController@index');
	Route::PUT('/update-delivery-radius', 'SubAdmin\DeliveryFeeController@updateDeliveryRadius')->name('update.max-rdius');
	Route::get('/add-delivery-fee', 'SubAdmin\DeliveryFeeController@create')->name('add-delivery-fee.index');
	Route::get('/edit-delivery-fee/{id}', 'SubAdmin\DeliveryFeeController@edit')->name('edit.delivery-fee.edit');
	Route::post('/delivery-fee-update', 'SubAdmin\DeliveryFeeController@update');
	Route::post('/delivery-fee-store', 'SubAdmin\DeliveryFeeController@store');
	//---------Content manager-------------------
	Route::get('/content', 'SubAdmin\ContentController@index')->name('content.index');
	Route::post('/content', 'SubAdmin\ContentController@index');
	Route::get('/add-content', 'SubAdmin\ContentController@addcontent')->name('add-content.index');
	Route::post('/add-content-post', 'SubAdmin\ContentController@addcontentpost')->name('add-content-post.store');
	Route::get('/edit-content/{id}', 'SubAdmin\ContentController@editcontent')->name('edit-content.edit');
	Route::post('/edit-content-post', 'SubAdmin\ContentController@editcontentpost')->name('edit-content-post.store');
	Route::post('/content-status', 'SubAdmin\ContentController@contentstatus')->name('content.status');
	// -----------Email Template -----------------
	Route::get('/email-templates', 'SubAdmin\EmailTemplatesController@index')->name('email-templates.index');
	Route::post('/email-templates', 'SubAdmin\EmailTemplatesController@index');
	Route::get('/edit-email-templates/{id}', 'SubAdmin\EmailTemplatesController@editemailtemplates')->name('edit-email-templates.edit');
	Route::post('/edit-email-templates-post', 'SubAdmin\EmailTemplatesController@editemailtemplatespost')->name('edit-email-templates-post.store');
	
	Route::get('/setting/chnage-password', 'SubAdmin\AuthController@changePassword')->name('changePassword');
	Route::get('/setting/change-password-post', 'SubAdmin\AuthController@changePasswordPost')->name('change-password-post.store');
	Route::post('/sendemail', 'SubAdmin\AuthController@sendemail');
});