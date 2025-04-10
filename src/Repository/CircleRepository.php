<?php

namespace App\Repository;

use App\Entity\Circle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Circle>
 */
class CircleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Circle::class);
    }

    //    /**
    //     * @return Circle[] Returns an array of Circle objects
    //     */
    //    public function findByUserAndCircleId($userId, $circleId): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.user_id = :user')
    //            ->andWhere('c.circle_id = :circle')
    //            ->setParameter('user', $userId)
    //            ->setParameter('circle', $circleId)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Circle
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
