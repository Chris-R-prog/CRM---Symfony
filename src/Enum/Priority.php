<?php

namespace App\Enum;

enum Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGHT = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Faible',
            self::MEDIUM => 'Normale',
            self::HIGHT => 'Haute',
            self::URGENT => 'Urgent'
        };
    }
}
