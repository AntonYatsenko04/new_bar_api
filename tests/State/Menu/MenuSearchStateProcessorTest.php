<?php

namespace App\Tests\State\Menu;


use ApiPlatform\Metadata\Operation;
use App\ApiResource\Auth\UserDTO;
use App\ApiResource\Menu\MenuSearchDTO;
use App\Entity\CriteriaWeights;
use App\Entity\Menu\MenuEntity;
use App\Enum\UserRole;
use App\Repository\CriteriaWeightsRepository;
use App\Repository\Menu\MenuEntityRepository;
use App\Repository\Order\OrderEntityRepository;
use App\Service\MenuSearchService;
use App\State\Menu\MenuSearchStateProcessor;
use App\State\UserInfoStateProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MenuSearchStateProcessorTest extends TestCase
{
    private $menuEntityRepositoryMock;
    private $orderEntityRepositoryMock;
    private $menuSearchServiceMock;
    private $userInfoStateProcessorMock;
    private $criteriaWeightsRepositoryMock;
    private $processor;

    public function testProcessReturnsMenuEntities()
    {
        $menuItems = [new MenuEntity(), new MenuEntity()];
        $criteriaWeights = new CriteriaWeights();
        $criteriaWeights->setItemQuantity(1);
        $criteriaWeights->setOrderQuantity(1);
        $criteriaWeights->setPricePercentage(1);
        $userDto = new UserDTO(id: 1, email: 'aa@aa.aa', userType: UserRole::manager);

        $this->menuEntityRepositoryMock->method('getAllMenuItems')->willReturn($menuItems);
        $this->criteriaWeightsRepositoryMock->method('findAll')->willReturn([$criteriaWeights]);
        $this->userInfoStateProcessorMock->method('findUserByToken')->willReturn($userDto);
        $this->orderEntityRepositoryMock->method('findAll')->willReturn([]);
        $this->menuSearchServiceMock->expects($this->once())
            ->method('setData')
            ->with(
                menuItems: $menuItems,
                orderItems: [],
                criteriaWeights: $criteriaWeights,
                userRole: UserRole::manager,
                userId: 1,
                searchString: '',
            );
        $this->menuSearchServiceMock->method('search')->willReturn($menuItems);

        $data = new MenuSearchDTO('', '');

        $operation = $this->createMock(Operation::class);

        $result = $this->processor->process($data, $operation);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame($menuItems, $result);
    }


    public function testProcessThrowsUnauthorizedException()
    {
        $this->userInfoStateProcessorMock->method('findUserByToken')->willReturn(null);

        $data = new MenuSearchDTO('', '');

        $operation = $this->createMock(Operation::class);

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);

        $this->processor->process($data, $operation);
    }

    public function testProcessThrowsExceptionWhenCriteriaWeightsAreMissing()
    {
        $menuItems = [new MenuEntity()];
        $userDto = new UserDTO(id: 1, email: 'aa@aa.aa', userType: UserRole::manager);

        $this->menuEntityRepositoryMock->method('getAllMenuItems')->willReturn($menuItems);
        $this->criteriaWeightsRepositoryMock->method('findAll')->willReturn([]);
        $this->userInfoStateProcessorMock->method('findUserByToken')->willReturn($userDto);

        $data = new MenuSearchDTO('', '');

        $operation = $this->createMock(Operation::class);

        $this->expectException(HttpException::class);
        $this->processor->process($data, $operation);
    }

    protected function setUp(): void
    {
        // Mock dependencies
        $this->menuEntityRepositoryMock = $this->createMock(MenuEntityRepository::class);
        $this->orderEntityRepositoryMock = $this->createMock(OrderEntityRepository::class);
        $this->menuSearchServiceMock = $this->createMock(MenuSearchService::class);
        $this->userInfoStateProcessorMock = $this->createMock(UserInfoStateProcessor::class);
        $this->criteriaWeightsRepositoryMock = $this->createMock(CriteriaWeightsRepository::class);

        $this->processor = new MenuSearchStateProcessor(
            $this->menuEntityRepositoryMock,
            $this->orderEntityRepositoryMock,
            $this->menuSearchServiceMock,
            $this->userInfoStateProcessorMock,
            $this->criteriaWeightsRepositoryMock,
        );
    }
}
