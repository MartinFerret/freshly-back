<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends WebTestCase
{
    public function testGetOrders(): void
    {
        $client = static::createClient();

        $orderServiceMock = $this->createMock(OrderService::class);
        $orderServiceMock->method('getAllOrders')->willReturn([
            ['id' => 1, 'state' => 'pending'],
            ['id' => 2, 'state' => 'completed'],
        ]);

        static::getContainer()->set(OrderService::class, $orderServiceMock);

        $client->request('GET', '/api/v1/orders');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertContains(['id' => 1, 'state' => 'pending'], $responseData);
    }

    public function testUpdateOrderStatus(): void
    {
        $client = static::createClient();

        $order = new Order();
        $order->setId(1);
        $order->setState('completed');

        $orderServiceMock = $this->createMock(OrderService::class);
        $orderServiceMock->method('updateOrderStatus')->willReturn($order);

        static::getContainer()->set(OrderService::class, $orderServiceMock);

        $client->request(
            'PUT',
            '/api/v1/orders/1/status',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['state' => 'completed'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame(1, $responseData['id']);
        $this->assertSame('completed', $responseData['state']);
    }

    public function testDeleteOrder(): void
    {
        $client = static::createClient();

        $orderServiceMock = $this->createMock(OrderService::class);
        $orderServiceMock->expects($this->once())->method('deleteOrderById');

        static::getContainer()->set(OrderService::class, $orderServiceMock);

        $client->request('DELETE', '/api/v1/orders/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}