<?php

namespace App\State\Menu;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\Menu\MenuEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MenuDeleteStateProcessor implements ProcessorInterface
{
    private MenuEntityRepository $repository;

    /**
     * @param MenuEntityRepository $repository
     */
    public function __construct(MenuEntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        try {
            $this->repository->deleteMenuItem($data['id']);
        }catch (\Exception $exception){

        }
    }
}
