<?php

namespace App\Repository;

use App\Entity\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
class UserEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function returnTokenByEmailAndPassword(string $email, string $password): ?string
    {
        return $this->_getUserByEmailAndPassword($email, $password)?->getToken();

    }

    private function _getUserByEmailAndPassword(string $email, string $password): ?UserEntity
    {
        return $this
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.email = :email')
            ->andWhere('u.password = :password')
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function _emailExistsInDB(string $email): bool{
        $result = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)->getQuery()->getResult();

        return !empty($result);

    }
    public function createUser(string $email, string $password): bool
    {
        if (!($this->_emailExistsInDB($email))) {
            $user = new UserEntity(email: $email, password: $password);

            $entityManager = $this->getEntityManager();
            $entityManager->createQueryBuilder();
            $entityManager->persist($user);
            $entityManager->flush();
            return true;
        }

        return false;

    }
}
