<?php

namespace App\Repository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Item;
/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByMonth(int $month): array
{
    $startDate = new \DateTime("first day of $month/1");
    $endDate = new \DateTime("last day of $month/1 23:59:59");
    
    return $this->createQueryBuilder('o')
        ->where('o.dateOrder BETWEEN :start AND :end')
        ->setParameter('start', $startDate)
        ->setParameter('end', $endDate)
        ->getQuery()
        ->getResult();
    }

    public function findByYearAndClient(int $year)
    {
        $startDate = new \DateTime("$year-01-01");
        $endDate = new \DateTime("$year-12-31 23:59:59");
        
        return $this->createQueryBuilder('o')
            ->where('o.dateOrder BETWEEN :start AND :end')
            ->andWhere('o.idAdmin = 12 OR o.addressSupplierOrder = :WattShop')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->setParameter('WattShop', 'WattShop')
            ->getQuery()
            ->getResult();
    }

    public function findByYearAndAdmin(int $year)
    {
        $startDate = new \DateTime("$year-01-01");
        $endDate = new \DateTime("$year-12-31 23:59:59");
        
        return $this->createQueryBuilder('o')
            ->where('o.dateOrder BETWEEN :start AND :end')
            ->andWhere('o.idAdmin != 12 AND o.addressSupplierOrder != :WattShop')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->setParameter('WattShop', 'WattShop')
            ->getQuery()
            ->getResult();
    }

    public function countClientOrders()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.idOrder)')
            ->where('o.idAdmin = 12 OR o.addressSupplierOrder = :WattShop')
            ->setParameter('WattShop', 'WattShop')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMonthlyOrderCounts(int $year, bool $isClient): array
    {
        $monthlyCounts = array_fill(0, 12, 0);

        $orders = $isClient 
            ? $this->findByYearAndClient($year)
            : $this->findByYearAndAdmin($year);

        foreach ($orders as $order) {
            $month = (int)$order->getDateOrder()->format('n') - 1;
            $monthlyCounts[$month]++;
        }

        return $monthlyCounts;
    }

    public function getMonthlyOrderAmounts(int $year, bool $isClient): array
    {
        $monthlyAmounts = array_fill(0, 12, 0);

        $orders = $isClient 
            ? $this->findByYearAndClient($year)
            : $this->findByYearAndAdmin($year);

        foreach ($orders as $order) {
            $month = (int)$order->getDateOrder()->format('n') - 1;
            $monthlyAmounts[$month] += $order->getTotalAmountOrder();
        }

        return $monthlyAmounts;
    }

    public function findOrdersByMonth(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.dateOrder BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function divideItemsByAdmin(array $items): array
    {
        $clientItems = [];
        $adminItems = [];

        foreach ($items as $item) {
            $orderId = $item->getOrderId();
            if ($orderId === null) {
                continue;
            }

            $order = $this->find($orderId);
            if (!$order) {
                continue;
            }

            if ($order->getIdAdmin() == 12) {
                $clientItems[] = $item;
            } else {
                $adminItems[] = $item;
            }
        }

        return [
            'clientItems' => $clientItems,
            'adminItems' => $adminItems,
        ];
    }

    public function calculateTotalAmount(array $items): float
    {
        $total = 0.0;
        foreach ($items as $itemData) {
            $total += $itemData['price'] * $itemData['quantity'];
        }
        return $total;
    }

    



}
