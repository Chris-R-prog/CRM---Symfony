<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Lead;
use App\Entity\User;
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

    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->andWhere('l.deletedAt IS NULL')
            ->setParameter('user', $user)
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
}
