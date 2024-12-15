<?php

namespace App\Service;

use App\Entity\Menu\MenuEntity;
use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderItemEntity;
use App\Enum\UserRole;

class MenuSearchService
{
    /**
     * @param MenuEntity[] $menuItems
     */
    public function filterMenuItemsByName(array $menuItems, string $searchString): array
    {
        return array_filter($menuItems, function ($item) use ($searchString) {
            $name = mb_convert_encoding(mb_strtolower($item->getName(), 'UTF-8'), 'UTF-8', 'auto');
            $searchToLowercase = mb_convert_encoding(mb_strtolower
            ($searchString, 'UTF-8'), 'UTF-8', 'auto');
            
            return mb_stripos($name, $searchToLowercase, 0, 'UTF-8') !== false;
        });
    }


    /**
     * @param MenuEntity[] $menuEntities
     * @param OrderItemEntity[] $orderItemEntities
     */
    public function filterMenuItemsByOrderMenuQuantity(array $menuEntities,
                                                       array $orderItemEntities):
    array
    {
        $menuFrequency = [];

        foreach ($orderItemEntities as $orderItem) {
            foreach ($orderItem->getOrderMenuItems() as $orderMenuItem) {
                $menuId = $orderMenuItem->getMenuItem()->getId();
                if (!isset($menuFrequency[$menuId])) {
                    $menuFrequency[$menuId] = 0;
                }
                $menuFrequency[$menuId] += $orderMenuItem->getCount();
            }
        }

        usort($menuEntities, function ($a, $b) use ($menuFrequency) {
            $idA = $a->getId();
            $idB = $b->getId();

            $frequencyA = $menuFrequency[$idA] ?? 0;
            $frequencyB = $menuFrequency[$idB] ?? 0;

            return $frequencyB <=> $frequencyA;
        });

        return $menuEntities;
    }

    /**
     * @param OrderEntity[] $orderEntities
     */

    public function filterOrderItemsByUserRole(array $orderEntities, UserRole $userRole, int $userId): array
    {
        $filteredOrderItems = [];

        foreach ($orderEntities as $orderEntity) {
            if ($userRole === UserRole::manager) {
                foreach ($orderEntity->getOrderItems() as $orderItem) {
                    $filteredOrderItems[] = $orderItem;
                }
            } elseif ($userRole === UserRole::cook) {
                foreach ($orderEntity->getOrderItems() as $orderItem) {
                    if ($orderItem->getCook() && $orderItem->getCook()->getId() === $userId) {
                        $filteredOrderItems[] = $orderItem;
                    }
                }
            } elseif ($userRole === UserRole::waiter) {
                if ($orderEntity->getWaiter() && $orderEntity->getWaiter()->getId() === $userId) {
                    foreach ($orderEntity->getOrderItems() as $orderItem) {
                        $filteredOrderItems[] = $orderItem;
                    }
                }
            }
        }

        return $filteredOrderItems;
    }
}