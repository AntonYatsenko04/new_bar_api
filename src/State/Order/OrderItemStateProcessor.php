<?php

namespace App\State\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Common\IdDTO;
use App\ApiResource\Order\OrderItemDTO;
use App\Repository\Order\OrderItemEntityRepository;

class OrderItemStateProcessor implements ProcessorInterface
{
    private OrderItemEntityRepository $orderItemEntityRepository;

    /**
     * @param OrderItemEntityRepository $orderItemEntityRepository
     */
    public function __construct(OrderItemEntityRepository $orderItemEntityRepository)
    {
        $this->orderItemEntityRepository = $orderItemEntityRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): IdDTO
    {
        if(get_class($data) === OrderItemDTO::class){
            $id = $this->orderItemEntityRepository->createOrderItem($data);
            return new IdDTO($id);
        }
    }
}
