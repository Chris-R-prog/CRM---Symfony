<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.deletedAt IS NULL')
            ->orderBy('a.accountName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
