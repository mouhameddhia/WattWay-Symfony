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


    // FeedbackManagement functions

    public function searchFeedbacks(string $query): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user', 'u') 
            ->addSelect('u') 
            ->where('f.content LIKE :query')
            ->orWhere('u.firstNameUser LIKE :query')
            ->orWhere('u.lastNameUser LIKE :query')
            ->orWhere('CONCAT(u.firstNameUser, \' \', u.lastNameUser) LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()
            ->getResult();
    }
public function sortFeedbacks(string $sortBy, string $direction = 'DESC'): array
{
    $validFields = ['date', 'rating', 'content'];
    $sortBy = in_array($sortBy, $validFields) ? $sortBy : 'date';
    $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
    
    return $this->createQueryBuilder('f')
        ->orderBy('f.'.$sortBy, $direction)
        ->getQuery()
        ->getResult();
}

public function filterFeedbacks(string $filterBy, string $value): array
{
    $validFilters = ['rating'];
    if (!in_array($filterBy, $validFilters)) {
        return $this->findAllFeedbackWithUser();
    }
    
    return $this->createQueryBuilder('f')
        ->where('f.'.$filterBy.' = :value')
        ->setParameter('value', $value)
        ->getQuery()
        ->getResult();
}


public function getRatingDistribution(): array
{
    $results = $this->createQueryBuilder('f')
        ->select('f.rating, COUNT(f.idFeedback) as count')
        ->groupBy('f.rating')
        ->getQuery()
        ->getResult();

    // Initialize all possible ratings (1-5)
    $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    
    // Fill with actual data
    foreach ($results as $result) {
        $distribution[$result['rating']] = (int)$result['count'];
    }

    return $distribution;
}

  
}
