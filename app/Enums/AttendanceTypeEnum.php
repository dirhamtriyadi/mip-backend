<?php

namespace App\Enums;

enum AttendanceTypeEnum: string
{
    case Present = 'present';
    case Sick = 'sick';
    case Permit = 'permit';
    case Absent = 'absent';

    public function label(): string
    {
        return match ($this) {
            self::Present => __('enums.attendanceType.present'),
            self::Sick => __('enums.attendanceType.sick'),
            self::Permit => __('enums.attendanceType.permit'),
            self::Absent => __('enums.attendanceType.absent'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Present => "success",
            self::Sick => "warning",
            self::Permit => "info",
            self::Absent => "danger",
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
