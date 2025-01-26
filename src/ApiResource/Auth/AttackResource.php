<?php

namespace App\ApiResource\Auth;


use ApiPlatform\Metadata\Post;
use App\State\AttackProcessor;
use Symfony\Component\Validator\Constraints as Assert;


#[Post(uriTemplate: 'attack', output: TokenDTO::class, processor: AttackProcessor::class)]
class AttackResource
{

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $password;

    private ?string $csrfToken;

    public function getCsrfToken(): ?string
    {
        return $this->csrfToken;
    }

    public function setCsrfToken(?string $CsrfToken): void
    {
        $this->csrfToken = $CsrfToken;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}