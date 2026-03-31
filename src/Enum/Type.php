<?php

namespace App\Enum;

enum Type: string
{
    case CLIENT = 'c';
    case PROSPECT = 'p';

    public function label(): string
    {
        return match ($this) {
            self::CLIENT => 'Client',
            self::PROSPECT => 'Prospect',
        };
    }
}
