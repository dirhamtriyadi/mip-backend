<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'no_contract',
        'bank_account_number',
        'name_customer',
        'name_mother',
        'phone_number',
        'status',
        'bank_id',
        'user_id',
        'os_start',
        'os_remaining',
        'os_total',
        'monthly_installments',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function billing()
    {
        return $this->hasMany(Billing::class);
    }

    public function customerAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
