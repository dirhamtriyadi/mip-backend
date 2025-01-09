<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingStatus extends Model
{
    use SoftDeletes;

    protected $table = 'billing_statuses';

    protected $guarded = [];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
