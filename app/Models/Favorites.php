<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stores;

class Favorites extends Model
{

    protected $table = 'favorites';
    
    protected $fillable = ['user_id','store_id'];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
    

}

