<?php

namespace App\ApiResource\Order;

class OrderDTO
{
    private int $waiterId;
    private int $tableId;

    /**
     * @param int $waiterId
     * @param int $tableId
     */
    public function __construct(int $waiterId, int $tableId)
    {
        $this->waiterId = $waiterId;
        $this->tableId = $tableId;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function getWaiterId(): int
    {
        return $this->waiterId;
    }
}