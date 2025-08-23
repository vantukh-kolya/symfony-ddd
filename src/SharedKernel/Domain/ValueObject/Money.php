<?php

namespace App\SharedKernel\Domain\ValueObject;

final class Money
{
    private int $amount;

    private function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money cannot be negative.');
        }
        $this->amount = $amount;
    }

    public static function fromInt(int $amount): self
    {
        return new self($amount);
    }

    public static function fromFloat(float $amount, int $scale = 2): self
    {
        return new self((int)round($amount * (10 ** $scale)));
    }

    public function toInt(): int
    {
        return $this->amount;
    }

    public function toFloat(int $scale = 2): float
    {
        return $this->amount / (10 ** $scale);
    }

    public function __toString(): string
    {
        return number_format($this->toFloat(), 2, '.', '');
    }
}
