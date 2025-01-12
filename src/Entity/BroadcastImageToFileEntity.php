<?php

namespace App\Entity;

use App\Repository\BroadcastImageToFileEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcastImageToFileEntityRepository::class)]
class BroadcastImageToFileEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $broadcastId = null;

    #[ORM\Column(length: 2048)]
    private ?string $filePath = null;

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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }
}
