<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Broadcast\BroadcastImageResource;
use App\Repository\BroadcastImageToDbEntityRepository;
use App\Repository\BroadcastImageToFileEntityRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BroadcastImageToDbStateProcessor implements ProcessorInterface
{
    private BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository;
    private BroadcastImageToFileEntityRepository
        $broadcastImageToFileEntityRepository;

    /**
     * @param BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository
     * @param BroadcastImageToFileEntityRepository $broadcastImageToFileEntityRepository
     */
    public function __construct(BroadcastImageToDbEntityRepository $broadcastImageToDbEntityRepository, BroadcastImageToFileEntityRepository $broadcastImageToFileEntityRepository)
    {
        $this->broadcastImageToDbEntityRepository = $broadcastImageToDbEntityRepository;
        $this->broadcastImageToFileEntityRepository = $broadcastImageToFileEntityRepository;
    }


    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof BroadcastImageResource) {
            try {
                $this->broadcastImageToDbEntityRepository->saveBase64Image
                (broadcastId: $data->getId(), base64Image: $data->getImage());
                $this->broadcastImageToFileEntityRepository->removeExistingImages($data->getId());
            } catch (Exception $exception) {
                throw HttpException::fromStatusCode(500);
            }

        }
    }
}
