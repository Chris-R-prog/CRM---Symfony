<?php

namespace App\Enum;

enum Priority: string
{
    case Low = 'low';
    case BAU = 'medium';
    case High = 'high';
}
