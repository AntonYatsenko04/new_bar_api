<?php

namespace App\ApiResource\Order;

class SetStatusDTO {
    private int $orderItemId;
    private string $status;
    private ?int $cookId;

    public function __construct(int $orderItemId, string $status) {

        $this->orderItemId = $orderItemId;
        $this->status = $status;
    }

    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCookId(): ?int
    {
        return $this->cookId;
    }

    public function setCookId(?int $cookId): void
    {
        $this->cookId = $cookId;
    }


}