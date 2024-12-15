<?php

namespace App\State\Menu;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Menu\MenuSearchDTO;
use App\Entity\Menu\MenuEntity;
use App\Enum\UserRole;
use App\Repository\Menu\MenuEntityRepository;
use App\Repository\Order\OrderEntityRepository;
use App\Service\MenuSearchService;
use App\State\UserInfoStateProcessor;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MenuSearchStateProcessor implements ProcessorInterface
{
    private MenuEntityRepository $menuEntityRepository;
    private OrderEntityRepository $orderEntityRepository;
    private MenuSearchService $menuSearchService;
    private UserInfoStateProcessor $userInfoStateProcessor;

    /**
     * @param MenuEntityRepository $menuEntityRepository
     * @param OrderEntityRepository $orderEntityRepository
     * @param MenuSearchService $menuSearchService
     * @param UserInfoStateProcessor $userInfoStateProcessor
     */
    public function __construct(MenuEntityRepository $menuEntityRepository, OrderEntityRepository $orderEntityRepository, MenuSearchService $menuSearchService, UserInfoStateProcessor $userInfoStateProcessor)
    {
        $this->menuEntityRepository = $menuEntityRepository;
        $this->orderEntityRepository = $orderEntityRepository;
        $this->menuSearchService = $menuSearchService;
        $this->userInfoStateProcessor = $userInfoStateProcessor;
    }


    /**
     * @return MenuEntity[]
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if (get_class($data) === MenuSearchDTO::class) {
            $menuItems = $this->menuEntityRepository->getAllMenuItems();
            $filteredByNameMenuItems =
                $this->menuSearchService->filterMenuItemsByName
                ($menuItems, $data->getSearchRequest());

            $userDto = $this->userInfoStateProcessor->findUserByToken
            ($data->getToken());

            if (!isset($userDto)) {
                throw HttpException::fromStatusCode(401);
            }

            $roleFilteredOrderItems = $this->menuSearchService
                ->filterOrderItemsByUserRole
                (orderEntities: $this->orderEntityRepository->findAll(),
                    userRole: UserRole::from($userDto->getUserType()),
                    userId: $userDto->getId());

            $result =
                ($this->menuSearchService->filterMenuItemsByOrderMenuQuantity
                ($filteredByNameMenuItems, $roleFilteredOrderItems));

            return $result;
        }

    }
}
