<?php

namespace App\Enum;

enum Title: string
{
    case M = 'm';
    case Mme = 'mme';
    case Autre = 'other';
    case Dr = 'dr';

    public function label(): string
    {
        return match ($this) {
            self::M => 'M',
            self::Mme => 'Mme',
            self::Autre => 'Autre',
            self::Dr => 'Dr',
        };
    }
}
