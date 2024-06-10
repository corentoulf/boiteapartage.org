<?php

namespace App\Repository;

use App\Entity\UserCircle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserCircle>
 */
class UserCircleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCircle::class);
    }

        /**
        * @return UserCircle[] Returns an array of Circle objects
        */
        public function findByUserAndCircleId($userId, $circleId): array
        {
            return $this->createQueryBuilder('uc')
                ->andWhere('uc.user_id = :user')
                ->andWhere('uc.circle = :circle')
                ->setParameter('user', $userId)
                ->setParameter('circle', $circleId)
                ->getQuery()
                ->getResult()
            ;
        }
    //    public function findOneBySomeField($value): ?UserCircle
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
