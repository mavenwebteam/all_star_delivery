<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoleHasPermission;

class Permission extends Model
{
   public $fillable = ['name'];

   /**
    * Get all of the permission for the Permission
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function userHasPermission()
    {
        return $this->hasOne(RoleHasPermission::class);
    }
}
