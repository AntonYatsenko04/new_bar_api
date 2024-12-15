<?php

namespace App\Entity\Table;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Common\EmptyDTO;
use App\Repository\Table\TableEntityRepository;
use App\State\RoleSignUpStateProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [new GetCollection(
        uriTemplate: 'table',
        paginationEnabled: false,
        normalizationContext: ['groups' => ['table:get']]
    )]
)]
#[ORM\Entity(repositoryClass: TableEntityRepository::class)]
class TableEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['table:get'])]
    public function getId(): ?int
    {
        return $this->id;
    }
}
