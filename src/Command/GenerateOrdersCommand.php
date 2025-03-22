<?php

namespace App\Command;

use App\Entity\Order;
use App\Entity\Product;
use Cassandra\Date;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setDate(new \DateTime())
                ->setFirstname('Firstname ' . $i)
                ->setLastname('Lastname ' . $i)
                ->setAddress('123 Street ' . $i)
                ->setCountry('Country ' . $i)
                ->setCreatedAt(new DateTimeImmutable())
                ->setState('State ' . $i);

            for ($j = 0; $j < 3; $j++) {
                $product = new Product();
                $product->setName('Product ' . $j)
                    ->setDescription('Description ' . $j)
                    ->setPrice(mt_rand(10, 100))
                    ->setSku('SKU-' . $i . '-' . $j)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setQuantity(mt_rand(1, 50));
                $order->addProduct($product);
                $this->entityManager->persist($product);
            }

            $this->entityManager->persist($order);
        }

        $this->entityManager->flush();

        $output->writeln('10 orders with products generated successfully.');
        return Command::SUCCESS;
    }
}

