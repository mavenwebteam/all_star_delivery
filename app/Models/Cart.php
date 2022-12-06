<?php

namespace App\Models;
use App\Models\Product;
use App\Models\ProductImage;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'qty',
        'price',
        'amount',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function store(){
        return $this->belongsTo(Stores::class);
    }

    public function productImg(){
        return $this->hasMany(ProductImage::class, 'product_id','product_id');
    }
}
