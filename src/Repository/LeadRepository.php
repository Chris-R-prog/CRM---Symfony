<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Lead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lead>
 */
class LeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lead::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.deletedAt IS NULL')
            ->orderBy('l.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByAccount(Account $account): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.account = :account')
            ->andWhere('o.deletedAt IS NULL')
            ->setParameter('account', $account)
            ->orderBy('o.amount', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Lead[] Returns an array of Lead objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lead
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
