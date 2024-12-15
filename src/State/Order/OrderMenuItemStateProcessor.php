<?php

namespace App\State\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Common\ArrayDTO;
use App\ApiResource\Common\IdDTO;
use App\ApiResource\Order\OrderMenuItemDTO;
use App\Entity\Order\OrderMenuItemEntity;
use App\Repository\Order\OrderMenuItemEntityRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderMenuItemStateProcessor implements ProcessorInterface
{
    private OrderMenuItemEntityRepository $orderMenuItemEntityRepository;

    /**
     * @param OrderMenuItemEntityRepository $orderMenuItemEntityRepository
     */
    public function __construct(OrderMenuItemEntityRepository $orderMenuItemEntityRepository)
    {
        $this->orderMenuItemEntityRepository = $orderMenuItemEntityRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (get_class($data) === ArrayDTO::class) {
            try {
                $items = $data->getItems();
                foreach ($items as $orderMenuItem) {
                    $this->orderMenuItemEntityRepository
                        ->createOrderMenuItemEntity(OrderMenuItemDTO::fromArray($orderMenuItem));
                }
            } catch (Exception $e) {
                throw new HttpException(404, $e->getMessage());
            }
        }else throw new HttpException(404, "Order menu item not found");
    }
}
