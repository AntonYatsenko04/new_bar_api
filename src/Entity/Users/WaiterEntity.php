<?php

namespace App\Entity\Users;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Order\OrderEntity;
use App\Repository\User\WaiterEntityRepository;
use App\Service\RandomStringUtility;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(operations:[new GetCollection(
    paginationEnabled: false,
    normalizationContext: ['groups' => ['waiter:get']]
)])]
#[ORM\Entity(repositoryClass: WaiterEntityRepository::class)]
class WaiterEntity implements IUserEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Unique]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\Unique]
    private ?string $token = null;

    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
        $this->orderEntities = new ArrayCollection();
        if ($this->token === null || $this->token === "") {
            $this->token = RandomStringUtility::generateToken();
        }
    }

    /**
     * @var Collection<int, OrderEntity>
     */
    #[ORM\OneToMany(targetEntity: OrderEntity::class, mappedBy: 'waiter')]
    private Collection $orderEntities;

    #[Groups(['waiter:get'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['waiter:get'])]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[Groups(['waiter:get'])]
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, OrderEntity>
     */

    public function getOrderEntities(): Collection
    {
        return $this->orderEntities;
    }

    public function addOrderEntity(OrderEntity $orderEntity): static
    {
        if (!$this->orderEntities->contains($orderEntity)) {
            $this->orderEntities->add($orderEntity);
            $orderEntity->setWaiter($this);
        }

        return $this;
    }

    public function removeOrderEntity(OrderEntity $orderEntity): static
    {
        if ($this->orderEntities->removeElement($orderEntity)) {
            // set the owning side to null (unless already changed)
            if ($orderEntity->getWaiter() === $this) {
                $orderEntity->setWaiter(null);
            }
        }

        return $this;
    }

//    #[Groups(['waiter:get'])]
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
