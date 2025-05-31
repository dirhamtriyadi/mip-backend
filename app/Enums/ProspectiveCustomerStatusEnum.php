<?php

namespace App\Enums;

enum ProspectiveCustomerStatusEnum: string
{
    case Pending = "pending";
    case Approved = "approved";
    case Rejected = "rejected";

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('enums.prospective_customer_status.pending'),
            self::Approved => __('enums.prospective_customer_status.approved'),
            self::Rejected => __('enums.prospective_customer_status.rejected'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => "info",
            self::Approved => "success",
            self::Rejected => "danger",
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
        return array_map(fn ($value) => $value->label(), self::cases());
    }
}
