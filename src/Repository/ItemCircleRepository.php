<?php

namespace App\Repository;

use App\Entity\Circle;
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
    public function findAllInArray($value, $user): array
    {
        return
            $this->createQueryBuilder('iCirc')
            // , itype.code as item_type_code, itype.label as item_type_label, i.property_1, i.property_2, i.property_3, i.property_4, i.property_5, itypeCategory.label as category_label')
            ->leftJoin('iCirc.item', 'i', 'ON')
            // ->leftJoin('i.itemType', 'itype', 'ON')
            // ->leftJoin('itype.category', 'itypeCategory', 'ON')
            ->andWhere('iCirc.circle IN (:ids)')
            ->andWhere('i.owner <> :userId')
            // ->addGroupBy('i.id, itype.code, itype.label, itypeCategory.label')
            ->setParameter('ids', $value)
            ->setParameter('userId', $user)
            ->getQuery()
            // ->getSQL()
            ->getResult()
        ;
    }
}
