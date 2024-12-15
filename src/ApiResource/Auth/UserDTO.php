<?php

namespace App\ApiResource\Auth;

use App\Enum\UserRole;

class UserDTO {
    private int $id;
    private String $email;
    private String $userType;

    /**
     * @param int $id
     * @param String $email
     * @param UserRole $userType
     */
    public function __construct(int $id, string $email, UserRole $userType)
    {
        $this->id = $id;
        $this->email = $email;
        $this->userType = $userType->value;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function getUserType(): string
    {
        return $this->userType;
    }

}