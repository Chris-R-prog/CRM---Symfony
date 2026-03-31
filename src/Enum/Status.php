<?php

namespace App\Enum;

enum Status: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case QUALIFIED = 'qualified';
    case CONVERTED = 'converted';
    case LOST = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Nouveau',
            self::CONTACTED => 'Contacté',
            self::QUALIFIED => 'Qualifié',
            self::CONVERTED => 'Converti',
            self::LOST => 'Perdu',
        };
    }
}
