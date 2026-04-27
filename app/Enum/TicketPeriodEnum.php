<?php

namespace App\Enum;

use Illuminate\Support\Carbon;

enum TicketPeriodEnum: string
{
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';

    public function toDays(): int
    {
        return match ($this) {
            self::Day => 1,
            self::Week => 7,
            self::Month => 30,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Day => 'В день',
            self::Week => 'В неделю',
            self::Month => 'В Месяц',
        };
    }

    public function startDate(): Carbon
    {
        return match ($this) {
            self::Day => Carbon::now()->subDay()->startOfDay(),
            self::Week => Carbon::now()->subWeek()->startOfDay(),
            self::Month => Carbon::now()->subMonth()->startOfDay(),
        };
    }
}
