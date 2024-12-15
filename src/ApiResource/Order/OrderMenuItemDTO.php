<?php

namespace App\ApiResource\Order;

class OrderMenuItemDTO
{
    private int $menuItemId;
    private int $orderItemId;
    private int $count;

    public function __construct(int $menuItemId, int $orderItemId, int $count){
        $this->menuItemId = $menuItemId;
        $this->orderItemId = $orderItemId;
        $this->count = $count;
    }

    public function getMenuItemId(): int
    {
        return $this->menuItemId;
    }

    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public static function fromArray(array $data): OrderMenuItemDTO
    {
        return new OrderMenuItemDTO(
            $data['menuItemId'],
            $data['orderItemId'],
            $data['count']
        );
    }
}