<?php

namespace App\Catalogue\Application\Query;

readonly class GetProductsQuery
{
    public function __construct(
        public bool $onlyAvailable = false,
        public ?int $maxPrice = null
    ) {
    }
}
