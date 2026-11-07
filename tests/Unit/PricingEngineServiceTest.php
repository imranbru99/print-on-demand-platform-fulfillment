<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\PricingEngineService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PricingEngineServiceTest extends TestCase
{
    private PricingEngineService $pricing;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricing = new PricingEngineService;
    }

    #[Test]
    public function it_adds_decimal_amounts_without_float_drift(): void
    {
        $this->assertSame('0.3000', $this->pricing->add('0.1000', '0.2000'));
        $this->assertSame('1.0000', $this->pricing->add('0.9999', '0.0001'));
    }

    #[Test]
    public function it_computes_line_totals_and_sums_lines(): void
    {
        $lineTotal = $this->pricing->lineTotal('19.9900', 3);
        $this->assertSame('59.9700', $lineTotal);

        $sum = $this->pricing->sumLines([
            ['unit_price' => '10.5000', 'quantity' => 2],
            ['unit_price' => '4.2500', 'quantity' => 4],
        ]);

        $this->assertSame('38.0000', $sum);
    }

    #[Test]
    public function it_compares_decimal_strings(): void
    {
        $this->assertSame(0, $this->pricing->compare('10.0000', '10.0000'));
        $this->assertSame(-1, $this->pricing->compare('9.9999', '10.0000'));
        $this->assertSame(1, $this->pricing->compare('10.0001', '10.0000'));
    }
}
