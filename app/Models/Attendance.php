<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\AttendanceTypeEnum;

class Attendance extends Model
{
    use SoftDeletes, Prunable;

    protected $table = 'attendances';

    protected $guarded = [];

    protected $casts = [
        'type' => AttendanceTypeEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    protected function pruning(): void
    {
        // delete image check in or check out from public/images/attendances
        if ($this->image_check_in) {
            $filePathImageCheckIn = public_path('images/attendances/' . $this->image_check_in);
            if (file_exists($filePathImageCheckIn)) {
                unlink($filePathImageCheckIn);
            } else {
                \Log::warning("File not found for pruning: " . $filePathImageCheckIn);
            }
        }

        if ($this->image_check_out) {
            $filePathImageCheckOut = public_path('images/attendances/' . $this->image_check_out);
            if (file_exists($filePathImageCheckOut)) {
                unlink($filePathImageCheckOut);
            } else {
                \Log::warning("File not found for pruning: " . $filePathImageCheckOut);
            }
        }

    }
}
