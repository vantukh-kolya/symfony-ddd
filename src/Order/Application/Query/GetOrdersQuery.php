<?php

namespace App\Order\Application\Query;

readonly class GetOrdersQuery
{
    public function __construct(public ?string $status)
    {
    }
}
