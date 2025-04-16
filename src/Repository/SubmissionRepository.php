<?php

namespace App\Repository;

use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Submission>
 */
class SubmissionRepository extends ServiceEntityRepository
{
    public function findByFilters(?string $status, ?string $urgency, ?string $keyword): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.idSubmission', 'DESC');

        if ($status) {
            $qb->andWhere('s.status = :status')
               ->setParameter('status', $status);
        }

        if ($urgency) {
            $qb->andWhere('s.urgencyLevel = :urgency')
               ->setParameter('urgency', $urgency);
        }

        if ($keyword) {
            $qb->andWhere('s.description LIKE :keyword')
               ->setParameter('keyword', '%'.$keyword.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['idSubmission' => 'DESC']);
    }

    public function searchByKeywords(string $keywords): array
    {
        $qb = $this->createQueryBuilder('s');

        $terms = explode(' ', $keywords);
        foreach ($terms as $key => $term) {
            $qb->orWhere("s.description LIKE :term$key")
                ->setParameter("term$key", '%' . $term . '%');
        }

        return $qb
            ->orderBy('s.dateSubmission', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Submission::class);
    }

    //    /**
    //     * @return Submission[] Returns an array of Submission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    public function findSubmissionsWithResponses()
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.responses', 'r') // Changed to inner join
            ->addSelect('r')
            ->getQuery()
            ->getResult();
    }
    //    public function findOneBySomeField($value): ?Submission
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
