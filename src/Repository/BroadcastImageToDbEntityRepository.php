<?php

namespace App\Repository;

use App\ApiResource\Broadcast\BroadcastImageResource;
use App\Entity\BroadcastImageToDbEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BroadcastImageToDbEntity>
 */
class BroadcastImageToDbEntityRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BroadcastImageToDbEntity::class);
    }

    public function saveBase64Image(int $broadcastId, string $base64Image): void
    {
        $entityManager = $this->getEntityManager();

        $entity = new BroadcastImageToDbEntity();
        $entity->setBroadcastId($broadcastId);
        $entity->setImage(base64_decode($base64Image));

        $this->removeExistingImages($broadcastId);

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public function removeExistingImages(int $broadcastId): void
    {
        $entityManager = $this->getEntityManager();

        $existingDbEntity = $this->findOneBy(['broadcastId' => $broadcastId]);
        if ($existingDbEntity) {
            $entityManager->remove($existingDbEntity);
        }

        $entityManager->flush();
    }

    public function getAllImagesAsBase64(): array
    {
        $images = $this->findAll();
        $result = [];

        foreach ($images as $image) {
            $imageResource = $image->getImage();
            if (is_resource($imageResource)) {
                $imageResource = stream_get_contents($imageResource);
            }

            $result[] =
                new BroadcastImageResource(broadcastId: $image->getBroadcastId(),
                    image: base64_encode(
                        $imageResource),
                );
        }

        return $result;
    }

}
