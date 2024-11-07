<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    use SoftDeletes, Prunable;

    protected $table = 'attendances';

    protected $guarded = [];

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
        // delete image from public/images/attendances
        $filePath = public_path('images/attendances/' . $this->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            \Log::warning("File not found for pruning: " . $filePath);
        }
    }
}
