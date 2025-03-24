<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\Order\OrderTotalCalculator;
use PHPUnit\Framework\TestCase;

class OrderTotalCalculatorTest extends TestCase
{
    public function testCalculateTotal(): void
    {
        $calculator = new OrderTotalCalculator();

        $product1 = $this->createMock(Product::class);
        $product1->method('getPrice')->willReturn(10.0);
        $product1->method('getQuantity')->willReturn(2);

        $product2 = $this->createMock(Product::class);
        $product2->method('getPrice')->willReturn(20.0);
        $product2->method('getQuantity')->willReturn(1);

        $total = $calculator->calculateTotal([$product1, $product2]);

        $this->assertEquals(40.0, $total, 'The total price calculation is incorrect.');
    }

    public function testCalculateTotalWithEmptyProducts(): void
    {
        $calculator = new OrderTotalCalculator();

        $total = $calculator->calculateTotal([]);

        $this->assertEquals(0.0, $total, 'The total price of an empty product list should be 0.');
    }
}