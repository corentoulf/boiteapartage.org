<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

   /**
    * @return Item[] Returns an array of Item ids
    */
   public function findTerms($userId, $searchTerms): array
   {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT item.id
            FROM public.item
            WHERE 
                item.id IN (
                    SELECT item_id
                    FROM item_circle 
                    WHERE circle_id IN (
                        SELECT circle_id 
                        FROM public.user_circle
                        WHERE user_circle.user_id_id = :uid
                    
                    )
                )
            AND
                item.owner_id <> :uid
            AND
                (item.property_1 ILIKE :terms
	            OR item.property_2 ILIKE :terms)
            GROUP BY item.id
            ORDER BY id ASC;
        ";
        // $terms = '%'.addcslashes($searchTerms, '%_').'%';
        $resultSet = $conn->executeQuery($sql, ['uid' => $userId, 'terms' => '%'.$searchTerms.'%']);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
   }

   /**
    * @return Item[] Returns an array of Item ids
    */
    public function findByCategory($userId, $categoryCode): array
    {
         $conn = $this->getEntityManager()->getConnection();
         $sql = "
            SELECT i.id
            FROM item i
            LEFT JOIN item_type it ON i.item_type_id = it.id 
            LEFT JOIN item_category ic ON it.category_id = ic.id 
            WHERE 
                i.id IN (
                    SELECT item_id
                    FROM item_circle 
                    WHERE circle_id IN (
                        SELECT circle_id 
                        FROM public.user_circle
                        WHERE user_circle.user_id_id = :uid
                    
                    )
                )
            AND
                i.owner_id <> :uid
            AND
                ic.code = :categoryCode

            GROUP BY i.id, ic.code
            ORDER BY i.id DESC;

         ";
         // $terms = '%'.addcslashes($searchTerms, '%_').'%';
         $resultSet = $conn->executeQuery($sql, ['uid' => $userId, 'categoryCode' => $categoryCode]);
 
         // returns an array of arrays (i.e. a raw data set)
         return $resultSet->fetchAllAssociative();
    }

   /**
    * @return Item[] Returns an array of Item objects
    */
    public function findAllInArray($itemIds): array
    {
        return
            $this->createQueryBuilder('i')
            ->andWhere('i.id IN (:itemIds)')
            // ->addGroupBy('i.id, itype.code, itype.label, itypeCategory.label')
            ->setParameter('itemIds', $itemIds)
            ->getQuery()
            ->getResult()
        ;
    }
//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
