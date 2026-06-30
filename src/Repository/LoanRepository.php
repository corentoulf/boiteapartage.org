<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

   /**
    * @return Loan[] Returns an array of Loan objects
    */
   public function findPendingByItemBorrower($item, $borrower): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.borrower = :b')
           ->andWhere('l.item = :i')
           ->andWhere('l.status IN (:statuses)')
           ->setParameter('b', $borrower)
           ->setParameter('i', $item)
           ->setParameter('statuses', ['requested', 'accepted', 'lent'])
           ->orderBy('l.id', 'ASC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Loan[] Returns an array of Loan objects
    */
   public function findLoanBorrowedWithoutHistory($borrower): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.borrower = :b')
           ->andWhere('l.status NOT IN (:statuses)')
           ->setParameter('b', $borrower)
           ->setParameter('statuses', ['cancelled', 'returned', 'rejected'])
           ->orderBy('l.id', 'DESC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Loan
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
