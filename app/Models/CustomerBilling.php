<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBilling extends Model
{
    protected $table = 'customer_billings';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function billingFollowups()
    {
        return $this->hasMany(BillingFollowup::class, 'customer_billing_id');
    }

    public function latestBillingFollowups()
    {
        return $this->hasMany(BillingFollowup::class, 'customer_billing_id')->latest();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
