<?php

namespace App\ApiResource\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\State\Auth\SignUpStateProcessor;
use App\State\UserInfoStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [new Post(
        uriTemplate: 'user_info',
        input: TokenDTO::class,
        output: UserDTO::class,
        processor: UserInfoStateProcessor::class
    )]
)]
class UserInfoResource {
    #[Assert\NotBlank]
    private ?string $token;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}