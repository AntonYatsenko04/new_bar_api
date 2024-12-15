<?php

namespace App\ApiResource\Auth;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\Auth\SignUpStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [new Post(
        uriTemplate: 'sign_up',
        processor: SignUpStateProcessor::class
    )]
)]
class SignUpApiResource {

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $password;

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


}