<?php
namespace App\Repository;

use App\Entity\Assignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assignment::class);
    }

    /**
     * Full-text-style search across description, status, car and mechanic name.
     */
    public function search(string $term): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.car', 'c')->addSelect('c')
            ->leftJoin('a.assignmentMechanics', 'am')->addSelect('am')
            ->leftJoin('am.idMechanic', 'm')->addSelect('m')
            ->andWhere('a.descriptionAssignment LIKE :t')
            ->orWhere('a.statusAssignment       LIKE :t')
            ->orWhere('c.brandCar               LIKE :t')
            ->orWhere('c.modelCar               LIKE :t')
            ->orWhere('m.nameMechanic           LIKE :t')
            ->setParameter('t', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }

    public function sortByDate(string $dir): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.car', 'c')->addSelect('c')
            ->leftJoin('a.assignmentMechanics', 'am')->addSelect('am')
            ->leftJoin('am.idMechanic', 'm')->addSelect('m')
            ->orderBy('a.dateAssignment', $dir)
            ->getQuery()
            ->getResult();
    }

    public function sortByStatus(string $dir): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.car', 'c')->addSelect('c')
            ->leftJoin('a.assignmentMechanics', 'am')->addSelect('am')
            ->leftJoin('am.idMechanic', 'm')->addSelect('m')
            ->orderBy('a.statusAssignment', $dir)
            ->getQuery()
            ->getResult();
    }

    public function sortByCar(string $dir): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.car', 'c')->addSelect('c')
            ->leftJoin('a.assignmentMechanics', 'am')->addSelect('am')
            ->leftJoin('am.idMechanic', 'm')->addSelect('m')
            ->orderBy('c.brandCar', $dir)
            ->addOrderBy('c.modelCar', $dir)
            ->getQuery()
            ->getResult();
    }
}
