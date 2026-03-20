<?php

namespace App\Repository;

use App\Entity\ContactRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactRole>
 */
class ContactRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactRole::class);
    }

    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('cr')
            ->orderBy('cr.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
