<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectiveCustomer extends Model
{
    protected $table = 'prospective_customers';

    protected $fillable = [
        'name',
        'no_ktp',
        'bank',
        'ktp',
        'kk',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prospectiveCustomerSurvey()
    {
        return $this->hasMany(ProspectiveCustomerSurvey::class);
    }
}
