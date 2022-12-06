<?php

namespace App\Constants;

class Constant
{

    const APP_NAME = 'All Star';  
    const RECORD_PER_PAGE = 10;
    const API_RECORD_PER_PAGE = 10;
    const ADMIN_RECORD_PER_PAGE = 10;
    const VENDOR_RECORD_PER_PAGE = 10;
    const EXPIRE_DAY = 1;
    const SHOULD_QUEUE = 10; //sec
    const CACHE_TIME = 12;
    const DATE = 'd-m-Y';
    const DISK = 'public';
    // const CACHE_STORE = 'redis';
    // const REDIS_CACHE_TIME = 60 * 60 * 24 * 365;


    /**
     * User Image
     */
    const IMAGE_PATH = 'app/public/media';
    const USER_IMAGE = 'media/users/';
    const USER_IMAGE_THUMB = 'media/users/thumb/';
    const USER_IMAGE_HEIGHT = 200;
    const USER_IMAGE_WIDTH = 200;

    const NO_IMAGE_USER = 'img/avatar.png';
    const NO_IMAGE_DUMMY = 'img/dummy.jpg';

    // const TWILIO_ACCOUNT_SID = "ACa0ae8dc2d1a31bbfa30745cb6550cd0e";
    // const TWILIO_AUTH_TOKEN = "1a5a64cd9bb0aa525d60c57c33dd94d4";
    // const TWILIO_FROM = "+18508765507";
    // ---client FCM account key ---
    const FCM_KEY ="AAAApHdoJgI:APA91bGAc0Uj4POe-Km27lDgIRieK5eNx2NibOf1WhqOBPWOOdv3ly1ewcoamovzi8WpQCsdWraHsghxWmaUvLdxNlxN7lv5ip4XnS4LAKGCIMVvNdQlwvz02wEZkvFpExA8Qli_z8B8";
    
    // const PAYMENT_URL = 'https://test.cashfree.com/api/v2/cftoken/order';
    const API_KEY = 'eyJpdiI6Ik1vVDZUc1dzWUJqd2ZwXC9ySEtvWTd3PT0iLCJ2YWx1ZSI6IlltbGlsandTYjhFUEgwOWpQUjdGY2RQVDhRc0Z3c3E4OTZkQll2NURjTmM9IiwibWFjIjoiMmQ4YTMxYWYyMjUzZDkyOGRiNTFmMGMyMDRlYjEyNGMzZmI0MzIyMWMxZTE1YmM3OTY0ZWFiZjY5NzU3NzQzYyJ9';
    const PASSPORT_TOKEN = 'AllStar@123';
    
    /*
    | Store
    */
    const STORE_IMAGE_PATH = 'media/store/';
    const STORE_THUMB_PATH = 'media/store/thumb/';

    /*
    | Business Category
    */
    const BUSINESS_CAT_IMAGE_PATH = 'media/business_category/';
    const BUSINESS_CAT_THUMB_PATH = 'media/business_category/thumb/';

     /*
    | Item Category
    */
    const ITEM_CAT_IMAGE_PATH = 'media/item_category/';


    /*
    | Banner 
    */
    const BANNER_IMAGE_PATH = 'media/banners/';
    const BANNER_THUMB_PATH = 'media/banners/thumb/';
    
    /*
    | Product 
    */
    const PRODUCT_IMAGE_PATH = 'media/products/';
    const PRODUCT_THUMB_PATH = 'media/products/thumb/';

    /*
    | Promocode 
    */
    const PROMOCODE_IMAGE_PATH = 'media/promocode/';
    const PROMOCODE_THUMB_PATH = 'media/promocode/thumb/';


    
}
