<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 18/04/19
 * Time: 11:43
 */

namespace App\Services;


class PricingRule
{
    /**
     * Apply a pricing rule
     *
     * @param array $item
     * @param array $rule
     * @return float
     * @throws \Exception
     */
    public static function apply(array $item, array $rule)
    {
        if (method_exists(__CLASS__, $rule['name'])) {
            return call_user_func(array(__CLASS__, $rule['name']), $item, $rule['args'] ?? []);
        }

        throw new \Exception('Pricing rule not found');
    }


    /**
     * Apply the 2-for-1 rule in an item
     *
     * @param array $item
     * @param array $ruleArgs
     * @return float
     */
    private static function twoForOne(array $item, array $ruleArgs): float
    {
        return (float)((intdiv($item['quantity'], 2) + ($item['quantity'] % 2)) * $item['unitaryPrice']);
    }

    /**
     * Apply the bulk rule in an item
     *
     * @param array $item
     * @param array $ruleArgs
     * @return float
     * @throws \Exception
     */
    private static function bulk(array $item, array $ruleArgs): float
    {
        if (!isset($ruleArgs['minOrder']) or !isset($ruleArgs['discount'])) {

            throw new \Exception('Invalid input for bulk pricing rule');
        }

        if ($item['quantity'] >= $ruleArgs['minOrder']) {
            $item['unitaryPrice'] -= $ruleArgs['discount'];
        }

        return (float)($item['quantity'] * $item['unitaryPrice']);
    }

}
