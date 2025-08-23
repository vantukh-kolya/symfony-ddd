<?php

namespace App\Order\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderCommand
{
    public function __construct(int $amountToPay, array $products)
    {
        $this->amountToPay = $amountToPay;
        $this->products = $products;
    }

    #[Assert\Type('array')]
    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Collection([
            'product_id' => [new Assert\NotBlank, new Assert\Type('string')],
            'quantity' => [new Assert\NotBlank, new Assert\Positive],
            'price' => [new Assert\NotBlank, new Assert\Positive],
        ])
    ])]
    private array $products;
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $amountToPay;

    public function getAmountToPay(): int
    {
        return $this->amountToPay;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

}
