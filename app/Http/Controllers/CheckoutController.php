<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\Checkout;

class CheckoutController extends Controller
{
    /**
     * @var Checkout
     */
    private $checkoutService;

    /**
     * Example of the use of the service in a Controller
     *
     * CheckoutRequest handles validation rules
     *
     * @param CheckoutRequest $request
     */
    public function init(CheckoutRequest $request)
    {
        $pricingRules = $request->validated();

        $this->checkoutService = new Checkout($pricingRules);
    }

}
