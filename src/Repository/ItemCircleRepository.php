<?php

namespace App\Repository;

use App\Entity\ItemCircle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemCircle>
 */
class ItemCircleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemCircle::class);
    }

    /**
    * @return ItemCircle[] Returns an array of Item objects
    */
    public function findAllInArray($value): array
    {
        return
            $this->createQueryBuilder('ic')
            ->select('i.id, i.description, icat.name as category, o.email as owner')
            ->leftJoin('ic.item', 'i', 'ON')
            ->leftJoin('i.category', 'icat', 'ON')
            ->leftJoin('i.owner', 'o', 'ON')
            ->andWhere('ic.circle IN (:ids)')
            ->addGroupBy('i.id, i.description, icat.name, o.email')
            ->setParameter('ids', $value)
            ->getQuery()
            // ->getSQL()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?ItemCircle
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
