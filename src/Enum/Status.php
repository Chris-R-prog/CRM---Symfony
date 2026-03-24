<?php

namespace App\Enum;

enum Status: string
{
    case Nouveau = 'new';
    case Contacté = 'contacted';
    case Qualifié = 'qualified';
    case Gagné = 'won';
    case Perdu = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::Nouveau => 'Nouveau',
            self::Contacté => 'Contacté',
            self::Qualifié => 'Qualifié',
            self::Gagné => 'Gagné',
            self::Perdu => 'Perdu',
        };
    }
}
