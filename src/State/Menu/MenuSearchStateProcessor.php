<?php

namespace App\State\Menu;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Menu\MenuSearchDTO;
use App\Entity\Menu\MenuEntity;
use App\Enum\UserRole;
use App\Repository\CriteriaWeightsRepository;
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
    private CriteriaWeightsRepository $criteriaWeightsRepository;

    /**
     * @param MenuEntityRepository $menuEntityRepository
     * @param OrderEntityRepository $orderEntityRepository
     * @param MenuSearchService $menuSearchService
     * @param UserInfoStateProcessor $userInfoStateProcessor
     * @param CriteriaWeightsRepository $criteriaWeightsRepository
     */
    public function __construct(MenuEntityRepository  $menuEntityRepository,
                                OrderEntityRepository $orderEntityRepository, MenuSearchService $menuSearchService, UserInfoStateProcessor $userInfoStateProcessor, CriteriaWeightsRepository $criteriaWeightsRepository)
    {
        $this->menuEntityRepository = $menuEntityRepository;
        $this->orderEntityRepository = $orderEntityRepository;
        $this->menuSearchService = $menuSearchService;
        $this->userInfoStateProcessor = $userInfoStateProcessor;
        $this->criteriaWeightsRepository = $criteriaWeightsRepository;
    }


    /**
     * @return MenuEntity[]
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): array
    {
        if (get_class($data) === MenuSearchDTO::class) {
            $menuItems = $this->menuEntityRepository->getAllMenuItems();
            $userDto = $this->userInfoStateProcessor->findUserByToken
            ($data->getToken());
            $criteriaWeights = $this->criteriaWeightsRepository->findAll()[0];

            if (!isset($userDto)) {
                throw HttpException::fromStatusCode(401);
            }

            $this->menuSearchService->setData(menuItems: $menuItems,
                orderItems: $this->orderEntityRepository->findAll(),
                criteriaWeights: $criteriaWeights, userRole: UserRole::from
                ($userDto->getUserType()), userId: $userDto->getId(), searchString: $data->getSearchRequest());

            return ($this->menuSearchService->search());
        }

    }
}
