<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessCategory;
use App\Models\Stores;

class Chat extends Model
{
    protected $fillable = [
        'order_id',
        'sender_id',
        'receiver_id',
        'message',
        'created_at'
    ];
}
