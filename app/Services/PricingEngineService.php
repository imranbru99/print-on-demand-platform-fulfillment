<?php

declare(strict_types=1);

namespace App\Services;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

final class PricingEngineService
{
    public function add(string $a, string $b): string
    {
        return BigDecimal::of($a)->plus($b)->toScale(4, RoundingMode::HALF_UP)->__toString();
    }

    public function subtract(string $a, string $b): string
    {
        return BigDecimal::of($a)->minus($b)->toScale(4, RoundingMode::HALF_UP)->__toString();
    }

    public function multiply(string $a, string $b): string
    {
        return BigDecimal::of($a)->multipliedBy($b)->toScale(4, RoundingMode::HALF_UP)->__toString();
    }

    public function lineTotal(string $unitPrice, int $quantity): string
    {
        return $this->multiply($unitPrice, (string) $quantity);
    }

    /**
     * @param  iterable<array{unit_price: string, quantity: int}>  $lines
     */
    public function sumLines(iterable $lines): string
    {
        $total = BigDecimal::zero();

        foreach ($lines as $line) {
            $total = $total->plus(BigDecimal::of($this->lineTotal($line['unit_price'], $line['quantity'])));
        }

        return $total->toScale(4, RoundingMode::HALF_UP)->__toString();
    }

    public function compare(string $a, string $b): int
    {
        return BigDecimal::of($a)->compareTo(BigDecimal::of($b));
    }
}
