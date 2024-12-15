<?php

namespace App\Entity\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Table\TableEntity;
use App\Entity\Users\WaiterEntity;
use App\Repository\Order\OrderEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\AST\DeleteClause;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderEntityRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'order',
            paginationEnabled: false,
            normalizationContext: ['groups' =>
                ['order:get', 'waiter:get', 'table:get', 'orderItem:get', 'cook:get', 'orderMenuItem:get', 'menu:get']]
        ),
        new Delete()
    ],

)]
class OrderEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('order:get')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TableEntity $tableEntity = null;

    #[Groups('order:get')]
    #[ORM\ManyToOne(inversedBy: 'orderEntities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WaiterEntity $waiter = null;

    /**
     * @var Collection<int, OrderItemEntity>
     */
    #[Groups('order:get')]
    #[ORM\OneToMany(targetEntity: OrderItemEntity::class, mappedBy: 'orderEntity')]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    #[Groups('order:get')]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups('order:get')]
    public function getTableEntity(): ?TableEntity
    {
        return $this->tableEntity;
    }


    public function setTableEntity(?TableEntity $tableEntity): static
    {
        $this->tableEntity = $tableEntity;

        return $this;
    }

    #[Groups(['order:get'])]
    public function getWaiter(): ?WaiterEntity
    {
        return $this->waiter;
    }

    public function setWaiter(?WaiterEntity $waiter): static
    {
        $this->waiter = $waiter;

        return $this;
    }

    /**
     * @return Collection<int, OrderItemEntity>
     */
    #[Groups('order:get')]
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItemEntity $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrderEntity($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItemEntity $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderEntity() === $this) {
                $orderItem->setOrderEntity(null);
            }
        }

        return $this;
    }
}
