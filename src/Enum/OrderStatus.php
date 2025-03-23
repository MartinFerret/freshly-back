<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case IN_PROGRESS = 'in progress';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}
