<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\RoleSignUpApiResource;
use App\Enum\UserRole;
use App\Repository\User\CookEntityRepository;
use App\Repository\User\ManagerEntityRepository;
use App\Repository\User\WaiterEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleSignUpStateProcessor implements ProcessorInterface
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

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (get_class($data) === RoleSignUpApiResource::class) {
            $email = $data->getEmail();

            $emailExists = array(
                $this->managerEntityRepository->emailExists($email),
                $this->waiterEntityRepository->emailExists($email),
                $this->cookEntityRepository->emailExists($email),
            );

            foreach ($emailExists as $val){
                if($val){
                    throw HttpException::fromStatusCode(409);
                }
            }

            $role = UserRole::from($data->getRole());
            switch ($role) {
                case UserRole::manager:
                    $this->managerEntityRepository->createUser(email: $data->getEmail(), password: $data->getPassword());
                    break;
                case UserRole::waiter:
                    $this->waiterEntityRepository->createUser(email: $data->getEmail(), password:
                        $data->getPassword());
                    break;
                case UserRole::cook:
                    $this->cookEntityRepository->createUser(email: $data->getEmail(), password:
                        $data->getPassword());

            }
        }

    }
}
