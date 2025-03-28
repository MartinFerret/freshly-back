<?php

namespace App\Service\Order;

use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function getAllOrders() : array
    {
        return $this->orderRepository->findOrdersByStatusAndDate();
    }

    public function updateOrderStatus(int $orderId, string $newStatus): Order
    {
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if (!$order) {
            throw new \Exception('Order not found');
        }

        $validStatuses = array_map(fn($status) => $status->value, OrderStatus::cases());

        if (!in_array($newStatus, $validStatuses, true)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        $this->checkAndUpdateOrderStatus($order, $newStatus);
        $this->entityManager->flush();

        return $order;
    }

    private function checkAndUpdateOrderStatus(Order $order, string $newStatus): void
    {
        $currentStatus = $order->getState();

        if ($currentStatus === 'in progress' && $newStatus === 'paid') {
            $order->setState($newStatus);
        } elseif ($currentStatus === 'paid' && $newStatus === 'delivered') {
            $order->setState($newStatus);
        } else {
            throw new \InvalidArgumentException('Invalid state transition');
        }
    }

    public function deleteOrderById(int $orderId): void
    {
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }
}
