<?php

namespace App\Service;

use App\Entity\CriteriaWeights;
use App\Entity\Menu\MenuEntity;
use App\Entity\Order\OrderEntity;
use App\Entity\Order\OrderItemEntity;
use App\Enum\UserRole;

class MenuSearchService
{
    /**
     * @var MenuEntity[]
     */
    private array $menuItems;

    /**
     * @var OrderEntity[]
     */
    private array $orderItems;
    private CriteriaWeights $criteriaWeights;
    private UserRole $userRole;
    private int $userId;
    private string $searchString;

    /**
     * @param array<int,MenuEntity> $menuItems
     * @param array<int,OrderEntity> $orderItems
     * @param CriteriaWeights $criteriaWeights
     * @param UserRole $userRole
     * @param int $userId
     * @param String $searchString
     */
    public function setData(array           $menuItems, array $orderItems,
                            CriteriaWeights $criteriaWeights, UserRole $userRole, int $userId, string $searchString): void
    {
        $this->menuItems = $menuItems;
        $this->orderItems = $orderItems;
        $this->criteriaWeights = $criteriaWeights;
        $this->userRole = $userRole;
        $this->userId = $userId;
        $this->searchString = $searchString;
    }


    /**
     * @return array<int,MenuEntity>
     */
    public function search(): array
    {
        $nameFilteredMenuItems = $this->_filterMenuItemsByName();
        $userFilteredOrderItemEntities = $this->_filterOrderItemsByUserRole();

        $itemQuantityPoints = $this->_assignPointsToMenuItemsByOrderMenuQuantity(
            $userFilteredOrderItemEntities,
            $nameFilteredMenuItems,
        );

        $orderCountPoints = $this->_assignPointsToMenuItemsByOrderCount(
            $userFilteredOrderItemEntities,
            $nameFilteredMenuItems,
        );

        $pricePercentagePoints = $this->_assignPointsToMenuItemsByPricePercentage(
            $userFilteredOrderItemEntities,
            $nameFilteredMenuItems,
        );

        $totalPoints = [];
        foreach ($nameFilteredMenuItems as $menuItem) {
            $menuId = $menuItem->getId();
            $totalPoints[$menuId] =
                ($itemQuantityPoints[$menuId] ?? 0) +
                ($orderCountPoints[$menuId] ?? 0) +
                ($pricePercentagePoints[$menuId] ?? 0);
        }

        usort($nameFilteredMenuItems, function ($a, $b) use ($totalPoints) {
            return $totalPoints[$b->getId()] <=> $totalPoints[$a->getId()];
        });

        return $nameFilteredMenuItems;
    }


    private function _filterMenuItemsByName(): array
    {
        $menuItems = $this->menuItems;
        $searchString = $this->searchString;
        return array_filter($menuItems, static function ($item) use ($searchString) {
            $name = mb_convert_encoding(mb_strtolower($item->getName(), 'UTF-8'), 'UTF-8', 'auto');
            $searchToLowercase = mb_convert_encoding(mb_strtolower
            ($searchString, 'UTF-8'), 'UTF-8', 'auto');

            return mb_stripos($name, $searchToLowercase, 0, 'UTF-8') !== false;
        });
    }

    private function _filterOrderItemsByUserRole(): array
    {
        $orderEntities = $this->orderItems;
        $userRole = $this->userRole;
        $userId = $this->userId;
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

    /**
     * @param OrderItemEntity[] $orderItemEntities
     * @param MenuEntity[] $menuEntities
     *
     * id, points
     * @return array<int,int>
     */
    private function _assignPointsToMenuItemsByOrderMenuQuantity(
        array $orderItemEntities, array $menuEntities):
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

        $menuToPoints = [];

        for ($i = 0, $iMax = count($menuEntities); $i < $iMax; $i++) {
            $menuEntity = $menuEntities[$i];
            $position = $i + 1;
            $weight = $this->criteriaWeights->getItemQuantity();
            $points = $weight * ($iMax - $position);
            $menuToPoints[$menuEntity->getId()] = $points;
        }

        return $menuToPoints;
    }

