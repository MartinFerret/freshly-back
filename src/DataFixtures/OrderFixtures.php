<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Service\Order\OrderTotalCalculator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class OrderFixtures extends Fixture
{
    private Generator $faker;
    private OrderTotalCalculator $orderTotalCalculator;
    private Connection $connexion;

    public function __construct(OrderTotalCalculator $orderTotalCalculator, Connection $connexion)
    {
        $this->faker = Factory::create();
        $this->orderTotalCalculator = $orderTotalCalculator;
        $this->connexion = $connexion;
    }

    public function truncate()
    {
        $this->connexion->executeQuery('SET foreign_key_checks = 0');
        $this->connexion->executeQuery('TRUNCATE TABLE product');
        $this->connexion->executeQuery('TRUNCATE TABLE order');
    }

    public function load(ObjectManager $manager): void
    {
        $orders = $manager->getRepository(Order::class)->findAll();
        foreach ($orders as $order) {
            $manager->remove($order);
        }
        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setDate($this->faker->dateTimeThisMonth())
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setAddress($this->faker->address())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setCountry($this->faker->country());

            $states = OrderStatus::cases();
            $randomState = $states[array_rand($states)];
            $order->setState($randomState->value);

            $products = [];
            $productCount = $this->faker->numberBetween(1, 5);
            for ($j = 0; $j < $productCount; $j++) {
                $product = new Product();
                $product->setName($this->faker->word())
                    ->setDescription($this->faker->sentence())
                    ->setPrice($this->faker->numberBetween(10, 100))
                    ->setSku($this->faker->uuid())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setQuantity($this->faker->numberBetween(1, 4));

                $order->addProduct($product);
                $manager->persist($product);
                $products[] = $product;
            }

            $totalPrice = $this->orderTotalCalculator->calculateTotal($products);
            $order->setTotalPrice($totalPrice);

            $manager->persist($order);
        }

        $manager->flush();
    }
}
