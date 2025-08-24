<?php

namespace App\Order\Domain\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case RESERVED = 'reserved';
    case FULFILLED = 'fulfilled';
    case FAILED = 'failed';
}
