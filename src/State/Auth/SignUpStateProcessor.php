<?php

namespace App\State\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\SignUpApiResource;
use App\Repository\UserEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpStateProcessor implements ProcessorInterface
{
    private UserEntityRepository $userEntityRepository;

    /**
     * @param UserEntityRepository $userEntityRepository
     */
    public function __construct(UserEntityRepository $userEntityRepository)
    {
        $this->userEntityRepository = $userEntityRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (get_class($data) === SignUpApiResource::class) {
            $result = $this->userEntityRepository->createUser(email: $data->getEmail(), password:
                $data->getPassword());

            if(!$result){
                throw HttpException::fromStatusCode(409);
            }
        }
    }
}
