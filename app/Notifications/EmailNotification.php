<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\EmailTranslation;
use App\Helpers\Helper;
use App\Constants\Constant;
use App\Models\Emailtemplates;


class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $slug;

    private $user;

    private $language;

    private $url;

    private $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($slug, $user, $url, $notification)
    {
        $this->slug = $slug;
        $this->user = $user;
        $this->url = $url;
        $this->language = $user->language;
        $this->notification = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $helper = Helper::setting();
        $email = Emailtemplates::where('slug', '=', $this->slug)->first();
        $fullName = $this->user->first_name.' '.$this->user->last_name;
        $message = $email->description;
        $message = str_replace("[NAME]", Helper::mb_strtolower($fullName), $message);
        $message = str_replace("[EMAIL]", $this->user->email, $message);
        $message = str_replace("[SITE_NAME]", $helper->app_name, $message);
        $message = str_replace("[URL]", $this->url, $message);
        return (new MailMessage)
            ->subject($email->subject)
            ->from( $helper->email, $helper->app_name)
            ->view('mail.EmailNotification', ['data' => $message, 'url' => $this->url, 'app_name'=> $helper->app_name]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
