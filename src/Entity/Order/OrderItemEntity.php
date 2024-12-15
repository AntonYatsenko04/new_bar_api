<?php

namespace App\Entity\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Entity\Users\CookEntity;
use App\Repository\Order\OrderItemEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderItemEntityRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        paginationEnabled: false,
        normalizationContext: ['groups' => ['orderItem:get', 'cook:get', 'orderMenuItem:get', 'menu:get']],
    ),
    new Patch(
        denormalizationContext: ['groups' => ['orderItem:get', 'cook:get', 'orderMenuItem:get', 'menu:get']],
    )
],
)]
class OrderItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, OrderMenuItemEntity>
     */
    #[ORM\OneToMany(targetEntity: OrderMenuItemEntity::class, mappedBy: 'orderItem')]
    private Collection $orderMenuItems;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?CookEntity $cook = null;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('taken', 'inCooking','pendingPayment','complete')")]
    private ?string $status;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderEntity $orderEntity = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->orderMenuItems = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    #[Groups('orderItem:get')]
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OrderMenuItemEntity>
     */
    #[Groups('orderItem:get')]
    public function getOrderMenuItems(): Collection
    {
        return $this->orderMenuItems;
    }

    public function addOrderMenuItem(OrderMenuItemEntity $orderMenuItem): static
    {
        if (!$this->orderMenuItems->contains($orderMenuItem)) {
            $this->orderMenuItems->add($orderMenuItem);
            $orderMenuItem->setOrderItem($this);
        }

        return $this;
    }

    public function removeOrderMenuItem(OrderMenuItemEntity $orderMenuItem): static
    {
        if ($this->orderMenuItems->removeElement($orderMenuItem)) {
            // set the owning side to null (unless already changed)
            if ($orderMenuItem->getOrderItem() === $this) {
                $orderMenuItem->setOrderItem(null);
            }
        }

        return $this;
    }

    #[Groups('orderItem:get')]
    public function getCook(): ?CookEntity
    {
        return $this->cook;
    }

    public function setCook(?CookEntity $cook): static
    {
        $this->cook = $cook;

        return $this;
    }

    #[Groups('orderItem:get')]
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): static
    {
        $this->orderEntity = $orderEntity;

        return $this;
    }

    #[Groups('orderItem:get')]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
