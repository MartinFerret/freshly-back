<?php

namespace App\Controller;

use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/api/v1/orders', name: 'api_orders', methods: ['GET'])]
    public function getOrders(OrderService $orderService): JsonResponse
    {
        return $this->json(
            $orderService->getAllOrders(),
            Response::HTTP_OK,
            [],
            ['groups' => ['order-list', 'show_technology'],
                'circular_reference_handler' => function ($order) {
                    return $order->getId();
                },]
        );

    }
}
