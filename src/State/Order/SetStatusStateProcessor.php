<?php

namespace App\State\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Common\IdDTO;
use App\ApiResource\Order\OrderItemDTO;
use App\ApiResource\Order\SetStatusDTO;
use App\Repository\Order\OrderItemEntityRepository;

class SetStatusStateProcessor implements ProcessorInterface
{
    private  OrderItemEntityRepository $orderItemEntityRepository;

    /**
     * @param OrderItemEntityRepository $orderItemEntityRepository
     */
    public function __construct(OrderItemEntityRepository $orderItemEntityRepository)
    {
        $this->orderItemEntityRepository = $orderItemEntityRepository;
    }


    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if(get_class($data) === SetStatusDTO::class){
            $this->orderItemEntityRepository->updateOrderItem($data);
        }
    }
}
