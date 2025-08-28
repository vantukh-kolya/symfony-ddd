<?php

namespace App\Catalogue\Application\Command;

readonly class CreateProductCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public int $price,
        public int $onHand,
    ) {
    }
}
