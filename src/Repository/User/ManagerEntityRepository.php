<?php

namespace App\Repository\User;

use App\Entity\Users\ManagerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ManagerEntity>
 */
class ManagerEntityRepository extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagerEntity::class);
    }


    public function findByToken(string $token): ?ManagerEntity
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.token = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createUser(string $email, string $password): bool
    {
        if (!$this->emailExists($email)) {
            $user = new ManagerEntity(email: $email, password: $password);

            $entityManager = $this->getEntityManager();
            $entityManager->createQueryBuilder();
            $entityManager->persist($user);
            $entityManager->flush();
            return true;
        }

        return false;
    }

    public function emailExists(string $email): bool
    {
        $result = $this->createQueryBuilder('m')
            ->andWhere('m.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult();

        return isset($result);
    }

    public function getTokenByEmailAndPassword(string $email, string $password): ?string
    {
        $entity =  $this
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.email = :email')
            ->andWhere('u.password = :password')
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->getQuery()
            ->getOneOrNullResult();

        return $entity?->getToken();
    }
}
