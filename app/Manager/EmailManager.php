<?php

namespace App\Manager;

use App\Notifications\EmailNotification;
use App\Constants\Constant;


class EmailManager
{
    /**
     * Send Email
     *
     * @param $id
     * @param $user
     * @param $url
     */
    public function sendEmail($slug,$user,$url,$notification){
        $when = now()->addSeconds(Constant::SHOULD_QUEUE);
        $user->notify((new EmailNotification($slug,$user,$url,$notification))->delay($when));
        return true;
    }
}
