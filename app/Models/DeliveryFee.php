<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryFee extends Model
{
    
    
    /*
    | range overlape filter
    */
    /*public function scopeAlreadyInRange($query,$min_distance,$max_distance) 
    { 
        return $query->whereBetween('min_distance', [$min_distance, $max_distance]) 
            ->orWhereBetween('max_distance', [$min_distance, $max_distance]) 
            ->orWhereRaw('? BETWEEN min_distance and max_distance', [$min_distance]) 
            ->orWhereRaw('? BETWEEN min_distance and max_distance', [$max_distance]); 
    }
    */
}
