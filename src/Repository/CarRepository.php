<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }
    public function areAllNotAvailable()
    {
        $cars = $this->findAll();
        foreach ($cars as $car) {
            if ($car->getStatusCar()=="available") {
                return false;
            }
        }
        return true;
    }
    public function availableCars()
    {
        $cars = $this->findAll();
        $availableCars = [];
        foreach ($cars as $car) {
            if ($car->getStatusCar()=="available") {
                $availableCars[] = $car;
            }
        }
        return $availableCars;
    }
    public function unavailableCars()
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.statusCar = :sold OR c.statusCar = :rented OR c.statusCar = :repair')
        ->setParameter('sold', 'sold')
        ->setParameter('rented', 'rented')
        ->setParameter('repair', 'under repair')
        ->getQuery()
        ->getResult();
    }

    public function rentedCars()
    {
        $cars = $this->findAll();
        $rentedCars = [];
        foreach ($cars as $car) {
            if ($car->getStatusCar()=="rented") {
                $rentedCars[] = $car;
            }
        }
        return $rentedCars;
    }
    public function soldCars()
    {
        $cars = $this->findAll();
        $soldCars = [];
        foreach ($cars as $car) {
            if ($car->getStatusCar()=="sold") {
                $soldCars[] = $car;
            }
        }
        return $soldCars;
    }
    public function underRepairCars()
    {
        $cars = $this->findAll();
        $underRepairCars = [];
        foreach ($cars as $car) {
            if ($car->getStatusCar()=="under repair") {
                $underRepairCars[] = $car;
            }
        }
        return $underRepairCars;
    }
    public function newCars()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.kilometrageCar = 0')
            ->getQuery()
            ->getResult();
    }
    public function usedCars()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.kilometrageCar > 0')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByPriceASC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.priceCar', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByPriceDESC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.priceCar', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByBrandModelASC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.brandCar', 'ASC')
            ->addOrderBy('c.modelCar', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByBrandModelDESC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.brandCar', 'DESC')
            ->addOrderBy('c.modelCar', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByKilometrageASC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.kilometrageCar', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByKilometrageDESC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.kilometrageCar', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByYearASC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.yearCar', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function sortCarsByYearDESC()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.yearCar', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function searchCar(string $searchItem){
        return $this->createQueryBuilder('c')
            ->leftJoin('c.warehouse', 'w')
            ->andWhere('
                c.brandCar LIKE :searchItem OR 
                c.modelCar LIKE :searchItem OR 
                c.yearCar LIKE :searchItem OR 
                c.kilometrageCar LIKE :searchItem OR 
                c.priceCar LIKE :searchItem OR 
                w.city LIKE :searchItem OR 
                w.street LIKE :searchItem OR 
                w.postalCode LIKE :searchItem
            ')
            ->setParameter('searchItem', '%' . $searchItem . '%')
            ->getQuery()
            ->getResult();	
    }
    public function maxPriceCar()
    {
        return $this->createQueryBuilder('c')
        ->select('MAX(c.priceCar) as max_price')
        ->getQuery()
        ->getSingleScalarResult();   
    }
    public function minPriceCar()
    {
        return $this->createQueryBuilder('c')
        ->select('MIN(c.priceCar) as min_price')
        ->andWhere('c.statusCar = :status')
        ->setParameter('status',"available")
        ->getQuery()
        ->getSingleScalarResult();   
    }
    public function getSliderCars($brand, $city, $sliderValue, $direction)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.statusCar = :status')
            ->setParameter("status","available")
            ->andWhere('c.priceCar <= :slider')
            ->setParameter('slider', $sliderValue);
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        if ($brand !== 'all') {
            $qb->andWhere('c.brandCar = :brand')
            ->setParameter('brand', $brand);
        }
        if($city !== 'all'){
            $qb->join('c.warehouse','w')
            ->andWhere('w.city=:city')
            ->setParameter('city',$city);
        }  
        return $qb->orderBy('c.priceCar', $direction)
                ->getQuery()
                ->getResult();
        }
    public function getAllBrands(){
        return $this->createQueryBuilder('c')
        ->select('DISTINCT c.brandCar')
        ->andWhere('c.statusCar = :status')
        ->setParameter('status',"available")
        ->getQuery()
        ->getSingleColumnResult();
    }

    //    /**
    //     * @return Car[] Returns an array of Car objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Car
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
