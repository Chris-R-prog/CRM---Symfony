<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Opportunity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opportunity>
 */
class OpportunityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunity::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.deletedAt IS NULL')
            ->orderBy('o.amount', 'ASC')
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

    public function findOneWithContactsBySlug(string $slug): ?Opportunity
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.opportunityContacts', 'oc')
            ->addSelect('oc')
            ->leftJoin('oc.contact', 'c')
            ->addSelect('c')
            ->where('o.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
