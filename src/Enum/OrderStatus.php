<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case IN_PROGRESS = 'in progress';
    case CANCELLED = 'cancelled';
    case DELIVERED = 'delivered';
}
