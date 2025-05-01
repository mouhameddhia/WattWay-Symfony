<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findByRole(string $role): array
    {
    return $this->createQueryBuilder('u')
        ->andWhere('u.roleUser = :role')
        ->setParameter('role', $role)
        ->getQuery()
        ->getResult();
    }
    public function getLoggedInUser(String $emailUser):User {
        return $this->createQueryBuilder('u')
        ->andWhere('u.emailUser = :email')
        ->setParameter("email",$emailUser)
        ->getQuery()
        ->getSingleResult();
    }

    public function findOneByFaceToken(string $faceToken): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.face_token = :faceToken')
            ->setParameter('faceToken', $faceToken)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByFaceDescriptor(array $descriptor): ?User
{
    $threshold = 0.5; // Adjust this based on your needs
    
    $query = $this->createQueryBuilder('u')
        ->where('u.faceDescriptor IS NOT NULL')
        ->getQuery();
    
    $users = $query->getResult();
    
    foreach ($users as $user) {
        $storedDescriptor = $user->getFaceDescriptor();
        if (!$storedDescriptor) continue;
        
        $distance = 0;
        for ($i = 0; $i < 128; $i++) {
            $diff = $storedDescriptor[$i] - $descriptor[$i];
            $distance += $diff * $diff;
        }
        $distance = sqrt($distance);
        
        if ($distance < $threshold) {
            return $user;
        }
    }
    
    return null;
}





// UserManagement functions

public function searchUsers(string $query): array
{
    return $this->createQueryBuilder('u')
        ->where('u.emailUser LIKE :query')
        ->orWhere('u.firstNameUser LIKE :query')
        ->orWhere('u.lastNameUser LIKE :query')
        ->orWhere('u.phoneNumber LIKE :query')
        ->orWhere('u.address LIKE :query')
        ->orWhere('u.paymentDetails LIKE :query')
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
}

public function sortUsers(string $sortBy, string $direction = 'ASC'): array
{
    $validFields = ['emailUser', 'firstNameUser', 'lastNameUser', 'phoneNumber', 'paymentDetails'];
    $sortBy = in_array($sortBy, $validFields) ? $sortBy : 'emailUser';
    $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
    
    return $this->createQueryBuilder('u')
        ->orderBy('u.'.$sortBy, $direction)
        ->getQuery()
        ->getResult();
}

public function filterUsers(string $filterBy, string $value): array
{
    $validFilters = ['paymentDetails'];
    if (!in_array($filterBy, $validFilters)) {
        return $this->findAll();
    }
    
    return $this->createQueryBuilder('u')
        ->where('u.'.$filterBy.' = :value')
        ->setParameter('value', $value)
        ->getQuery()
        ->getResult();
}


public function clearExpiredBans(): void
{
    $this->createQueryBuilder('u')
        ->update()
        ->set('u.isBanned', 'false')
        ->set('u.banUntil', 'null')
        ->set('u.banReason', 'null')
        ->where('u.isBanned = true')
        ->andWhere('u.banUntil IS NOT NULL')
        ->andWhere('u.banUntil < :now')
        ->setParameter('now', new \DateTime())
        ->getQuery()
        ->execute();
}

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
