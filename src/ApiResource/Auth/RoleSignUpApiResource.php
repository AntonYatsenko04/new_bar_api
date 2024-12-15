<?php

namespace App\ApiResource\Auth;

namespace App\ApiResource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Common\EmptyDTO;
use App\State\RoleSignUpStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [new Post(
        uriTemplate: 'sign_up_with_role',
        output: EmptyDTO::class,
        processor: RoleSignUpStateProcessor::class,
    )]
)]
class RoleSignUpApiResource
{

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $password;

    #[Assert\NotBlank]
    private ?string $role;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }


}