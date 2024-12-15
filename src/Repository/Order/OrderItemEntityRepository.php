<?php

namespace App\Repository\Order;

use App\ApiResource\Order\OrderItemDTO;
use App\ApiResource\Order\SetStatusDTO;
use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderItemEntity;
use App\Entity\Users\CookEntity;
use App\Enum\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @extends ServiceEntityRepository<OrderItemEntity>
 */
class OrderItemEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItemEntity::class);
    }

    public function createOrderItem(OrderItemDTO $orderItem): int
    {
        $entityManager = $this->getEntityManager();

        $orderItemEntity = new OrderItemEntity();
        $orderItemEntity->setOrderEntity(
            $entityManager->getRepository(OrderEntity::class)->find($orderItem->getOrderId()),
        );

        $orderItemEntity->setStatus(OrderStatus::taken->value);

        $entityManager->persist($orderItemEntity);
        $entityManager->flush();
        return $orderItemEntity->getId();
    }

    public function updateOrderItem(SetStatusDTO $setStatusDTO): void
    {
        $entityManager = $this->getEntityManager();

        $orderItemEntity = $this
            ->createQueryBuilder('o')
            ->select('o')
            ->where('o.id= :id')
            ->setParameter('id', $setStatusDTO->getOrderItemId())
            ->getQuery()
            ->getOneOrNullResult();

        if (!$orderItemEntity) {
            throw  HttpException::fromStatusCode(400);
        }

        $orderItemEntity->setStatus($setStatusDTO->getStatus());
        $cookId = $setStatusDTO->getCookId();

        if (isset($cookId)) {
            $cook = $entityManager->getRepository(CookEntity::class)->find
            ($cookId);
            $orderItemEntity->setCook($cook);
        }

        $entityManager->persist($orderItemEntity);
        $entityManager->flush();
    }
}
