<?php

namespace App\ApiResource\Broadcast;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\State\BroadcastImageStateProvider;
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
            provider: BroadcastImageStateProvider::class,
        )
    ]
)]
class BroadcastImageResource
{
    private int $broadcastId;

    private string $image;

    /**
     * @param int $broadcastId
     * @param string $image
     */
    public function __construct(int $broadcastId, string $image)
    {
        $this->broadcastId = $broadcastId;
        $this->image = $image;
    }

    public function getBroadcastId(): int
    {
        return $this->broadcastId;
    }

    public function setBroadcastId(int $broadcastId): void
    {
        $this->broadcastId = $broadcastId;
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