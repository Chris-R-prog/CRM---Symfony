<?php

namespace App\Enum;

enum Status: string
{
    case NEW = 'new';
    case CONTACTÉ = 'contacted';
    case QUALIFIÉ = 'qualified';
    case GAGNÉ = 'won';
    case PERDU = 'lost';
}
