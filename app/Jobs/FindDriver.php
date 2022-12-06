<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Api\OrderController;


class FindDriver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderId;
    private $storeId;
    private $radius;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id,$store_id, $radius)
    {
        $this->orderId = $order_id;
        $this->storeId = $store_id;
        $this->radius = $radius;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $obj = new OrderController;
        $obj->findDriver($this->orderId, $this->storeId, $this->radius);
    }
}
