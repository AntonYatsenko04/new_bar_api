<?php

namespace App\Repository;

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
            $result[] = [
                'id' => $image->getId(),
                'broadcastId' => $image->getBroadcastId(),
                'image' => base64_encode($image->getImage()),
            ];
        }

        return $result;
    }

}
