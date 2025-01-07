<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Repository\CriteriaWeightsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CriteriaWeightsRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Put()
    ]
)]
class CriteriaWeights
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $itemQuantity = null;

    #[ORM\Column]
    private ?int $orderQuantity = null;

    #[ORM\Column]
    private ?int $pricePercentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemQuantity(): ?int
    {
        return $this->itemQuantity;
    }

    public function setItemQuantity(int $itemQuantity): static
    {
        $this->itemQuantity = $itemQuantity;

        return $this;
    }

    public function getOrderQuantity(): ?int
    {
        return $this->orderQuantity;
    }

    public function setOrderQuantity(int $orderQuantity): static
    {
        $this->orderQuantity = $orderQuantity;

        return $this;
    }

    public function getPricePercentage(): ?int
    {
        return $this->pricePercentage;
    }

    public function setPricePercentage(int $pricePercentage): static
    {
        $this->pricePercentage = $pricePercentage;

        return $this;
    }
}