    /**
     * @param OrderItemEntity[] $orderItemEntities
     * @param MenuEntity[] $menuEntities
     *
     * id, points
     * @return array<int,int>
     */
    private function _assignPointsToMenuItemsByOrderCount(
        array $orderItemEntities, array $menuEntities,
    ): array
    {
        $menuOrderCount = [];

        foreach ($orderItemEntities as $orderItem) {
            foreach ($orderItem->getOrderMenuItems() as $orderMenuItem) {
                $menuId = $orderMenuItem->getMenuItem()->getId();
                if (!isset($menuOrderCount[$menuId])) {
                    $menuOrderCount[$menuId] = 0;
                }
                $menuOrderCount[$menuId]++;
            }
        }

        usort($menuEntities, function ($a, $b) use ($menuOrderCount) {
            $idA = $a->getId();
            $idB = $b->getId();

            $countA = $menuOrderCount[$idA] ?? 0;
            $countB = $menuOrderCount[$idB] ?? 0;

            return $countB <=> $countA;
        });

        $menuToPoints = [];

        for ($i = 0, $iMax = count($menuEntities); $i < $iMax; $i++) {
            $menuEntity = $menuEntities[$i];
            $position = $i + 1;
            $weight = $this->criteriaWeights->getOrderQuantity();
            $points = $weight * ($iMax - $position);
            $menuToPoints[$menuEntity->getId()] = $points;
        }

        return $menuToPoints;
    }

    /**
     * @param OrderItemEntity[] $orderItemEntities
     * @param MenuEntity[] $menuEntities
     *
     * id, points
     * @return array<int,int>
     */
    private function _assignPointsToMenuItemsByPricePercentage(
        array $orderItemEntities, array $menuEntities,
    ): array
    {
        $menuPricePercentage = [];

        foreach ($orderItemEntities as $orderItem) {
            $orderTotalPrice = 0;
            $menuContribution = [];

            foreach ($orderItem->getOrderMenuItems() as $orderMenuItem) {
                $menuId = $orderMenuItem->getMenuItem()->getId();
                $menuItemPrice = $orderMenuItem->getMenuItem()->getPrice() * $orderMenuItem->getCount();
                $orderTotalPrice += $menuItemPrice;

                if (!isset($menuContribution[$menuId])) {
                    $menuContribution[$menuId] = 0;
                }
                $menuContribution[$menuId] += $menuItemPrice;
            }

            foreach ($menuContribution as $menuId => $contribution) {
                $percentage = $orderTotalPrice > 0 ? ($contribution / $orderTotalPrice) * 100 : 0;

                if (!isset($menuPricePercentage[$menuId])) {
                    $menuPricePercentage[$menuId] = [];
                }
                $menuPricePercentage[$menuId][] = $percentage;
            }
        }

        $averagePricePercentage = [];
        foreach ($menuPricePercentage as $menuId => $percentages) {
            $averagePricePercentage[$menuId] = array_sum($percentages) / count($percentages);
        }

        usort($menuEntities, function ($a, $b) use ($averagePricePercentage) {
            $idA = $a->getId();
            $idB = $b->getId();

            $percentageA = $averagePricePercentage[$idA] ?? 0;
            $percentageB = $averagePricePercentage[$idB] ?? 0;

            return $percentageB <=> $percentageA;
        });

        $menuToPoints = [];

        for ($i = 0, $iMax = count($menuEntities); $i < $iMax; $i++) {
            $menuEntity = $menuEntities[$i];
            $position = $i + 1;
            $weight = $this->criteriaWeights->getPricePercentage();
            $points = $weight * ($iMax - $position);
            $menuToPoints[$menuEntity->getId()] = $points;
        }

        return $menuToPoints;
    }
}