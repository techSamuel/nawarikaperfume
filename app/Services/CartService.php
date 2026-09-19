<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;

class CartService
{
    public static function calculateShipping($cart, $subtotal)
    {
        // 1. Check Free Delivery Threshold
        $freeThreshold = Setting::get('free_delivery_threshold');
        if ($freeThreshold !== null && $freeThreshold !== '' && $subtotal >= (float)$freeThreshold) {
            return 0; // Free delivery
        }

        // 2. Calculate Individual Delivery Charges (Highest charge logic)
        $maxDeliveryCharge = 0;
        $hasIndividualCharge = false;

        if (empty($cart)) {
            return 0; // No shipping if empty cart, shouldn't reach here normally, but safe.
        }

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->delivery_charge !== null) {
                $hasIndividualCharge = true;
                if ($product->delivery_charge > $maxDeliveryCharge) {
                    $maxDeliveryCharge = $product->delivery_charge;
                }
            }
        }

        if ($hasIndividualCharge) {
            return (float)$maxDeliveryCharge;
        }

        // 3. Fallback to Global Delivery Charge
        $globalCharge = Setting::get('global_delivery_charge', 50);
        return (float)$globalCharge;
    }
}
