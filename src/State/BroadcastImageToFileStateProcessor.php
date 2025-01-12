<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Broadcast\BroadcastImageResource;
use App\Repository\BroadcastImageToDbEntityRepository;
use App\Repository\BroadcastImageToFileEntityRepository;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BroadcastImageToFileStateProcessor implements ProcessorInterface
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


    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof BroadcastImageResource) {
            try {
                $this->broadcastImageToFileEntityRepository->saveBase64ImageToFile
                (broadcastId: $data->getId(), base64Image: $data->getImage());
                $this->broadcastImageToDbEntityRepository
                    ->removeExistingImages(broadcastId: $data->getId());
            } catch (Exception $exception) {
                throw HttpException::fromStatusCode(500);
            }
        }
    }
}
