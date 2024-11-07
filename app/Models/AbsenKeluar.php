<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKeluar extends Model
{
    protected $table = 'absen_keluar';
    protected $fillable = [
        'user_id',
        'code',
        'date',
        'time',
        'image',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
