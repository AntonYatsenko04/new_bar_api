<?php

namespace App\State\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Auth\UserDTO;
use App\Repository\User\CookEntityRepository;
use App\Repository\User\ManagerEntityRepository;
use App\Repository\User\WaiterEntityRepository;

class UserStateProvider implements ProviderInterface
{
    private ManagerEntityRepository $managerEntityRepository;
    private WaiterEntityRepository $waiterEntityRepository;
    private CookEntityRepository  $cookEntityRepository;

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

    public function provide(Operation $operation, array $uriVariables = [], array $context = []):
    UserDTO
    {
        $manager = $this->managerEntityRepository->findByToken($context['token']);
        $cook = $this->cookEntityRepository->findByToken($context['token']);
        $waiter = $this->waiterEntityRepository->findByToken($context['token']);


    }
}
