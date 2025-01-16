<?php

namespace App\Enums;

enum BillingStatusEnum: string
{
    case Visit = "visit";
    case PromiseToPay = "promise_to_pay";
    case Pay = "pay";

    public function label(): string
    {
        return match ($this) {
            self::Visit => "Kunjungan",
            self::PromiseToPay => "Janji bayar",
            self::Pay => "Bayar",
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
}
