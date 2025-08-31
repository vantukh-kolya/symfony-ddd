<?php

namespace App\SharedKernel\Domain\ValueObject;

final class Money
{
    private const SCALE = 2; // fixed minor units (e.g., cents)
    private int $amountMinor; // stored in minor units only

    private function __construct(int $amountMinor)
    {
        if ($amountMinor < 0) {
            throw new \InvalidArgumentException('Money cannot be negative.');
        }
        $this->amountMinor = $amountMinor;
    }

    public static function fromMinor(int $amountMinor): self
    {
        return new self($amountMinor);
    }

    public function toMinor(): int
    {
        return $this->amountMinor;
    }

    public function toMajorString(): string
    {
        if (self::SCALE === 0) {
            return (string)$this->amountMinor;
        }
        $base = 10 ** self::SCALE;
        $int = intdiv($this->amountMinor, $base);
        $frac = $this->amountMinor % $base;
        return sprintf('%d.%0' . self::SCALE . 'd', $int, $frac);
    }

    public function equals(self $other): bool
    {
        return $this->amountMinor === $other->amountMinor;
    }

    public function __toString(): string
    {
        return $this->toMajorString();
    }
}
