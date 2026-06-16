<?php

namespace App\Repository;

use App\Entity\Circle;
use App\Entity\Item;
use App\Entity\User;
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
                    WHERE circle IN (
                        SELECT circle
                        FROM public.user_circle
                        WHERE user_circle.user = :uid
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
   public function findAllowedId($userId, $itemId): array
   {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT item.id
            FROM public.item
            WHERE 
                item.id = (
                    SELECT item_id
                    FROM public.item_circle 
                    WHERE circle IN (
                        SELECT circle 
                        FROM public.user_circle
                        WHERE user_circle.user = :uid
                    )
                    AND item_circle.item_id = :iid
                    LIMIT 1
                )
            AND
                item.owner_id <> :uid;
        ";
        // $terms = '%'.addcslashes($searchTerms, '%_').'%';
        $resultSet = $conn->executeQuery($sql, ['uid' => $userId, 'iid' => $itemId]);

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

    public function findWithCirclesAndMembers(int $id): ?Item
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.itemCircles', 'ic')->addSelect('ic')
            ->leftJoin('ic.circle', 'c')->addSelect('c')
            ->leftJoin('c.userCircles', 'uc')->addSelect('uc')
            ->leftJoin('uc.user', 'u')->addSelect('u')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByKeywordAndCategory(
        User    $user,
        ?string $keyword = null,
        ?string $category = null,
    ): array {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.itemCircles', 'ic')
            ->innerJoin('ic.circle', 'c')
            ->leftJoin('c.userCircles', 'uc')
            ->leftJoin('uc.user', 'u')
            ->addSelect('ic', 'c')
            ->where('u = :user')
            ->andWhere('i.owner != :user')
            ->setParameter('user', $user)
            ->groupBy('i.id, ic.id, c.id')
            ->orderBy('i.id', 'DESC')
            ;

        if ($keyword !== null) {
            $qb->andWhere(
                'LOWER(i.property_1) LIKE LOWER(:keyword)
                OR LOWER(i.property_2) LIKE LOWER(:keyword)'
            )
            ->setParameter('keyword', '%' . $keyword . '%');
        }
        if ($category !== null) {
            $qb->innerJoin('i.itemType', 'type')
               ->innerJoin('type.category', 'cat')
               ->andWhere('cat.code = :category')
               ->setParameter('category', $category);
        }
        return $qb->getQuery()->getResult();
    }
}
