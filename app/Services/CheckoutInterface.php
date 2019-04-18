<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 18/04/19
 * Time: 19:59
 */

namespace App\Services;


interface CheckoutInterface
{
    public function getItems(): array;

    public function scan(string $productCode): void;

    public function total(): float;

    public function clear(): void;
}
