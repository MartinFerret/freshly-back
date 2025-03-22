<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAYED = 'payed';
    case IN_PROGRESS = 'progress';
    case CANCELLED = 'cancelled';
}
