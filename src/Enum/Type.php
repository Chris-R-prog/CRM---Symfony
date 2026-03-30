<?php

namespace App\Enum;

enum Type: string
{
    case Client = 'c';
    case Prospect = 'p';

    public function label(): string
    {
        return match ($this) {
            self::Client => 'Client',
            self::Prospect => 'Prospect',
        };
    }
}
