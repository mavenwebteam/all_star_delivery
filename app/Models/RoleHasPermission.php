<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    public $fillable = ['user_id', 'role_id', 'permission_id'];
}
