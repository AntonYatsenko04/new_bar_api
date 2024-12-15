<?php

namespace App\Mapper;


use App\ApiResource\Auth\UserDTO;
use App\Entity\Users\IUserEntity;
use App\Enum\UserRole;

class UserMapper
{
    public static function dtoFromEntity(IUserEntity $entity, UserRole $role):
    UserDTO
    {
        return new UserDTO(id: $entity->getId(), email: $entity->getEmail(),
            userType: $role);
    }
}