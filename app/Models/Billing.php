<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;

class Billing extends Model
{
    use SoftDeletes, Prunable;

    protected $table = 'billings';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    protected function pruning(): void
    {
        // delete image check in or check out from public/images/billings
        if ($this->image_amount) {
            $filePathImageCheckIn = public_path('images/billings/' . $this->image_amount);
            if (file_exists($filePathImageCheckIn)) {
                unlink($filePathImageCheckIn);
            } else {
                \Log::warning("File not found for pruning: " . $filePathImageCheckIn);
            }
        }

        if ($this->signature_officer) {
            $filePathImageCheckOut = public_path('images/billings/' . $this->signature_officer);
            if (file_exists($filePathImageCheckOut)) {
                unlink($filePathImageCheckOut);
            } else {
                \Log::warning("File not found for pruning: " . $filePathImageCheckOut);
            }
        }

        if ($this->signature_customer) {
            $filePathImageCheckOut = public_path('images/billings/' . $this->signature_customer);
            if (file_exists($filePathImageCheckOut)) {
                unlink($filePathImageCheckOut);
            } else {
                \Log::warning("File not found for pruning: " . $filePathImageCheckOut);
            }
        }
    }
}
