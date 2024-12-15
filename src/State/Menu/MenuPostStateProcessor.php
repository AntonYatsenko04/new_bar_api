<?php

namespace App\State\Menu;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\SignInApiResource;
use App\ApiResource\Auth\TokenDTO;
use App\ApiResource\Menu\MenuResource;
use App\Mapper\MenuMapper;
use App\Repository\Menu\MenuEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MenuPostStateProcessor implements ProcessorInterface
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
        if (get_class($data) === MenuResource::class) {
            $this->repository->createMenuItem(MenuMapper::entityFromResource($data));
        }
    }
}
