<?php

namespace App\Enums;

enum BillingStatusesEnum: string
{
    case Visit = "visit";
    case PromiseToPay = "promise_to_pay";
    case Pay = "pay";

    public function label(): string
    {
        return match ($this) {
            self::Visit => __('enums.billingStatus_status.visit'),
            self::PromiseToPay => __('enums.billingStatus_status.promise_to_pay'),
            self::Pay => __('enums.billingStatus_status.pay'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Visit => "info",
            self::PromiseToPay => "warning",
            self::Pay => "success",
        };
    }

    public static function search(string $query): array
    {
        return array_values(array_filter(self::cases(), function ($status) use ($query) {
            return stripos($status->label(), $query) !== false;
        }));
    }

    public static function all(): array
    {
        return array_map(fn ($value) => $value::label(), self::values());
    }
}
