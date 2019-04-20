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

        /**
         * Do some stuff here to normalize the $pricingRules array. That is, put it in the format expected by the service.
         * For example, you could use a custom method:
         * $pricingRules = $this->normalize($pricingRules);
         *
         */

        $this->checkoutService = new Checkout($pricingRules);

        /**
         * Send some response here
         */
    }

}
