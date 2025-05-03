<?php

namespace App\Repository;

use App\Entity\Mechanic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mechanic>
 *
 * @method Mechanic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mechanic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mechanic[]    findAll()
 * @method Mechanic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MechanicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mechanic::class);
    }

    /**
     * Search mechanics by name, email, or speciality.
     *
     * @param string $term
     * @return Mechanic[]
     */
    public function search(string $term): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.nameMechanic LIKE :term')
            ->orWhere('m.emailMechanic LIKE :term')
            ->orWhere('m.specialityMechanic LIKE :term')
            ->setParameter('term', '%'. $term .'%')
            ->orderBy('m.nameMechanic', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort mechanics by name.
     *
     * @param string $direction 'ASC' or 'DESC'
     * @return Mechanic[]
     */
    public function sortByName(string $direction = 'ASC'): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.nameMechanic', $direction)
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort mechanics by speciality.
     *
     * @param string $direction 'ASC' or 'DESC'
     * @return Mechanic[]
     */
    public function sortBySpeciality(string $direction = 'ASC'): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.specialityMechanic', $direction)
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort mechanics by cars repaired count.
     *
     * @param string $direction 'ASC' or 'DESC'
     * @return Mechanic[]
     */
    public function sortByCarsRepaired(string $direction = 'ASC'): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.carsRepaired', $direction)
            ->getQuery()
            ->getResult();
    }
    public function findRepairCounts(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.nameMechanic AS name, m.carsRepaired AS repairs')
            ->orderBy('m.nameMechanic', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
