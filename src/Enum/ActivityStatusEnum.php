<?php

namespace App\Enum;

enum ActivityStatusEnum: string
{
    case TODO = 'todo';
    case DONE = 'done';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'À faire',
            self::DONE => 'Réalisé',
            self::CANCELED => 'Annulé'
        };
    }
}
