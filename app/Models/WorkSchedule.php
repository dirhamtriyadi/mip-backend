<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $table = 'work_schedules';

    protected $guarded = [];

    // Mengambil hari kerja dalam bentuk array
    public function getWorkingDaysAttribute($value)
    {
        return json_decode($value, true);
    }

    // Menyimpan hari kerja dalam bentuk JSON
    // public function setWorkingDaysAttribute($value)
    // {
    //     $this->attributes['working_days'] = json_encode($value);
    // }
}
