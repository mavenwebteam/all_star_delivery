<?php
/* Global constants for site */
Session::put("appCurrency","USD");
//define('FFMPEG_CONVERT_COMMAND', '');
ini_set('memory_limit', '128M');
define("ADMIN_FOLDER", "admin/");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', public_path());
define('APP_PATH', app_path());
define('SERVICE_COMMAND', 'TOKENIZATION');
define('PURCHASE_COMMAND', 'PURCHASE');


define("IMAGE_CONVERT_COMMAND", "");
define('WEBSITE_URL', url('/').'/');
define('WEBSITE_JS_URL', WEBSITE_URL . 'js/');
define('WEBSITE_CSS_URL', WEBSITE_URL . 'css/');
define('WEBSITE_IMG_URL', WEBSITE_URL . 'img/');
define('NO_CELEBRITY_IMG', 'no_celebrity_image.jpg');
define('NO_CATEGORY_IMG', 'no_category_image.jpg');

define('WEBSITE_UPLOADS_ROOT_PATH', ROOT . DS . 'media' .DS );
define('WEBSITE_UPLOADS_URL', WEBSITE_URL . 'media/');

define('WEBSITE_ADMIN_URL', WEBSITE_URL.ADMIN_FOLDER );
define('WEBSITE_ADMIN_IMG_URL', WEBSITE_ADMIN_URL . 'img/');
define('WEBSITE_ADMIN_JS_URL', WEBSITE_ADMIN_URL . 'js/');
define('WEBSITE_ADMIN_FONT_URL', WEBSITE_ADMIN_URL . 'fonts/');
define('WEBSITE_ADMIN_CSS_URL', WEBSITE_ADMIN_URL . 'css/');

define('SETTING_FILE_PATH', APP_PATH . DS . 'settings.php');
define('MENU_FILE_PATH', APP_PATH . DS . 'menus.php');

