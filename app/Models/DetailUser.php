<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $table = 'detail_users';
    protected $fillable = [
        'user_id',
        'nik',
        // 'phone',
        // 'address',
    ];
}
