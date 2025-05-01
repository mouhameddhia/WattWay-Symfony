<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ItemRepository;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('backend/baseBack.html.twig');
    }

    // Redirect To Orders Page 
    #[Route('/orders', name: 'orders')]
    public function orders(OrderRepository $orderRepository, Request $request, EntityManagerInterface $em): Response
{
    $locale = $request->getLocale();
    $page = max(1, $request->query->getInt('page', 1));
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $filter = $request->query->get('filter', 'all');
    $sortStatus = $request->query->get('sortStatus');
    $sortAmount = $request->query->get('sortAmount');
    $month = $request->query->get('month', 'all');

    $dql = 'SELECT b FROM App\Entity\Order b';
    $conditions = [];
    $parameters = [];

    // Filter by admin/client
    if ($filter === 'client') {
        $conditions[] = 'b.idAdmin = 12';
    } elseif ($filter === 'admin') {
        $conditions[] = 'b.idAdmin != 12';
    }

    // Filter by status
    if ($sortStatus) {
        $conditions[] = 'b.statusOrder = :statusOrder';
        $parameters['statusOrder'] = $sortStatus;
    }

    // Filter by month (if selected)
    if ($month !== 'all') {
        $conditions[] = 'MONTH(b.dateOrder) = :month';
        $parameters['month'] = (int)$month;
    }

    if (!empty($conditions)) {
        $dql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    // Sorting
    if ($sortAmount) {
        $dql .= ' ORDER BY b.totalAmountOrder ' . strtoupper($sortAmount);
    } else {
        $dql .= ' ORDER BY b.idOrder DESC';
    }

    $query = $em->createQuery($dql)
        ->setFirstResult($offset)
        ->setMaxResults($limit);

    foreach ($parameters as $key => $value) {
        $query->setParameter($key, $value);
    }

    $paginator = new Paginator($query);
    $totalPages = ceil(count($paginator) / $limit);

    // Stats
    $orders = $orderRepository->findAll();
    $currentYear = date('Y');
    $totalOrdersCount = count($orders);
    $clientOrdersCount = $orderRepository->countClientOrders();
    $adminOrdersCount = $totalOrdersCount - $clientOrdersCount;
    $completedOrdersCount = $orderRepository->count(['statusOrder' => 'Delivered']);

    return $this->render('backend/order/orders.html.twig', [
        'locale' => $locale,
        'orders' => $paginator,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'currentFilter' => $filter,
        'currentSortStatus' => $sortStatus,
        'currentSortAmount' => $sortAmount,
        'currentMonth' => $month,
        'total_orders_count' => $totalOrdersCount,
        'admin_orders_count' => $adminOrdersCount,
        'client_orders_count' => $clientOrdersCount,
        'completed_orders_count' => $completedOrdersCount,
        'admin_orders_by_month' => $orderRepository->getMonthlyOrderCounts($currentYear, false),
        'client_orders_by_month' => $orderRepository->getMonthlyOrderCounts($currentYear, true),
        'expenses_by_month' => $orderRepository->getMonthlyOrderAmounts($currentYear, false),
        'revenues_by_month' => $orderRepository->getMonthlyOrderAmounts($currentYear, true),
    ]);
}



    



    // Redirect to Items Page 
    #[Route('/items', name: 'items')]
    public function items(ItemRepository $itemRepository, OrderRepository $orderRepository, Request $request): Response
{
    $searchTerm = $request->query->get('search');

    $filterCategory = $request->query->get('category', null);  // Get selected category filter

    $sort = $request->query->get('sort', null);
    $items = $itemRepository->findAll();

    $dividedItems = $itemRepository->divideItemsByAdmin($items, $orderRepository);
    $itemQuantities = $itemRepository->calculateItemQuantities(
        $dividedItems['clientItems'], 
        $dividedItems['adminItems']
    );

    $itemsDiff = [];

    foreach ($itemQuantities as $itemName => $quantities) {
        $adminQty = $quantities['adminQuantity'] ?? 0;
        $clientQty = $quantities['clientQuantity'] ?? 0;
        $diff = $adminQty - $clientQty;

        $itemsDiff[$itemName] = $diff;
    }

    $categories = [
        'Mechanics' => 0,
        'Electronics' => 0,
        'Electricity' => 0,
        'Interior' => 0,
        'Exterior' => 0,
        'Cooling & Heating' => 0,
        'Lubricants & Fluids' => 0,
        'Accessories' => 0,
        'Body Parts' => 0,
        'Performance Parts' => 0,
    ];

    $categoriesRevenue = [
        'Mechanics' => 0,
        'Electronics' => 0,
        'Electricity' => 0,
        'Interior' => 0,
        'Exterior' => 0,
        'Cooling & Heating' => 0,
        'Lubricants & Fluids' => 0,
        'Accessories' => 0,
        'Body Parts' => 0,
        'Performance Parts' => 0,
    ];

    function getCategoryForItem($itemName) {
        if (in_array($itemName, ["Brake Pads", "Spark Plugs", "Timing Belt"])) return "Mechanics";
        if (in_array($itemName, ["Car Battery", "Headlights", "Bluetooth Car Kit"])) return "Electronics";
        if (in_array($itemName, ["Alternator", "Starter Motor", "Fuse Box"])) return "Electricity";
        if (in_array($itemName, ["Phone Holder for Car", "Car Seat Covers", "Steering Wheel Cover"])) return "Interior";
        if (in_array($itemName, ["Windshield Wipers", "Car Wax", "License Plate Frame"])) return "Exterior";
        if (in_array($itemName, ["Air Conditioning Recharge Kit", "Radiator", "Heater Core"])) return "Cooling & Heating";
        if (in_array($itemName, ["Motor Oil", "Transmission Fluid", "Brake Fluid"])) return "Lubricants & Fluids";
        if (in_array($itemName, ["Car Wash Kit", "Car Air Freshener", "Car Trash Can"])) return "Accessories";
        if (in_array($itemName, ["Car Exhaust System", "Bumper", "Side Mirror", "Wheel"])) return "Body Parts";
        if (in_array($itemName, ["Turbocharger", "Performance Air Filter", "Sport Exhaust System"])) return "Performance Parts";
    
        return "Uncategorized";
    }

    foreach ($itemsDiff as $itemName => $diff) {
        $category = getCategoryForItem($itemName);

        // Find item price
        $item = array_filter($items, function($item) use ($itemName) {
            return $item->getNameItem() === $itemName;
        });
        $item = array_values($item)[0] ?? null;

        $pricePerUnit = $item ? $item->getPricePerUnitItem() : 0;

        if (isset($categories[$category])) {
            $categories[$category] += $diff;
            $categoriesRevenue[$category] += $diff * $pricePerUnit;
        }
    }

    $chartData = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Nov', 'Dec'],
        'datasets' => [
            [
                'label' => 'Sales',
                'data' => [65, 59, 80, 81, 56, 55],
            ],
        ],
    ];

    // Sorting logic based on the 'sort' parameter
    if ($sort) {
        if ($sort === 'asc') {
            asort($itemsDiff); // Sort in ascending order
        } elseif ($sort === 'desc') {
            arsort($itemsDiff); // Sort in descending order
        }
    }

    // Initialize category color map and index
    $categoryColors = [];
    $colorIndex = 0;
    $colors = ['#e6f7ff', '#fff7e6', '#f9e6ff', '#e6ffe6', '#ffe6e6', '#e6e6ff'];

    // Group items by name and calculate aggregated data
    $groupedItems = [];
    foreach ($items as $item) {
        $category = $item->getCategoryItem();
        if (!isset($categoryColors[$category])) {
            $categoryColors[$category] = $colors[$colorIndex % count($colors)];
            $colorIndex++;
        }

        if (!isset($groupedItems[$item->getNameItem()])) {
            $groupedItems[$item->getNameItem()] = [
                'id' => $item->getIdItem(),
                'name' => $item->getNameItem(),
                'totalQuantity' => $item->getQuantityItem(),
                'price' => $item->getPricePerUnitItem(),
                'category' => $category,
                'orderCount' => 1,
                'ids' => [$item->getIdItem()]
            ];
        } else {
            $groupedItems[$item->getNameItem()]['totalQuantity'] += $item->getQuantityItem();
            $groupedItems[$item->getNameItem()]['orderCount']++;
            $groupedItems[$item->getNameItem()]['ids'][] = $item->getIdItem();
        }
    }

    // Calculate the quantity difference for each item
    foreach ($groupedItems as $key => &$groupedItem) {
        $quantityData = $itemQuantities[$groupedItem['name']] ?? null;
        $adminQty = $quantityData ? $quantityData['adminQuantity'] : 0;
        $clientQty = $quantityData ? $quantityData['clientQuantity'] : 0;
        $difference = $adminQty - $clientQty;
        $groupedItem['difference'] = $difference;
    }

    // Apply category filter if selected
    if ($filterCategory) {
        $groupedItems = array_filter($groupedItems, function ($item) use ($filterCategory) {
            return $item['category'] === $filterCategory;
        });
    }

    
    // Prepare the final sorted grouped items
    $sortedGroupedItems = [];

    if (in_array($sort, ['asc', 'desc'])) {
        foreach (array_keys($itemsDiff) as $itemName) {
            if (isset($groupedItems[$itemName])) {
                $sortedGroupedItems[] = $groupedItems[$itemName];
            }
        }
    } else {
        $sortedGroupedItems = array_values($groupedItems);
    }


    if ($sort) {
        if ($sort === 'ascc') {
            // Sort by orderCount in ascending order
            usort($sortedGroupedItems, function ($a, $b) {
                return $a['orderCount'] <=> $b['orderCount'];
            });
        } elseif ($sort === 'descc') {
            // Sort by orderCount in descending order
            usort($sortedGroupedItems, function ($a, $b) {
                return $b['orderCount'] <=> $a['orderCount'];
            });
        }
    }
    if ($searchTerm) {
        // Filter the groupedItems array based on item name match (case-insensitive)
        $sortedGroupedItems = array_filter($sortedGroupedItems, function($item) use ($searchTerm) {
            return stripos($item['name'], $searchTerm) !== false;
        });
    }


    return $this->render('backend/order/items.html.twig', [
        'groupedItems' => $sortedGroupedItems,
        'categoryColors' => $categoryColors,
        'items' => $items,
        'chartData' => $chartData,
        'itemQuantities' => $itemQuantities,
        'clientItems' => $dividedItems['clientItems'],
        'adminItems' => $dividedItems['adminItems'],
        'categoriesCount' => $categories,
        'categoriesRevenue' => $categoriesRevenue,
        'itemsDiff' => $itemsDiff
    ]);
}


    
    

}
