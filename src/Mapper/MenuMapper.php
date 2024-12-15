<?php

namespace App\Mapper;

use App\ApiResource\Menu\MenuResource;
use App\Entity\Menu\MenuEntity;

class MenuMapper
{
    public static function entityFromResource(MenuResource $resource): MenuEntity
    {
        $entity = new MenuEntity();
        $entity->setId($resource->getId());
        $entity->setName($resource->getName());
        $entity->setDescription($resource->getDescription());
        $entity->setPrice($resource->getPrice());
        return $entity;
    }

    public static function resourceFromEntity(MenuEntity $entity): MenuResource
    {
        $resource = new MenuResource();
        $resource->setId($entity->getId());
        $resource->setName($entity->getName());
        $resource->setDescription($entity->getDescription());
        $resource->setPrice($entity->getPrice());
        return $resource;
    }
}