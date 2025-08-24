<?php

namespace App\Catalogue\Application\Query;

readonly class GetProductsQuery
{
    public function __construct(
        public readonly bool $onlyAvailable = false,
        public readonly ?int $maxPrice = null
    ) {
    }
}
