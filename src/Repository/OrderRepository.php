<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOrdersByStatusAndDate(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.state IN (:statuses)')
            ->setParameter('statuses', ['paid', 'in progress'])
            ->orderBy(
                'CASE 
                WHEN o.state = :paid THEN 1
                WHEN o.state = :progress THEN 2
                ELSE 3
            END', 'ASC'
            )
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('paid', 'paid')
            ->setParameter('progress', 'in progress')
            ->getQuery()
            ->getResult();
    }
}