define('CK_EDITOR_URL', WEBSITE_UPLOADS_URL . 'ckeditor_images/');
define('CK_EDITOR_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'ckeditor_images' . DS);

define('USER_PROFILE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'user_profile/');
define('USER_PROFILE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'user_profile' . DS); 

define('USER_VIDEO_URL', WEBSITE_UPLOADS_URL . 'user_video/');
define('USER_VIDEO_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'user_video' . DS); 

define('BLOCK_URL', WEBSITE_UPLOADS_URL . 'block/');
define('BLOCK_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'block' . DS); 

define('MASTERS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'masters/');
define('MASTERS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'masters' . DS); 

define('PRODUCTS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'products/thumb/');
define('PRODUCTS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'products/thumb' . DS); 

define('PRODUCTS_IMAGE_SLIDER_URL', WEBSITE_UPLOADS_URL . 'products/thumbslider/');
define('PRODUCTS_IMAGE_SLIDER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'products/thumbslider' . DS); 


define('BRAND_URL', WEBSITE_UPLOADS_URL . 'brands/');
define('BRAND_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'brands' . DS); 

define('CATEGORY_URL', WEBSITE_UPLOADS_URL . 'category/');
define('CATEGORY_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'category' . DS); 



define('MIDDLE_BANNER_URL', WEBSITE_UPLOADS_URL . 'middlebanner/');
define('MIDDLE_BANNER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'middlebanner' . DS); 

define('CHARITY_URL', WEBSITE_UPLOADS_URL . 'charity/');
define('CHARITY_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'charity' . DS); 


define('GIFT_CARD_URL', WEBSITE_UPLOADS_URL . 'gift_card/');
define('GIFT_CARD_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'gift_card' . DS); 

define('GIFT_URL', WEBSITE_UPLOADS_URL . 'gift/');
define('GIFT_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'gift' . DS); 

define('STORE_URL', WEBSITE_UPLOADS_URL . 'store/thumb/');
define('STORE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'store/thumb' . DS); 

define('SHOP_CATEGORY_URL', WEBSITE_UPLOADS_URL . 'category/thumb/');
define('SHOP_CATEGORY_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'category/thumb/' . DS); 

define('OFFER_URL', WEBSITE_UPLOADS_URL . 'coupon/thumb/');
define('OFFER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'coupon/thumb/' . DS); 
define('USER_URL', WEBSITE_UPLOADS_URL . 'users/thumb/');
define('USER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'users/thumb/' . DS); 
define('USER_URLS', WEBSITE_UPLOADS_URL . 'users/');
define('USER_ROOT_PATHS', WEBSITE_UPLOADS_ROOT_PATH .'users/' . DS); 
$config	=	array();

/*define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span></div><em><table><ul><li><section><thead><tbody><tr><td>');*/

define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span><div></div><em><table><ul><li><section><thead><tbody><tr><td><meta><style><title>');

define('ADMIN_ID', 1);
define('SUPER_ADMIN_ROLE_ID', 4);
define('VENDOR_ROLE_ID', 1);
define('MANUAL',2);
define('CUSTOMER_ROLE_ID', 1);
//define('CURRENCY', "$");


Config::set("Site.freePlanExpirationInDays", "30");
Config::set("Site.currency", "$");
Config::set("Site.currencyCode", "USD");
Config::set('defaultLanguage', 'English');
Config::set('defaultLanguageCode', 'en');
Config::set('defaultLanguageId', 1);
Config::set('secondLanguage', 'Arabic');
Config::set('secondLanguageCode', 'ar');
Config::set('secondLanguageId', 2);
Config::set('default_language.message', 'All the fields in English language are mandatory.');
Config::set('newsletter_template_constant',array('TO_EMAIL'=>'TO_EMAIL','WEBSITE_URL'=>'WEBSITE_URL','UNSUBSCRIBE_LINK'=>'UNSUBSCRIBE_LINK'));
Config::set('languages',array('en'=>'English','ar'=>'Arabic'));
Config::set('point_type',array('user'=>'User','charity'=>'Charity'));
Config::set("Reading.front_date_format",'d/m/Y H:i:s');
Config::set('celebrity_type',array('1'=>'TV Channel','2'=>'Blogger','3'=>'Page','4'=>'Radio'));
Config::set('user_type',array('simple'=>'1','Celebrity'=>'2'));
Config::set('language_code',array('en'=>1,'ar'=>2));
Config::set('wallet_trans_type',array('add_by_self'=>'Add By Self','order_payment'=>'Order Payment','send_a_gift_card'=>'Send A Gift Card','reedemed_gift_card_coupan'=>'Reedemed Gift Card Coupan',"funding"=>"Fund Added","fundingcommision" => "Fund Commision"));
Config::set('cancelOrder_Status',array('0'=>'Pending','1'=>'Approve','2'=>'Rejected'));
Config::set('fund_payment_type',array('Weekly'=>'1','Monthly'=>'2'));
Config::set('notification_user_type',array('All'=>'1','User'=>'2',"Celebrity"=>'3'));
Config::set('notification_type',array('Email'=>'1','SMS'=>'2',"PushNotification"=>'3','All'=>'4'));
/*Config::set('notification_type',array('Email'=>'1'));*/
Config::set('payment_type',array('COD'=>'1','Wallet'=>'2',"Card"=>'3','Other' => 4));
Config::set('payment_status',array('Pending'=>'0','Complete'=>'1',"Refunded"=>'2','Failed' => '3'));
Config::set('order_status',array('Pending'=>'0','Success'=>'1',"Proccessing"=>'2','Cancelled' => '3','RequestForCancel' => '4','Delivered' => '5'));

//////// extension 
define('IMAGE_EXTENSION','jpeg,jpg,png,gif');
define('PDF_EXTENSION','pdf');
define('DOC_EXTENSION','doc,xls,docx');
define('VIDEO_EXTENSION','mpeg,avi,mp4,webm,flv,3gp,m4v,mkv,mov,moov');
define('USER_PROFILE_IMG_FOLDER','user_profile_images');

define('TEXT_ADMIN_ID',1);
define('TEXT_FRONT_USER_ID',2);
define('FRONT_USER',2);


define("price_low_to_high",'price_low_to_high');
define("price_high_to_low",'price_high_to_low');
define("newly_added",'newly_added');
define("most_popular",'most_popular');
define("discounted",'discounted');

/**  System document url path **/
if (!defined('SYSTEM_IMAGE_URL')) {
    define('SYSTEM_IMAGE_URL', WEBSITE_UPLOADS_URL . 'system_images/');
}

/**  System document upload directory path **/
if (!defined('SYSTEM_IMAGE_DIRECTROY_PATH')){
    define('SYSTEM_IMAGE_DIRECTROY_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'system_images' . DS);
}

/**  Active Inactive global constant **/
define('ACTIVE',1);
define('INACTIVE',0);

define('HOME_PAGE', 'home_page');
define('BLOG_PAGE', 'blog_page');
define('BLOG_DETAILS_PAGE', 'blog_detail_page');
define('FORUM_PAGE', 'forum_page');
define('PRODUCT_PAGE', 'product_page');

define("BLOG_DATE_FORMAT",'d M y');
define("BLOG_DATETIME_FORMAT",'d M Y h:i a');

define("GOOGLE_AD",'google_ad');
define("IMAGE",'image');

define("CURRENCY",'$');
define("NO_PRODUCT_IMAGE",'no_product_image.jpg');

define("ORDER_ID",1000);

define("MALE",'male');
define("FEMALE",'female');
Config::set('gender_type', array(
	MALE		=>	'Male',
	FEMALE		=>	'Female'
));

define("FREE",'free');
define("PAID",'paid');
Config::set('event_type', array(
	FREE		=>	'Free',
	PAID		=>	'Paid'
));


define('SINGLE','single');
define('MULTIPLE','multiple');

define('RatingDef',3);

define('USER',2);
define('ACTOR',3);
define('SUBADMIN',4);
define('CUSTOMER','User');
define('SUBADMINS','Sub admin');
define('CELEBRITY','Celebrity');
define('PLAN_AMOUNT_DEDUCTED_FROM_WALLET','plan_amount_deducted_from_wallet');

Config::set("Site.plan_remaining_day",'10');

define('GIFTCARDREEDEMED',1);
define('GIFTCARDNOTREEDEMED',0);
define('LOYALTYPOINTREEDEMED',1);
define('LOYALTYPOINTADD',0);
define('TWILIO_SID','ACcec483ceb580c9f65a3d9783b0c62cee');
define('TWILIO_AUTH_TOKEN','2e061b07b982c9e1caee991a95e0b412');

