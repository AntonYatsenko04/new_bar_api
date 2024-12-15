<?php

namespace App\Entity\Menu;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Menu\MenuSearchDTO;
use App\Repository\Menu\MenuEntityRepository;
use App\State\Menu\MenuSearchStateProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    uriTemplate: 'menu',
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['menu:get']],
        ),
        new Post(
            uriTemplate: 'menu_search',
            normalizationContext: ['groups' => ['menu:get']],
            input: MenuSearchDTO::class,
            output: array(),
            processor: MenuSearchStateProcessor::class,
        ),
        new Post(),
        new Delete(uriTemplate: 'menu/{id}', uriVariables: [
            'id' => new Link(fromClass: MenuEntity::class)
        ]),
        new Patch(uriTemplate: 'menu/{id}', uriVariables: [
            'id' => new Link(fromClass: MenuEntity::class)
        ]),
    ],
    paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: MenuEntityRepository::class)]
class MenuEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[Groups('menu:get')]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    #[Groups('menu:get')]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    #[Groups('menu:get')]
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[Groups('menu:get')]
    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

}
