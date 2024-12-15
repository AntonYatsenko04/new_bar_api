<?php

namespace App\State\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\SignInApiResource;
use App\ApiResource\Auth\TokenDTO;
use App\Repository\User\CookEntityRepository;
use App\Repository\User\ManagerEntityRepository;
use App\Repository\User\WaiterEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignInStateProcessor implements ProcessorInterface
{
    private ManagerEntityRepository $managerEntityRepository;
    private WaiterEntityRepository $waiterEntityRepository;
    private CookEntityRepository $cookEntityRepository;

    /**
     * @param ManagerEntityRepository $managerEntityRepository
     * @param WaiterEntityRepository $waiterEntityRepository
     * @param CookEntityRepository $cookEntityRepository
     */
    public function __construct(ManagerEntityRepository $managerEntityRepository, WaiterEntityRepository $waiterEntityRepository, CookEntityRepository $cookEntityRepository)
    {
        $this->managerEntityRepository = $managerEntityRepository;
        $this->waiterEntityRepository = $waiterEntityRepository;
        $this->cookEntityRepository = $cookEntityRepository;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?TokenDTO
    {
        if (get_class($data) === SignInApiResource::class) {
            $repositories = array(
                $this->managerEntityRepository,
                $this->waiterEntityRepository,
                $this->cookEntityRepository,
            );

            foreach ($repositories as $repository) {
                $token = $repository->getTokenByEmailAndPassword
                ($data->getEmail(),$data->getPassword());
                if(isset($token)){
                    return new TokenDTO(token: $token);
                }
            }

            throw HttpException::fromStatusCode(401);
        }

    }
}
