<?php

namespace App\Repository;

use App\ApiResource\Broadcast\BroadcastImageResource;
use App\Entity\BroadcastImageToFileEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BroadcastImageToFileEntity>
 */
class BroadcastImageToFileEntityRepository extends ServiceEntityRepository
{
    private const IMAGE_DIRECTORY = '/Users/user/PhpstormProjects/bar_api/images';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BroadcastImageToFileEntity::class);
    }

    public function saveBase64ImageToFile(int $broadcastId, string $base64Image): void
    {
        $entityManager = $this->getEntityManager();

        $decodedImage = base64_decode($base64Image);
        $filename = uniqid('image_', true) . '.jpeg';
        $filePath = self::IMAGE_DIRECTORY . '/' . $filename;

        if (!file_put_contents($filePath, $decodedImage)) {
            throw new \RuntimeException('Failed to save image to file');
        }

        $this->removeExistingImages($broadcastId);

        $entity = new BroadcastImageToFileEntity();
        $entity->setBroadcastId($broadcastId);
        $entity->setFilePath($filePath);

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public function removeExistingImages(int $broadcastId): void
    {
        $entityManager = $this->getEntityManager();

        $existingFileEntity = $this->findOneBy(['broadcastId' => $broadcastId]);
        if ($existingFileEntity) {
            $this->deleteFileAndEntity($existingFileEntity);
        }


        $entityManager->flush();
    }

    private function deleteFileAndEntity(BroadcastImageToFileEntity $entity): void
    {
        $filePath = $entity->getFilePath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->getEntityManager()->remove($entity);
    }


    public function getAllImagesAsBase64(): array
    {
        $images = $this->findAll();
        $result = [];

        foreach ($images as $image) {
            $filePath = $image->getFilePath();
            if (!file_exists($filePath)) {
                $this->getEntityManager()->remove($image);
                $this->getEntityManager()->flush();
                continue;
            }

            $result[] =
                new BroadcastImageResource(broadcastId: $image->getBroadcastId(),
                    image: base64_encode(file_get_contents($filePath)));
        }

        return $result;
    }
}
