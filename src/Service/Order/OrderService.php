<?php

namespace App\Service\Order;

use App\Repository\OrderRepository;

class OrderService implements OrderServiceInterface
{
    private OrderRepository $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders() : array
    {
        return $this->orderRepository->findOrdersByStatusAndDate();
    }
}