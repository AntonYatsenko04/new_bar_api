<?php

namespace App\ApiResource\Auth;

use ApiPlatform\Metadata\Post;
use App\State\Auth\SignInStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;


#[Post(uriTemplate: 'sign_in', output: TokenDTO::class, processor: SignInStateProcessor::class)]
class SignInApiResource {

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $password;


    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}