<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product();
        $product->code = "TICKET";
        $product->name = "Triggerise Ticket";
        $product->price = 5.00;

        $product->save();

        $product = new Product();
        $product->code = "HOODIE";
        $product->name = "Triggerise Hoodie";
        $product->price = 20.00;

        $product->save();

        $product = new Product();
        $product->code = "HAT";
        $product->name = "Triggerise Hat";
        $product->price = 7.50;

        $product->save();
    }
}
