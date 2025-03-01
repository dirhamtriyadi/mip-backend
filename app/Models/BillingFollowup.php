<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\BillingFollowupEnum;

class BillingFollowup extends Model
{
    protected $table = 'billing_followups';

    protected $guarded = [];

    protected $casts = [
        'status' => BillingFollowupEnum::class,
    ];

    public function customerBilling()
    {
        return $this->belongsTo(CustomerBilling::class, 'customer_billing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
