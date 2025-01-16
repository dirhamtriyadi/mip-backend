<?php

namespace App\Enums;

enum BillingStatusEnum: string
{
    case Pending = "pending";
    case Process = "process";
    case Success = "success";
    case Cancel = "cancel";

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('enums.billing_status.pending'),
            self::Process => __('enums.billing_status.process'),
            self::Success => __('enums.billing_status.success'),
            self::Cancel => __('enums.billing_status.cancel'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => "warning",
            self::Process => "info",
            self::Success => "success",
            self::Cancel => "danger",
        };
    }

    public static function all(): array
    {
        return array_map(fn ($value) => $value::label(), self::values());
    }
}
