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
            self::Pending => "Menunggu",
            self::Process => "Diproses",
            self::Success => "Berhasil",
            self::Cancel => "Gagal",
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
}
