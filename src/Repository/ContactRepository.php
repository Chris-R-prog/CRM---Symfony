<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deletedAt IS NULL')
            ->orderBy('c.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByAccount(Account $account): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.account = :account')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('account', $account)
            ->getQuery()
            ->getResult();
    }
}
