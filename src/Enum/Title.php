<?php

namespace App\Enum;

enum Title: string
{
    case MR = 'mr';
    case MRS = 'mrs';
    case OTHER = 'other';
    case DR = 'dr';

    public function label(): string
    {
        return match ($this) {
            self::MR => 'M.',
            self::MRS => 'Mme',
            self::OTHER => 'Autre',
            self::DR => 'Dr',
        };
    }
}
