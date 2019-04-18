<?php

namespace Tests\Unit;

use App\Services\PricingRule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PricingRuleServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     * @throws \Exception
     */
    public function testTwoForOneRuleReturnsTheCorrectValue()
    {
        $item = ['quantity' => 3, 'unitaryPrice' => 5.00];

        $rule = ['name' => 'twoForOne'];

        $subTotal = PricingRule::apply($item, $rule);

        self::assertEquals(10, $subTotal);
    }

    /**
     * @test
     * @return void
     * @throws \Exception
     */
    public function testBulkRuleReturnsTheCorrectValue()
    {
        $item = ['quantity' => 3, 'unitaryPrice' => 20.00];

        $rule = ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]];

        $subTotal = PricingRule::apply($item, $rule);

        self::assertEquals(57, $subTotal);
    }

    /**
     * @test
     * @return void
     * @throws \Exception
     */
    public function testBulkInvalidInputTrowsException()
    {
        $item = ['quantity' => 3, 'unitaryPrice' => 20.00];

        $rule = ['name' => 'bulk', 'args' => []];

        $this->expectException(\Exception::class);

        PricingRule::apply($item, $rule);
    }
}
