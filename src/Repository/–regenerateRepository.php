<?php

namespace App\Repository;

use App\Entity\–regenerate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<–regenerate>
 */
class –regenerateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, –regenerate::class);
    }

    //    /**
    //     * @return –regenerate[] Returns an array of –regenerate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('�')
    //            ->andWhere('�.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('�.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?–regenerate
    //    {
    //        return $this->createQueryBuilder('�')
    //            ->andWhere('�.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
