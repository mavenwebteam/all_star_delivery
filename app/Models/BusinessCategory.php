<?php



namespace App\Models;

use App\Models\Stores;

use Illuminate\Database\Eloquent\Model;



class BusinessCategory extends Model
{
    protected $table = 'business_category';
    
    protected $fillable = [
        'name_en',
        'name_burmese',
        'image',
        'created_at',
        'is_deleted',
    ];
    public function stores()
    {
        $this->hasMany(Stores::class);
    }
}

