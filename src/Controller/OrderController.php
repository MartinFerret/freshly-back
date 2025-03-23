<?php

namespace App\Controller;

use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            ['groups' => ['order-list'],
                'circular_reference_handler' => function ($order) {
                    return $order->getId();
                }]
        );
    }

    #[Route('/api/v1/orders/{id}/status', name: 'update_order_status', methods: ['PUT'])]
    public function updateOrderStatus(int $id, Request $request, OrderService $orderService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['state'])) {
            return $this->json(['error' => 'State is required'], Response::HTTP_BAD_REQUEST);
        }

        $updatedOrder = $orderService->updateOrderStatus($id, $data['state']);

        return $this->json(
            $updatedOrder,
            Response::HTTP_OK,
            [],
            [
                'groups' => ['order-list'],
                'circular_reference_handler' => function ($order) {
                    return $order->getId();
                }
            ]
        );
    }

    #[Route('/api/v1/orders/{id}', name: 'delete_order', methods: ['DELETE'])]
    public function deleteOrder(int $id, OrderService $orderService): Response
    {
        try {
            $orderService->deleteOrderById($id);
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
