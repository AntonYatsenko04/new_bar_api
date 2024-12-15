<?php

namespace App\State\Menu;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Menu\MenuResource;
use App\Entity\Menu\MenuEntity;
use App\Mapper\MenuMapper;
use App\Repository\Menu\MenuEntityRepository;

class MenuGetCollectionStateProvider implements ProviderInterface
{
    private MenuEntityRepository $repository;

    /**
     * @param MenuEntityRepository $repository
     */
    public function __construct(MenuEntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entities = $this->repository->getAllMenuItems();
        $mapper = static function (MenuEntity $item) {
            return MenuMapper::resourceFromEntity($item);
        };

        return array_map($mapper, $entities);
    }
}
