<?php

namespace App\Enums;

enum ProspectiveCustomerSurveyRecommendationPtEnum: string
{
    case YES = "yes";
    case NO = "no";

    public function label(): string
    {
        return match ($this) {
            self::YES => __('enums.prospective_customer_surveys_recommendation_pt.yes'),
            self::NO => __('enums.prospective_customer_surveys_recommendation_pt.no'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::YES => "success",
            self::NO => "danger",
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
