<?php

namespace App\Service\Order;

class OrderTotalCalculator
{
    public function calculateTotal(array $products): float
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPrice() * $product->getQuantity();
        }

        return $totalPrice;
    }
}