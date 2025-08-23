<?php

namespace App\Catalogue\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductCommand
{
    public function __construct(string $name, int $price, int $onHand)
    {
        $this->name = $name;
        $this->price = $price;
        $this->onHand = $onHand;
    }

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $name;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private int $price;
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private int $onHand;

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getOnHand(): int
    {
        return $this->onHand;
    }

}
