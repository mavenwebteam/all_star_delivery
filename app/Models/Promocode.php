<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessCategory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode extends Model
{
    use SoftDeletes;
    
    protected $table = 'promocodes';

    protected $fillable = [
        'id',
        'title',
        'image',
        'description',
        'start_date',
        'end_date',
        'code',
        'business_category_id',
        'cap_limit',
        'discount_present',
        'total_no_of_times_use',
        'no_of_times_for_same_user',
        'no_of_times_in_each_day',
        'status',
    ];

    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class);
    }


}
