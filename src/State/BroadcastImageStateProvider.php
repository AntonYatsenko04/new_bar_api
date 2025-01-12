<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\BroadcastImageToDbEntityRepository;
use App\Repository\BroadcastImageToFileEntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BroadcastImageStateProvider implements ProviderInterface
{
    private BroadcastImageToFileEntityRepository $broadcastImageToFileEntityRepository;
    private BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository;

    /**
     * @param BroadcastImageToFileEntityRepository $broadcastImageToFileEntityRepository
     * @param BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository
     */
    public function __construct(BroadcastImageToFileEntityRepository $broadcastImageToFileEntityRepository, BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository)
    {
        $this->broadcastImageToFileEntityRepository = $broadcastImageToFileEntityRepository;
        $this->broadcastImageToDbEntityRepository = $broadcastImageToDbEntityRepository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        try {
            $fileImages = $this->broadcastImageToFileEntityRepository
                ->getAllImagesAsBase64();
            $dbImages = $this->broadcastImageToDbEntityRepository->getAllImagesAsBase64();

            return array_merge($fileImages, $dbImages);
        } catch (\Exception $exception) {
            throw HttpException::fromStatusCode(500, $exception->getMessage()
                , $exception);

        }
    }
}
