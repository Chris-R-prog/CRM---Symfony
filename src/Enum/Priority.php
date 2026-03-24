<?php

namespace App\Enum;

enum Priority: string
{
    case Low = 'low';
    case BAU = 'medium';
    case High = 'high';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::BAU => 'BAU',
            self::High => 'high',
        };
    }
}
