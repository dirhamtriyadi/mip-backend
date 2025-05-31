<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProspectiveCustomerStatusEnum;

class ProspectiveCustomer extends Model
{
    protected $table = 'prospective_customers';

    protected $casts = [
        'status' => ProspectiveCustomerStatusEnum::class,
    ];

    protected $fillable = [
        'name',
        'no_ktp',
        'ktp',
        'kk',
        'status',
        'status_message',
        'user_id',
        'bank_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function prospectiveCustomerSurvey()
    {
        return $this->hasMany(ProspectiveCustomerSurvey::class);
    }
}
