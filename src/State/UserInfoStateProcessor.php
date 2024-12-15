<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Auth\TokenDTO;
use App\ApiResource\Auth\UserDTO;
use App\Enum\UserRole;
use App\Mapper\UserMapper;
use App\Repository\User\CookEntityRepository;
use App\Repository\User\ManagerEntityRepository;
use App\Repository\User\WaiterEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserInfoStateProcessor implements ProcessorInterface
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

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserDTO
    {
        if (get_class($data) === TokenDTO::class) {
            $userDto = $this->findUserByToken($data->getToken());

            if (isset($userDto)) {
                return $userDto;
            }

            throw HttpException::fromStatusCode(401);
        }
    }

    public function findUserByToken(string $token): ?UserDTO
    {
        $repositories = array(
            UserRole::manager->value => $this->managerEntityRepository,
            UserRole::cook->value => $this->cookEntityRepository,
            UserRole::waiter->value => $this->waiterEntityRepository,
        );

        foreach ($repositories as $role => $repository) {
            $user = $repository->findByToken
            ($token);
            if (isset($user)) {
                return UserMapper::dtoFromEntity($user, UserRole::from($role));
            }
        }
    }
}
