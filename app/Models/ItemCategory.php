<?php
namespace App\Models;

use Eloquent,App,DB,Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ItemCategory extends Model
{

    protected $table = 'item_category';

	// public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }
	
}

