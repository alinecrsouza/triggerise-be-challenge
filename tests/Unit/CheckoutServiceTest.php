<?php

namespace Tests\Unit;

use App\Product;
use App\Services\Checkout;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     * @throws \Exception
     */
    public function testScanIncludesItemInCartCorrectly()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');

        $this->assertArrayHasKey('HOODIE', $co->getItems());
    }

    /**
     * @test
     * @return void
     * @throws \Exception
     */
    public function testScanIncludesItemQuantityInCartCorrectly()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');

        $items = $co->getItems();

        $this->assertEquals(3, $items['HOODIE']['quantity']);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testClearEmptyTheCart()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');
        $co->scan('HOODIE');

        $co->clear();

        $this->assertEmpty($co->getItems());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testTotalReturnTheCorrectValueWithTwoForOneItems()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('TICKET');
        $co->scan('TICKET');

        $product = Product::firstOrCreate(['name' => 'Triggerise Ticket', 'code' => 'TICKET', 'price' => 5.00]);

        $this->assertEquals($product->price, $co->total());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testTotalReturnTheCorrectValueWithBulkItems()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');

        $product = Product::firstOrCreate(['name' => 'Triggerise Hoodie', 'code' => 'HOODIE', 'price' => 20.00]);

        $this->assertEquals(($product->price - 1) * 3, $co->total());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testTotalReturnTheCorrectValueWithMixedItems()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('TICKET');
        $co->scan('TICKET');
        $co->scan('TICKET');
        $co->scan('HAT');
        $co->scan('HAT');

        $hoodie = Product::firstOrCreate(['name' => 'Triggerise Hoodie', 'code' => 'HOODIE', 'price' => 20.00]);
        $ticket = Product::firstOrCreate(['name' => 'Triggerise Ticket', 'code' => 'TICKET', 'price' => 5.00]);
        $hat = Product::firstOrCreate(['name' => 'Triggerise Hat', 'code' => 'HAT', 'price' => 7.5]);

        $expectedTotal = (($hoodie->price - 1) * 5) + ($ticket->price * 2) + ($hat->price * 2);

        $this->assertEquals($expectedTotal, $co->total());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testTotalReturnTheCorrectValueWithTwoItemsInSameRule()
    {
        $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
        $pricingRules['TICKET'] = ['rule' => ['name' => 'twoForOne']];
        $pricingRules['HAT'] = ['rule' => ['name' => 'twoForOne']];

        $co = new Checkout($pricingRules);

        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('HOODIE');
        $co->scan('TICKET');
        $co->scan('TICKET');
        $co->scan('TICKET');
        $co->scan('HAT');
        $co->scan('HAT');

        $hoodie = Product::firstOrCreate(['name' => 'Triggerise Hoodie', 'code' => 'HOODIE', 'price' => 20.00]);
        $ticket = Product::firstOrCreate(['name' => 'Triggerise Ticket', 'code' => 'TICKET', 'price' => 5.00]);
        $hat = Product::firstOrCreate(['name' => 'Triggerise Hat', 'code' => 'HAT', 'price' => 7.5]);

        $expectedTotal = (($hoodie->price - 1) * 5) + ($ticket->price * 2) + ($hat->price);

        $this->assertEquals($expectedTotal, $co->total());
    }
}
