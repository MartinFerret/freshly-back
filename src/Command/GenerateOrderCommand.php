<?php


namespace App\Command;

use App\Entity\Order;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Service\Order\OrderTotalCalculator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-order',
    description: 'Generate a single order with 1 to 5 products and a specified status'
)]
class GenerateOrderCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private OrderTotalCalculator $orderTotalCalculator;

    public function __construct(EntityManagerInterface $entityManager, OrderTotalCalculator $orderTotalCalculator)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->orderTotalCalculator = $orderTotalCalculator;
    }

    protected function configure(): void
    {
        $this->addArgument('status', InputArgument::REQUIRED, 'Order status (pending, paid, in_progress, delivered, cancelled)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = $input->getArgument('status');
        if (!in_array(strtolower($status), array_map('strtolower', array_column(OrderStatus::cases(), 'name')))) {
            $output->writeln('<error>Invalid status. Allowed values: pending, paid, in_progress, delivered, cancelled</error>');
            return Command::INVALID;
        }

        $order = new Order();
        $order->setDate(new \DateTime())
            ->setFirstname('Firstname')
            ->setLastname('Lastname')
            ->setAddress('123 Street')
            ->setCreatedAt(new DateTimeImmutable())
            ->setCountry('Country')
            ->setState($status);

        $products = [];
        $productCount = mt_rand(1, 5);
        for ($j = 0; $j < $productCount; $j++) {
            $product = new Product();
            $product->setName('Product ' . $j)
                ->setDescription('Description ' . $j)
                ->setPrice(mt_rand(10, 100))
                ->setSku('SKU-' . $j)
                ->setCreatedAt(new DateTimeImmutable())
                ->setQuantity(mt_rand(1, 4));

            $order->addProduct($product);
            $this->entityManager->persist($product);
            $products[] = $product;
        }

        $totalPrice = $this->orderTotalCalculator->calculateTotal($products);
        $order->setTotalPrice($totalPrice);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $output->writeln('<info>Order created successfully with status: ' . $status . '</info>');
        return Command::SUCCESS;
    }
}
