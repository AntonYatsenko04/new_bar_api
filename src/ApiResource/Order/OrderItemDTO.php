<?php

namespace App\ApiResource\Order;

class OrderItemDTO
{
    private int $orderId;
    private string $status;
    private ?int $cookId;

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCookId(): ?int
    {
        return $this->cookId;
    }

    /**
     * @param int $orderId
     * @param string $status
     * @param int|null $cookId
     */
    public function __construct(int $orderId, string $status, ?int $cookId)
    {
        $this->orderId = $orderId;
        $this->status = $status;
        $this->cookId = $cookId;
    }
}