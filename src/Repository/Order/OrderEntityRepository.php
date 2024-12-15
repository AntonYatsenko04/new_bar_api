<?php

namespace App\Repository\Order;

use App\ApiResource\Order\OrderDTO;
use App\Entity\Order\OrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderEntity>
 */
class OrderEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEntity::class);
    }

    public function createOrderEntity(OrderDTO $orderDTO): int
    {
        $order = new OrderEntity();

        $entityManager = $this->getEntityManager();

        $tableQuery=$entityManager->createQuery("SELECT t FROM App\Entity\Table\TableEntity t WHERE t.id = :id");
        $tableQuery->setParameter('id', $orderDTO->getTableId());
        $tableEntity =  $tableQuery->getResult()[0];

        $waiterQuery=$entityManager->createQuery("SELECT m FROM App\Entity\Users\WaiterEntity m WHERE m.id = :id");
        $waiterQuery->setParameter('id', $orderDTO->getWaiterId());
        $waiterEntity =  $waiterQuery->getResult()[0];

        $order->setWaiter($waiterEntity);
        $order->setTableEntity($tableEntity);

        $entityManager->persist($order);
        $entityManager->flush();
        return $order->getId();
    }


}
