<?php

namespace App\Repository\Menu;

use App\Entity\Menu\MenuEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @extends ServiceEntityRepository<MenuEntity>
 */
class MenuEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuEntity::class);
    }

    public function getAllMenuItems(): array
    {
        return $this->createQueryBuilder('m')
            ->select()
            ->getQuery()
            ->getResult();
    }

    public function deleteMenuItem(int $id): void
    {
        try{
            $entityManager = $this->getEntityManager();
            $menuItem = $entityManager->find(MenuEntity::class, $id);

            if (isset($menuItem)) {
                $entityManager->remove($menuItem);
                $entityManager->flush();
            }else{
                throw HttpException::fromStatusCode(400);
            }
        }catch (\Exception $e){
            throw HttpException::fromStatusCode(400);
        }

    }

    public function createMenuItem(MenuEntity $menuEntity): void{
        $entityManager = $this->getEntityManager();
        $entityManager->persist($menuEntity);
        $entityManager->flush();
    }

    public function updateMenuItem(MenuEntity $menuEntity): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->refresh($menuEntity);
    }
}
