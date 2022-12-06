<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\Unit;
use App\Models\ItemCategory;
use App\Models\Cart;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'uuid',
        'ref_id',
        'unit_id',
        'store_id',
        'vendor_id',
        'item_category_id',
        'unit_id',
        'sku',
        'name_en',
        'name_br',
        'description_en',
        'description_br',
        'slug',
        'rating',
        'status',
        'price',
        'discount_present',
        'discounted_price',
        'total_qty',
        'available_qty',
        'size',
        'in_stock',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
        
}

