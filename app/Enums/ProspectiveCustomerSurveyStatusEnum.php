<?php

namespace App\Enums;

enum ProspectiveCustomerSurveyStatusEnum: string
{
    case Pending = "pending";
    case Ongoing = "ongoing";
    case Done = "done";

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('enums.prospective_customer_surveys_status.pending'),
            self::Ongoing => __('enums.prospective_customer_surveys_status.ongoing'),
            self::Done => __('enums.prospective_customer_surveys_status.done'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => "info",
            self::Ongoing => "warning",
            self::Done => "success",
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
