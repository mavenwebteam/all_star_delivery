<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessCategory;
use App\Models\Stores;

class Banner extends Model
{
    protected $fillable = [
        'business_category_id',
        'store_id',
        'banner',
        'deleted_at'
    ];
    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class);
    }

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
}
