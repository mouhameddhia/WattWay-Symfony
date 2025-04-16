<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Warehouse>
 */
class WarehouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }
    public function addressExists(Warehouse $warehouse): bool
    {
        $existingWarehouse = $this->findOneBy([
            'street' => $warehouse->getStreet(),
            'city' => $warehouse->getCity(),
            'postalCode' => $warehouse->getPostalCode(),
            'typeWarehouse' => $warehouse->getTypeWarehouse(),
        ]);

        return $existingWarehouse !== null;
    }
    public function warehouseIsFull(Warehouse $warehouse): bool
    {
        $existingWarehouse = $this->findOneBy([
            'idWarehouse' => $warehouse->getIdWarehouse(),
        ]);
        if ($existingWarehouse) {
            return count($existingWarehouse->getCars()) == $warehouse->getCapacityWarehouse();
        }

        return false;
    }
    public function storageWarehouse(){
        return $this->createQueryBuilder('w')
            ->andWhere('w.typeWarehouse = :val')
            ->setParameter('val', 'storage')
            ->getQuery()
            ->getResult();
    }
    public function repairWarehouse(){
        return $this->createQueryBuilder('w')
            ->andWhere('w.typeWarehouse = :val')
            ->setParameter('val', 'repair')
            ->getQuery()
            ->getResult();
    }
    public function sortWarehouseByCapacityASC(){
        return $this->createQueryBuilder('w')
            ->orderBy('w.capacityWarehouse', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortWarehouseByCapacityDESC(){
        return $this->createQueryBuilder('w')
            ->orderBy('w.capacityWarehouse', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function sortWarehouseByCityASC(){
        return $this->createQueryBuilder('w')
            ->orderBy('w.city', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortWarehouseByCityDESC(){
        return $this->createQueryBuilder('w')
            ->orderBy('w.city', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function searchWarehouse(string $searchItem){
        return $this->createQueryBuilder('w')
            ->andWhere('
                w.city LIKE :searchItem OR 
                w.street LIKE :searchItem OR 
                w.postalCode LIKE :searchItem
            ')
            ->setParameter('searchItem', '%' . $searchItem . '%')
            ->getQuery()
            ->getResult();	
    }
    //    /**
    //     * @return Warehouse[] Returns an array of Warehouse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Warehouse
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
