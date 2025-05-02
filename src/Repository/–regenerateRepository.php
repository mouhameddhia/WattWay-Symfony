<?php

namespace App\Repository;

use App\Entity\â€“regenerate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<â€“regenerate>
 */
class â€“regenerateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, â€“regenerate::class);
    }

    //    /**
    //     * @return â€“regenerate[] Returns an array of â€“regenerate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('â')
    //            ->andWhere('â.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('â.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?â€“regenerate
    //    {
    //        return $this->createQueryBuilder('â')
    //            ->andWhere('â.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
