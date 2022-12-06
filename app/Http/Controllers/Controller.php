<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Str, App, Log;
use App\Models\Cartitem;
use App\Models\Stores;
use App\Models\Notification;
use App\Models\Emailtemplates;
use App\User;
use App\Models\ItemCategory;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $user;
    public function __construct()
    {
        $user = "";
        if (Auth::user())
        {
            $user = Auth::user();
            //$usercartitemcount = Cartitem::where('user_id',Auth::user()->id)->count();
            //echo $usercartitemcount; die;
            
        }

        View::share('userdata', $user);
    }

    public function getpubnub()
    {
        $pnConfiguration = new PNConfiguration();
        $pnConfiguration->setSubscribeKey("sub-c-9216f6b0-050b-11e9-a860-92908bb92f21");
        $pnConfiguration->setPublishKey("pub-c-1523cfad-d0d8-486b-be32-f419caab6052");
        $pnConfiguration->setSecure(false);
        $pubnub = new PubNub($pnConfiguration);
        return $pubnub;
    }

    public function sendMail($to, $fullName, $subject, $messageBody, $from = '', $files = false, $path = '', $attachmentName = '')
    {
        $data = array();
        $data['to'] = $to;
        $data['from'] = (!empty($from) ? $from : 'allstar@octalinfosolution.com');
        $data['fullName'] = $fullName;
        $data['subject'] = $subject;
        $data['filepath'] = $path;
        $data['attachmentName'] = $attachmentName;
        if ($files === false)
        {
            Mail::send('emails.template', array(
                'messageBody' => $messageBody
            ) , function ($message) use ($data)
            {
                $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);

            });
        }
        else
        {
            if ($attachmentName != '')
            {
                Mail::send('emails.template', array(
                    'messageBody' => $messageBody
                ) , function ($message) use ($data)
                {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'], array(
                        'as' => $data['attachmentName']
                    ));
                });
            }
            else
            {
                Mail::send('emails.template', array(
                    'messageBody' => $messageBody
                ) , function ($message) use ($data)
                {
                    $message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
                });
            }
        }
        DB::table('email_logs')->insert(array(
            'email_to' => $data['to'],
            'email_from' => $data['from'],
            'subject' => $data['subject'],
            'message' => $messageBody,
            'created_at' => DB::raw('NOW()')
        ));
    }

    public function send_notification_for_android_old($registration_ids = NULL, $message = "Test Message", $title = "test", $type = "", $notification_id = "")
    {
        $badge_count = 0;
        $userData = User::where('device_id', '=', $registration_ids)->first();

        $url = FIREBASE_URL;
        $firebase_api_key = API_KEY;
        $message = trim(strip_tags($message));
        $data['message'] = $message;
        $data['title'] = $title;
        $data['ticket_id'] = $ticket_id;
        $data['ticket_title'] = $ticket_title;
        $data['title_type'] = $title_type;
        $data['discussion_id'] = $discussion_id;
        $data['type'] = $type;
        $data['notification_id'] = $notification_id;
        $data['group_id'] = $group_id;
        $data['is_ticket'] = $is_ticket;
        $data['badge_count'] = $total_badge_count;
        $data['badge'] = $badge_count;
        $data['count_messages'] = $count_messages;

        $fields = array(
            'to' => $registration_ids,
            'data' => $data,
        );
        $headers = array(
            'Authorization: key=' . $firebase_api_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false)
        {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
    }
    public function arrayStripTags($array)
    {
        $result = array();
        foreach ($array as $key => $value)
        {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key, ALLOWED_TAGS_XSS);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value))
            {
                $result[$key] = $this->arrayStripTags($value);
            }
            else
            {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value, ALLOWED_TAGS_XSS));
            }
        }

        return $result;

    }
    public function send_notification_for_iphone_old($registration_ids = NULL, $message = "Test Message", $title = "test", $type = "", $notification_id = "")
    {
        try
        {
            $badge_count = 0;
            $userData = User::where('device_id', '=', $registration_ids)->first();
            // pr($userData);die;
            $unread_discussions_data = TicketDiscussion::where('sender_id', '!=', $userData->_id)
                ->where('is_read', '=', 'no')
                ->pluck('ticket_id');

            $unread_discussions_count = count(array_unique($unread_discussions_data->toArray()));

            $single_ticket_count = CountTicket::where('user_id', '=', $userData->_id)
                ->where('list_type', '=', 'single')
                ->count();

            $schedule_ticket_count = CountTicket::where('user_id', '=', $userData->_id)
                ->where('list_type', '=', 'schedule')
                ->count();

            $completed_ticket_count = CountTicket::where('user_id', '=', $userData->_id)
                ->where('list_type', '=', 'completed')
                ->count();

            $total_badge_count = $single_ticket_count + $schedule_ticket_count + $completed_ticket_count + $unread_discussions_count;

            $count_messages = UserNotification::all()->where('user_id', '=', $userData->_id)
                ->where('is_read', '=', 'no')
                ->where('notification_type', '=', 'admin')
                ->count();

            $badge_count = UserNotification::all()->where('user_id', '=', $userData->_id)
                ->where('notification_type', '!=', 'admin')
                ->where('is_read', '=', 'no')
                ->count();

            $title_type = '';
            $ticket_title = '';
            if (!empty($ticket_id))
            {
                if (!empty($group_id))
                {
                    $tix_detail = GroupTicket::where('_id', '=', $ticket_id)->first();
                }
                else
                {
                    $tix_detail = Ticket::where('_id', '=', $ticket_id)->first();
                }
                if (!empty($tix_detail))
                {
                    $ticket_title = $tix_detail->title;
                    $title_type = $tix_detail->title_type;
                }
            }

            $url = 'ssl://gateway.sandbox.push.apple.com:2195';
            $ctx = stream_context_create();
            // TidifCer.pem is your certificate file
            stream_context_set_option($ctx, 'ssl', 'local_cert', public_path() . '/storage/TidifCer.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', '123456');
            // Open a connection to the APNS server
            $fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);
            // Create the payload body
            $alert['title'] = $title;
            // $alert['subtitle'] = $message;
            $alert['body'] = $message;
            $alert['badge'] = $total_badge_count;
            $alert['sound'] = 'default';
            // $obj = (object)$alert;
            $body['aps'] = array(
                'category' => 'CustomSamplePush',
                'alert' => $alert,
                'type' => $type,
                'ticket_title' => $ticket_title,
                'title_type' => $title_type,
                'ticket_id' => $ticket_id,
                'group_id' => $group_id,
                'is_ticket' => $is_ticket,
                'discussion_id' => $discussion_id,
                'count_messages' => $count_messages,
                'badge' => $badge_count,
                'sound' => 'default',
                'content-available' => 1,
                'mutable-content' => 1,
                'notification_id' => $notification_id,
                'message' => $message,
            );
            // Encode the payload as JSON
            $payload = json_encode($body);
            // Build the binary notification
            @$msg = chr(0) . pack('n', 32) . pack('H*', $registration_ids) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            fclose($fp);
            if (!$result) return false;
            else return true;
        }
        catch(Exception $ex)
        {
            return true;
        }
    }

    public function send_notification_for_android($registatoin_ids = "", $message = "Test Message", $title = NULL, $notification_type = null, $requester_name = '', $item_id = '', $profile_image = '', $amount = '', $requester_id = '', $booking_type = '')
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $Apikey = ANDROID_API_KEY;
        // $Apikey 	= 	"AIzaSyAH9o_siJg6KwaTymq3FwXoffr5IELKOeM";	//Live
        $bedgeArray = User::where('id', '=', $registatoin_ids)->first();
        if ($bedgeArray->is_notification == 1)
        {
            if ($bedgeArray->role_id == 4)
            {
                $Apikey = DRIVER_ANDROID_API_KEY;
            }
            if (isset($bedgeArray->bedge_count))
            {
                $bedgeCount = $bedgeArray->bedge_count + 1;
            }
            else
            {
                $bedgeCount = 1;
            }

            if (!empty($bedgeArray))
            {
                $bedgeArray->bedge_count = $bedgeCount;
                $bedgeArray->save();
            }

            $notification_count = Notification::where('user_id', '=', $bedgeArray->id)
                ->where('is_read', '=', '0')
                ->count();
            $reg_id = $bedgeArray->device_id;

            $message = strip_tags($message);
            $data['message'] = $message;
            $data['badge'] = $notification_count;
            $data['notification_type'] = $notification_type;
            $data['payload']['title'] = $title;
            $data['payload']['requester_name'] = $requester_name;
            $data['payload']['requester_id'] = $requester_id;
            $data['payload']['item_id'] = $item_id;
            $data['payload']['profile_image'] = $profile_image;
            $data['payload']['amount'] = $amount;
            $data['payload']['booking_type'] = $booking_type;

            $fields = array(
                'registration_ids' => array(
                    $reg_id
                ) ,
                'data' => $data
            );

            $headers = array(
                'Authorization: key=' . $Apikey,
                'Content-Type: application/json'
            );
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);
            //pr($result);die;
            if ($result === false)
            {
                //die('Curl failed: ' . curl_error($ch));
                
            }
            else
            {
                //echo "Messages delivered successfully.".$result;
                
            }

            // Close connection
            curl_close($ch);
            //echo $result;
            
        }
    }

    public function send_notification_for_iphone($registatoin_ids = "", $message = "Test Message", $title = NULL, $notification_type = null, $requester_name = '', $item_id = '', $profile_image = '', $amount = '', $requester_id = '', $booking_type = '')
    {
        $bedgeCount = 1;
        $bedgeArray = User::where('id', '=', $registatoin_ids)->first();
        if ($bedgeArray->is_notification == 1)
        {
            if (isset($bedgeArray->bedge_count))
            {
                $bedgeCount = $bedgeArray->bedge_count + 1;
            }
            else
            {
                $bedgeCount = 1;
            }

            if (!empty($bedgeArray))
            {
                $bedgeArray->bedge_count = $bedgeCount;
                $bedgeArray->save();
            }
            //$message = strip_tags($message);
            //	$message = array('aps'=>array('alert'=>$message));
            $main_msg = $message = strip_tags($message);
            $message = array(
                'aps' => array()
            );
            $notification_count = Notification::where('user_id', '=', $bedgeArray->id)
                ->where('is_read', '=', '0')
                ->count();
            $reg_id = $bedgeArray->device_id;

            if (strlen($reg_id) < 40)
            {
                return false;
            }
            // Put your private key's passphrase here:
            $passphrase = '123456';

            // Put your alert message here:
            $base_path = public_path('storage');

            //ck_dev.pem
            $Apikey = IPHONE_FILE_NAME;
            if ($bedgeArray->role_id == 4)
            {
                $Apikey = DRIVER_IPHONE_FILE_NAME;
            }
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $base_path . '/' . $Apikey);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            //gateway.sandbox.push.apple.com
            // Open a connection to the APNS server
            //'ssl://gateway.sandbox.push.apple.com:2195'
            $fp = stream_socket_client(
            //'ssl://gateway.push.apple.com:2195', $err,
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);

            $sound = 'default';
            if ($notification_type == 'PanicRequest')
            {
                $sound = 'intruder_alarm.wav';
            }

            $message['aps']['badge'] = $notification_count;
            $message['aps']['sound'] = $sound;
            $message['aps']['notification_type'] = $notification_type;
            $message['aps']['requester_name'] = $requester_name;
            $message['aps']['requester_id'] = $requester_id;
            $message['aps']['item_id'] = $item_id;
            $message['aps']['amount'] = $amount;
            $message['aps']['mutable-content'] = 1;
            $message['aps']['category'] = "myNotificationCategory";
            $message['aps']['booking_type'] = $booking_type;
            $message['aps']['alert']['title'] = $title;
            $message['aps']['alert']['body'] = $main_msg;
            $message["data"]['profile_image'] = $profile_image;
            $body = json_encode($message);
            $message = $body;

            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $reg_id) . pack('n', strlen($message)) . $message;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            //pr($result);die;
            // Close the connection to the server
            fclose($fp);
        }
    }

    public function send_sms($code = "", $number = "", $type = "")
    {
        $checkotp = UserOtp::where('mobile_number', '=', $number)->first();
        if ($checkotp)
        {
            $checkotp->otp = $code;
            $checkotp->save();
        }
        else
        {
            UserOtp::create(['mobile_number' => $number, 'otp' => $code]);
        }
        /*App::import('Vendor', 'Twilio', array('file' => 'Twilio'.DS.'autoload.php'));
        //pr(get_included_files());die;
        $AccountSid = TWILIO_SID;
        $AuthToken = TWILIO_AUTH_TOKEN;
        $client = new Twilio\Rest\Client($AccountSid, $AuthToken);
        $auth_otp = $this->random_digits(6);
        $otp = "Your Paytz OTP Code is ".$auth_otp. ".";
        try
        {
        $client->messages->create(
        // the number you'd like to send the message to
        $code_number,
        array(
        // A Twilio phone number you purchased at twilio.com/console
        'from' => TWILIO_PURCHASED_NUMBER,
        // the body of the text message you'd like to send
        'body' => $otp
        )
        );*/
        /*$st['user_id'] = $userArray['User']['id'];
        $st['mobile_number'] = $number;
        $st['otp'] = $code;
        $this->UserOtp->save($st,false);
        unset($this->UserOtp->id);*/

        return true;
        /*}
        catch(Exception $ex)
        {
        $message = $ex->getMessage();
        }*/
    }

    function random_reference_number($length = 6)
    {
        $random = "";
        $data = "0a1b2c3d4e5f6g7h8i9j10k11l1112m13n14o15p16q17r18s19t20u21v22w23x24y25z";
        for ($i = 0;$i < $length;$i++)
        {
            $random .= substr($data, (rand() % (strlen($data))) , 1);
        }
        return $random;
    }

    static function cart_count($id)
    {
        //if(!empty(Auth::user()->id)){
        $usercartitemcount = Cartitem::where('user_id', $id)->count();
        return $usercartitemcount;
        //}
        
    }

    static function cart_record($user_id)
    {

        $usercartitems = Cartitem::select('cart_item.store_id', 'cart_item.id', 'cart_item.outlet_id', 'cart_item.id', 'cart_item.product_id', 'cart_item.user_id', 'cart_item.qty', 'products.discount_price', 'product_images.image', 'products.name', 'products.product_vat', 'cart_item.price', 'products.total_qty as quentity')->leftJoin('products', 'products.id', '=', 'cart_item.product_id')
            ->leftJoin('product_images', 'cart_item.product_id', '=', 'product_images.product_id')
        //->leftJoin('product_inventories','cart_item.product_id', '=', 'product_inventories.product_id')
        
            ->groupBy('product_images.product_id')
            ->where('cart_item.user_id', Auth::user()
            ->id)
            ->get();
        return $usercartitems;
    }

    static function total_qty($id)
    {
        $qty = DB::table('cart_item')->where('user_id', $id)->sum('qty');
        return $qty;
        //}
        
    }

    static function total_price($id)
    {
        //$qty = DB::table('cart_item')->where('user_id', $id)->sum('qty');
        //$price = DB::table('cart_item')->where('user_id', $id)->sum('price');
        $price = DB::table('cart_item')->select(DB::raw('sum(qty*price) AS total_price'))
            ->where('user_id', $id)->first();
        return $price->total_price;

    }

    static function store_detalis($id)
    {
        $cartdata = Cartitem::where('user_id', $id)->first();
        $store_data = DB::table('stores')->where('id', $cartdata->store_id)
            ->first();
        return $store_data->name;

    }

    function random_digits($length = 6)
    {
        $random = "";
        $data = "0123456789";
        for ($i = 0;$i < $length;$i++)
        {
            $random .= substr($data, (rand() % (strlen($data))) , 1);
        }
        return $random;
    }
    function random_referral_code($length = 6)
    {

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $random = "";
        for ($i = 0;$i < $length;$i++)
        {
            $random .= $chars[mt_rand(0, strlen($chars) - 1) ];
        }
        return $random;
    }

    public function sendNotification($DeviceToken, $title, $msg, $apiKey)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $registrationIds = $DeviceToken;
        $msgSuccess = true;
        $fields = array(
            'registration_ids' => $registrationIds,
            'notification' => $msg
        );

        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        $result = json_decode($result);
        pr($result);
        die;
        if (isset($result->success))
        {

            $msgSuccess = false;
        }
        else
        {
            $msgSuccess = true;
        }
        curl_close($ch);
        return $msgSuccess;
    }

    static function notification_count($id)
    {
        $qty = DB::table('notification')->where('user_id', $id)->where('is_read', 0)
            ->count();
        return $qty;
    }

    protected function deleteFile($filePath = '')
    {
        if (File::exists($filePath))
        {
            File::delete($filePath);
        }
        return true;
    }

    /*
    -----------------------------
    -----------All Star Feb-2021 ----------
    -----------------------------
    */

    public function getStoreItemCategory()
    {
        try
        {
            $itemCategories = [];
            $vendorId = Auth::id();
            $store = Stores::where('user_id', $vendorId)->first();
            if ($store)
            {
                $itemCategories = ItemCategory::where('category_id', $store->business_category_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->get();
            }
            return $itemCategories;
        }
        catch(Exception $ex)
        {
            return [];
        }
    }

    public function generateUniqueId()
    {
        return (string)Str::uuid();
    }

    public function sendMailOnUserStatusUpdate($userId, $status)
    {
        $user = User::find($userId);
        if ($user && !empty($user->email))
        {
            $emailData = Emailtemplates::where('slug', '=', 'user-deactivate')->first();
            $settingsEmail = Config::get("Site.email");
            if ($emailData)
            {
                $textMessage = $emailData->description;
                $textMessage = str_replace(array(
                    '{USERNAME}',
                    '{{status}}'
                ) , array(
                    $user->first_name,
                    $status
                ) , $textMessage);
                self::sendMail($user->email, $user->first_name, $emailData->subject, $textMessage, $settingsEmail);
            }
        }
        return true;
    }

    /**
     * get store list for select-2 with pagination
     *
     */
    public function getStoreList(Request $request)
    {
        // Log::debug('call');
        $page = Input::get('page');
        $term = Input::get('search');
        $resultCount = 8;
        $offset = ($page - 1) * $resultCount;

        $stores = Stores::select('id', 'name as text')->where('is_deleted', '0')
            ->where('status', '1')->when(!empty($term) , function ($q) use ($term)
        {
            return $q->where('name', 'like', '%' . $term . '%');
        })->skip($offset)->take($resultCount)->orderBy('text')
            ->get();

        // $count = Stores::select('id','name as text')
        // ->where('is_deleted','0')
        // ->where('status','1')
        // ->when(!empty($term), function ($q) use ($term) {
        //     return $q->where('name','like','%'.$term.'%');
        // })
        // ->skip($offset)->take($resultCount)
        // ->orderBy('text')
        // ->count();
        $count = $stores->count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount > $count ? false : true;
        return ['results' => $stores, "pagination" => array(
            "more" => $morePages
        ) ];
    }


}



