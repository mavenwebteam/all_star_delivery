<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Stores;
use App\User;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_id',
        'user_id',
        'store_id',
        'address_id',
        'payment_mode',
        'instructions',
        'delivery_fee',
        'tax',
        'amount',
        'discounted_amount',
        'amount_payable_to_store',
        'grand_total',
        'status',
        'status_remark',
        'promocode_id'
    ];
    /**
     * Get order items for a specified order
     * */ 
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get store for a specified order
     * */ 
    public function store()
    {
        return $this->belongsTo(Stores::class);
    }

    /**
     * Get user for a specified order
     * */ 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get driver for a specified order
     * */ 
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get address for a specified order
     * */ 
    public function address()
    {
        return $this->belongsTo(Address::class)->withTrashed();
    }

    
    public function get_order_number()
    {
        return '#' . str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    public function setOrderIdAttribute($value)
    {
        $this->attributes['order_id'] = self::get_order_number();
    }

    /**
     * Get order date from created_at to d-M-Y formate
     * */ 
    public function getOrderDateAttribute($value)
    {
        return date('d-M-Y', strtotime($this->created_at));
    }
}