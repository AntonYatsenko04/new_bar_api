<?php

namespace App\Entity;

use App\Repository\BroadcastImageToDbEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcastImageToDbEntityRepository::class)]
class BroadcastImageToDbEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $broadcastId = null;

    #[ORM\Column(type: Types::BLOB)]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBroadcastId(): ?int
    {
        return $this->broadcastId;
    }

    public function setBroadcastId(int $broadcastId): static
    {
        $this->broadcastId = $broadcastId;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): static
    {
        $this->image = $image;

        return $this;
    }
}
