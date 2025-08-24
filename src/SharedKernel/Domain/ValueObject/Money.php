<?php

namespace App\SharedKernel\Domain\ValueObject;

final class Money
{
    private int $amount;
    private int $scale;

    private function __construct(int $amount, int $scale = 2)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money cannot be negative.');
        }
        $this->amount = $amount;
        $this->scale = $scale;
    }

    public static function fromInt(int $amount): self
    {
        return new self($amount);
    }

    public function toInt(): int
    {
        return $this->amount;
    }

    public function toFloat(): float
    {
        return $this->amount / (10 ** $this->scale);
    }

    public function __toString(): string
    {
        return number_format($this->toFloat(), 2, '.', '');
    }
}
