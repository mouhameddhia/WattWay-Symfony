<?php

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Response>
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    //    /**
    //     * @return Response[] Returns an array of Response objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Response
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    
    public function findBySubmissionId(int $idSubmission): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idSubmission = :idSubmission')
            ->setParameter('idSubmission', $idSubmission)
            ->orderBy('r.dateResponse', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function filterAndSearch(?string $type = null, ?string $search = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.dateResponse', 'DESC');

        if ($type) {
            $qb->andWhere('r.typeResponse = :type')
               ->setParameter('type', $type);
        }

        if ($search) {
            $qb->andWhere('r.message LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
