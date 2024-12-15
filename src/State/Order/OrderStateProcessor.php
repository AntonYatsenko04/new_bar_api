<?php

namespace App\State\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Common\IdDTO;
use App\ApiResource\Order\OrderDTO;
use App\Entity\Order\OrderEntity;
use App\Repository\Order\OrderEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderStateProcessor implements ProcessorInterface
{
    private OrderEntityRepository $orderEntityRepository;

    /**
     * @param OrderEntityRepository $orderEntityRepository
     */
    public function __construct(OrderEntityRepository $orderEntityRepository)
    {
        $this->orderEntityRepository = $orderEntityRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): IdDTO
    {
        if(get_class($data)==OrderDTO::class){
            try{
                $id = $this->orderEntityRepository->createOrderEntity($data);
                return new IdDTO($id);
            } catch (\Exception $exception){
                throw HttpException::fromStatusCode(404);
            }

        }
    }
}
