<?php

namespace App\Repository;

use App\Entity\Merci;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Merci>
 */
class MerciRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merci::class);
    }

    public function findByUser($user)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.fromEmployee = :user')
            ->setParameter('user', $user)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
