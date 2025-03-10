<?php

namespace App\Enums;

enum AttendanceTypeEnum: string
{
    case Present = 'present';
    case Sick = 'sick';
    case Permit = 'permit';

    public function label(): string
    {
        return match ($this) {
            self::Present => __('enums.attendanceType.present'),
            self::Sick => __('enums.attendanceType.sick'),
            self::Permit => __('enums.attendanceType.permit'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Present => "info",
            self::Sick => "warning",
            self::Permit => "success",
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
