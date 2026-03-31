<?php

namespace App\Enum;

enum Direction: string
{
    case INBOUND = 'INBOUND';
    case OUTBOUND = 'OUTBOUND';
    case INTERNAL = 'INTERNAL';

    public function label(): string
    {
        return match ($this) {
            self::INBOUND => 'Inbound',
            self::OUTBOUND => 'Outbound',
            self::INTERNAL => 'Internal',
        };
    }
}
