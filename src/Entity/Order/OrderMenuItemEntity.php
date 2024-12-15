<?php

namespace App\Entity\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Menu\MenuEntity;
use App\Repository\Order\OrderMenuItemEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderMenuItemEntityRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        paginationEnabled: false,
        normalizationContext: ['groups' => ['orderMenuItem:get']]
    ),
],
)]

class OrderMenuItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, MenuEntity>
     */

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MenuEntity $menuItem = null;

    #[ORM\ManyToOne(inversedBy: 'orderMenuItems')]
    private ?OrderItemEntity $orderItem = null;

    public function __construct()
    {
    }

    #[Groups('orderMenuItem:get')]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups('orderMenuItem:get')]
    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    #[Groups('orderMenuItem:get')]
    public function getMenuItem(): ?MenuEntity
    {
        return $this->menuItem;
    }

    public function setMenuItem(?MenuEntity $menuItem): static
    {
        $this->menuItem = $menuItem;

        return $this;
    }

    public function getOrderItem(): ?OrderItemEntity
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItemEntity $orderItem): static
    {
        $this->orderItem = $orderItem;

        return $this;
    }
}
