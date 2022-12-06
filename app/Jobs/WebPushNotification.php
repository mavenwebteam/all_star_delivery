<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\Helper;


class WebPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $deviceToken;
    private $message;

    private $title;
    private $target_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deviceToken, $title, $message, $target_id)
    {
        $this->deviceToken = $deviceToken;
        $this->message = $message;
        $this->title = $title;
        $this->target_id = $target_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Helper::notificationWeb($this->deviceToken, $this->title, $this->message, $this->target_id);
    }
}
