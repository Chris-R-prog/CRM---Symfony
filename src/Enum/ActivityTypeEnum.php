<?php

namespace App\Enum;

enum ActivityTypeEnum: string
{
    case TASK = 'task';
    case ADMIN = 'admin';
    case CALL = 'call';
    case DEMO = 'demo';
    case EMAIL = 'email';
    case MEETING = 'meeting';
    case PROPOSAL = 'proposal';
    case REMINDER = 'reminder';
    case FOLLOW_UP = 'follow_up';
    case VISIO = 'visio';

    public function label(): string
    {
        return match ($this) {
            self::TASK => 'À faire',
            self::ADMIN => 'Administratif',
            self::CALL => 'Appel',
            self::DEMO => 'Démo',
            self::EMAIL => 'Email',
            self::MEETING => 'Meeting',
            self::PROPOSAL => 'Proposition Commerciale',
            self::REMINDER => 'Rappel',
            self::FOLLOW_UP => 'Relance',
            self::VISIO => 'Visio'
        };
    }
}
