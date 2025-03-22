<?php

// src/Command/GenerateOrdersCommand.php

namespace App\Command;

use App\Entity\Order;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use App\Service\Order\OrderTotalCalculator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-orders',
    description: 'Generate 10 orders with associated products'
)]
class GenerateOrdersCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, OrderTotalCalculator $orderTotalCalculator)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->orderTotalCalculator = $orderTotalCalculator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $existingOrders = $this->entityManager->getRepository(Order::class)->findAll();
        foreach ($existingOrders as $order) {
            $this->entityManager->remove($order);
        }
        $this->entityManager->flush();

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setDate(new \DateTime())
                ->setFirstname('Firstname ' . $i)
                ->setLastname('Lastname ' . $i)
                ->setAddress('123 Street ' . $i)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCountry('Country ' . $i);

            $states = OrderStatus::cases();
            $randomState = $states[array_rand($states)];
            $order->setState($randomState->name);
            $products = [];
            $productCount = mt_rand(1, 5);
            for ($j = 0; $j < $productCount; $j++) {
                $product = new Product();
                $product->setName('Product ' . $j)
                    ->setDescription('Description ' . $j)
                    ->setPrice(mt_rand(10, 100))
                    ->setSku('SKU-' . $i . '-' . $j)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setQuantity(mt_rand(1, 4));

                $order->addProduct($product);
                $this->entityManager->persist($product);
                $products[] = $product;
            }
            $totalPrice = $this->orderTotalCalculator->calculateTotal($products);
            $order->setTotalPrice($totalPrice);
            $this->entityManager->persist($order);
        }

        $this->entityManager->flush();

        $output->writeln('10 orders with products (1 to 5 per order) generated successfully.');
        return Command::SUCCESS;
    }
}


