<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class DriverOrder extends Model
{
    protected $table = 'driver_order';
    protected $fillable = [
        'order_id', 'driver_id'
    ];

     public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
