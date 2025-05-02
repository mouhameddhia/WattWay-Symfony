<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // Returns an Array of Items
    public function findAllItems(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.nameItem', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Find All items and All Orders
    public function findAllWithOrders()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.order', 'o') // Assuming you have a relation between Item and Order
            ->addSelect('o')
            ->getQuery()
            ->getResult();
    }

    // Classify items between Admin and Client 
    public function divideItemsByAdmin(array $items, OrderRepository $orderRepository): array
    {
        $clientItems = [];
        $adminItems = [];

        foreach ($items as $item) {
            $orderId = $item->getOrderId();
            if ($orderId === null) {
                continue;
            }

            $order = $orderRepository->find($orderId);
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

    //Calculate item Quantities Diff to generate Stock Levels
    public function calculateItemQuantities(array $clientItems, array $adminItems): array
    {
        $quantities = [];

        foreach ($clientItems as $item) {
            $name = $item->getNameItem();
            $quantities[$name]['clientQuantity'] = ($quantities[$name]['clientQuantity'] ?? 0) + $item->getQuantityItem();
            $quantities[$name]['adminQuantity'] = $quantities[$name]['adminQuantity'] ?? 0;
        }

        foreach ($adminItems as $item) {
            $name = $item->getNameItem();
            $quantities[$name]['adminQuantity'] = ($quantities[$name]['adminQuantity'] ?? 0) + $item->getQuantityItem();
            $quantities[$name]['clientQuantity'] = $quantities[$name]['clientQuantity'] ?? 0;
        }

        return $quantities;
    }

    // Find All items of a specific Order
    public function findItemsByOrderId(int $orderId): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }

    // Return Suggestion of Item Names 
    public function findItemNameSuggestions(string $itemName, string $mode = 'contains'): array
    {
        $query = $this->createQueryBuilder('i')
            ->select('DISTINCT i.nameItem');

        if ($mode === 'startsWith') {
            $query->where('LOWER(i.nameItem) LIKE :name')
                ->setParameter('name', strtolower($itemName) . '%');
        } else {
            $query->where('LOWER(i.nameItem) LIKE :name')
                ->setParameter('name', '%' . strtolower($itemName) . '%');
        }

        $result = $query->orderBy('i.nameItem', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return array_column($result, 'nameItem');
    }

    //Return all orders containing a specified item 
    
    
    //Calculates Available stock 
    public function getAvailableQuantityByItemId(int $itemId): ?int
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.quantityItem')
            ->where('i.idItem = :id')
            ->setParameter('id', $itemId)
            ->getQuery();

        try {
            return $qb->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    // Fetches Items From the database 
    public function findByNameOrKeyword(string $keyword): Item
    {
        return $this->createQueryBuilder('i')
            ->where('LOWER(i.nameItem) LIKE :keyword')
            ->setParameter('keyword', '%' . strtolower($keyword) . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    //Return all orders containing a specified item 

    public function findOrderIdsByItemName(string $itemName): array
{
    try {
        $results = $this->createQueryBuilder('i')
            ->select('DISTINCT i.orderId')
            ->where('LOWER(i.nameItem) LIKE :name')
            ->setParameter('name', '%' . strtolower($itemName) . '%')
            ->getQuery()
            ->getScalarResult();
        
        return array_column($results, 'orderId');
    } catch (\Exception $e) {
        error_log('Search error: ' . $e->getMessage());
        return [];
    }
}
public function remove(Item $item, bool $flush = false): void
{
    $this->_em->remove($item);
    if ($flush) {
        $this->_em->flush();
    }
}

}
