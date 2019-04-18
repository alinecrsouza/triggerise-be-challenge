<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 18/04/19
 * Time: 11:39
 */

namespace App\Services;


use App\Product;

class Checkout implements CheckoutInterface
{
    /**
     * The pricing rules
     *
     * @var array
     *
     * Examples:
     * $pricingRules['TICKET'] = ['rule' => [ 'name' => 'twoForOne']]
     * $pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]]
     */
    private $pricingRules = array();

    /**
     * @var array
     */
    private $items = array();

    /**
     * Checkout constructor.
     * @param array $pricingRules
     */
    public function __construct(array $pricingRules)
    {
        $this->pricingRules = $pricingRules;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Scan a product
     *
     * @param string $productCode
     * @throws \Exception
     */
    public function scan(string $productCode): void
    {
        $product = Product::where('code', $productCode)->first();

        if ($product) {
            if (array_key_exists($productCode, $this->items)) {
                $this->items[$productCode]['quantity']++;
            } else {
                $this->items[$productCode]['quantity'] = 1;
                $this->items[$productCode]['unitaryPrice'] = $product->price;
            }
        } else {

            throw new \Exception('Product not found');
        }
    }

    /**
     * Get the total of the cart
     *
     * @throws \Exception
     * @return float;
     */
    public function total(): float
    {
        $total = 0;

        foreach ($this->items as $key => $item) {
            if (array_key_exists($key, $this->pricingRules)) {
                $total += PricingRule::apply($item, $this->pricingRules[$key]['rule']);
            } else {
                $total += $item['quantity'] * $item['unitaryPrice'];
            }
        }

        return (float)$total;
    }

    /**
     * Clear cart
     */
    public function clear(): void
    {
        $this->items = [];
    }
}
