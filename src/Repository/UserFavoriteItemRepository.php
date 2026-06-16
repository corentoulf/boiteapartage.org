<?php

namespace App\Repository;

use App\Entity\UserFavoriteItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteItem>
 */
class UserFavoriteItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteItem::class);
    }

    //    /**
    //     * @return UserFavoriteItem[] Returns an array of UserFavoriteItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function findOneByUserAndItem($user, $item): ?UserFavoriteItem
       {
           return $this->createQueryBuilder('u')
               ->andWhere('u.user = :uid')
               ->andWhere('u.item_id = :iid')
               ->setParameter('uid', $user)
               ->setParameter('iid', $item)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
