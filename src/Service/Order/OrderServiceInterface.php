<?php

namespace App\Service\Order;

use App\Entity\Order;

interface OrderServiceInterface
{
    public function getAllOrders();
    public function updateOrderStatus(int $orderId, string $newStatus): Order;
    public function  deleteOrderById(int $orderId);

}
