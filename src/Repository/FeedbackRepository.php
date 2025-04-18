<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function findLatestFeedbacks(int $limit = 5)
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.date', 'DESC')  
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findAllFeedbackWithUser()
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user', 'u')
            ->addSelect('u') // Select User fields as well
            ->getQuery() // get the query object
            ->getResult(); //execute the query and return the results
    }
  
}