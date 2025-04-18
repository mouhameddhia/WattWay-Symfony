<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bill>
 */
class BillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }
    public function getBillIdByCarId($idCar)
    {
        return $this->createQueryBuilder('b')
            ->select('DISTINCT b.idBill')
            ->andWhere('b.car = :idCar')
            ->setParameter('idCar', $idCar)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function getBillIdByUserId($idUser)
    {
        return $this->createQueryBuilder('b')
            ->select('DISTINCT b.idBill')
            ->andWhere('b.user = :idUser')
            ->setParameter('idUser', $idUser)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function filterByPaidBills(){
        return $this->createQueryBuilder('b')
            ->andWhere('b.statusBill = 1')
            ->getQuery()
            ->getResult();
    }
    public function filterByUnpaidBills(){
        return $this->createQueryBuilder('b')
            ->andWhere('b.statusBill = 0')
            ->getQuery()
            ->getResult();
    }
    public function searchBill(string $searchItem)
    {
    return $this->createQueryBuilder('b')
        ->leftJoin('b.car', 'c')
        ->leftJoin('b.user', 'u')
        ->andWhere('
            b.idBill LIKE :searchItem OR 
            b.totalAmountBill LIKE :searchItem OR
            c.brandCar LIKE :searchItem OR 
            c.modelCar LIKE :searchItem OR 
            u.firstNameUser LIKE :searchItem OR 
            u.lastNameUser LIKE :searchItem
        ')
        ->setParameter('searchItem', '%' . $searchItem . '%')
        ->getQuery()
        ->getResult();
    }
    public function sortBillsByDateASC()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.dateBill', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortBillsByDateDESC()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.dateBill', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function sortBillsByTotalAmountASC()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.totalAmountBill', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortBillsByTotalAmountDESC()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.totalAmountBill', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function deleteAllBillsForUser(String $emailUser): int
    {
    return $this->createQueryBuilder('b')
        ->delete('App\Entity\Bill', 'b')
        ->where('b.user IN (
            SELECT u.idUser FROM App\Entity\User u 
            WHERE u.emailUser = :emailUser
        )')
        ->setParameter('emailUser', $emailUser)
        ->getQuery()
        ->execute();
    }
    //    /**
    //     * @return Bill[] Returns an array of Bill objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bill
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
