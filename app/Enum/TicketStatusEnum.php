<?php

namespace App\Enum;

enum TicketStatusEnum: int
{
    case New = 1;
    case InProgress = 2;
    case Processed = 3;

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новый',
            self::InProgress => 'В обработке',
            self::Processed => 'Обработан',
        };
    }

    public static function values(): array
    {
        return [
            self::New,
            self::InProgress,
            self::Processed,
        ];
    }

    public static function labels(): array
    {
        return [
            self::New->value => self::New->label(),
            self::InProgress->value => self::InProgress->label(),
            self::Processed->value => self::Processed->label(),
        ];
    }
}
