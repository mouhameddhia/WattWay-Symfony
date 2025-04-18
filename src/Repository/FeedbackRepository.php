<?php

namespace App\Repository;

use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function findLatestFeedbacks(int $limit = 5)
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.date', 'DESC')  // Changed from 'f.date' to 'f.dateFeedback'
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findAllFeedbackWithUser()
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user', 'u')
            ->addSelect('u') // Select User fields as well
            ->getQuery()
            ->getResult();
    }
    public function deleteAllFeedbacksForUser(String $emailUser): int
    {
    return $this->createQueryBuilder('f')
        ->delete('App\Entity\Feedback', 'f')
        ->where('f.user IN (
            SELECT u.idUser FROM App\Entity\User u 
            WHERE u.emailUser = :emailUser
        )')
        ->setParameter('emailUser', $emailUser)
        ->getQuery()
        ->execute();
    }



  
}