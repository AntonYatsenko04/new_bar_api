<?php

namespace App\Repository\User;

use App\Entity\Users\IUserEntity;
use App\Entity\Users\WaiterEntity;

interface UserRepository {
    public function findByToken(string $token): ?IUserEntity;

    public function createUser(string $email, string $password): bool;

    public function emailExists(string $email): bool;

    public function getTokenByEmailAndPassword(string $email, string
    $password): ?string;

}