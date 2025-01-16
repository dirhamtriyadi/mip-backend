<?php

namespace App\Models;

use App\Enums\BillingStatusesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingStatus extends Model
{
    use SoftDeletes;

    protected $table = 'billing_statuses';

    protected $guarded = [];

    protected $casts = [
        'status' => BillingStatusesEnum::class,
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
