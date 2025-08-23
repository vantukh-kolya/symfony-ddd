<?php

namespace App\Order\Domain\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case RESERVED = 'reserved';
    case RESERVATION_FAILED = 'reservation_failed';
    case CANCELLED = 'cancelled';
}
