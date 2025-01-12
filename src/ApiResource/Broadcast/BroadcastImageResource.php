<?php

namespace App\ApiResource\Broadcast;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\State\BroadcastImageToDbStateProcessor;
use App\State\BroadcastImageToFileStateProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'broadcast_image_to_db',
            processor: BroadcastImageToDbStateProcessor::class,
        ),
        new Post(
            uriTemplate: 'broadcast_image_to_file',
            processor: BroadcastImageToFileStateProcessor::class,
        ),
        new GetCollection(
            paginationEnabled: false,
        )
    ]
)]
class BroadcastImageResource
{
    private int $id;

    private string $image;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}