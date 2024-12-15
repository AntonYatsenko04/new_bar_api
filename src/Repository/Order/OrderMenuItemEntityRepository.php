<?php

namespace App\Repository\Order;

use App\ApiResource\Order\OrderMenuItemDTO;
use App\Entity\Menu\MenuEntity;
use App\Entity\Order\OrderItemEntity;
use App\Entity\Order\OrderMenuItemEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderMenuItemEntity>
 */
class OrderMenuItemEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMenuItemEntity::class);
    }

    public function createOrderMenuItemEntity(OrderMenuItemDTO $orderMenuItemDTO): void{
        $orderMenuItemEntity = new OrderMenuItemEntity();

        $orderMenuItemEntity->setCount($orderMenuItemDTO->getCount());

        $em =$this->getEntityManager();
        $menuItem = $em->getRepository(MenuEntity::class)->find($orderMenuItemDTO->getMenuItemId());
        $orderItem = $em->getRepository(OrderItemEntity::class)->find($orderMenuItemDTO->getOrderItemId());

        $orderMenuItemEntity->setMenuItem($menuItem);
        $orderMenuItemEntity->setOrderItem($orderItem);
        $em->persist($orderMenuItemEntity);
        $em->flush();
    }
}
