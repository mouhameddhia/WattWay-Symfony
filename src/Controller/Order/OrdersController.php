<?php

namespace App\Controller\Order;
use App\Form\CreateOrderType;
use App\Entity\Order;
use App\Entity\Item;
use App\Repository\OrderRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrdersController extends AbstractController
{

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
        $conditions[] = 'b.idAdmin != 1';
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

    // Redirect to Main Page
    #[Route('/orders', name: 'orders_index')]
    public function index(): Response
    {
        return $this->render('backend/order/orders.html.twig');
    }


    // Create An Order
    #[Route('/order/createOrder', name: 'order_create')]
    public function createOrder(Request $request, EntityManagerInterface $entityManager, ItemRepository $itemRepository): Response
    {
        // Fetch all items from the database
        $itemsList = $itemRepository->findAll();

        // Initialize the orderItems array
        $orderItems = [];

        // Check if the form was submitted
        if ($request->isMethod('POST')) {
            // Create a new order entity
            $order = new Order();
            $order->setSupplierOrder($request->request->get('supplierOrder'));
            $order->setDateOrder(new \DateTime());  // Set current date for order creation
            $order->setStatusOrder('Pending');  // Set default status to Pending
            $order->setAddressSupplierOrder($request->request->get('addressSupplierOrder'));
            $order->setTotalAmountOrder((float) $request->request->get('totalAmountOrder'));
            $order->setIdAdmin((int) $request->request->get('admin'));

            // Persist the order first
            $entityManager->persist($order);
            $entityManager->flush(); // Flush to generate order ID

            // Get the items data from the request
            $itemsData = json_decode($request->request->get('orderItems'), true); // true to get an associative array

            // Ensure $itemsData is an array before processing
            if (is_array($itemsData)) {
                foreach ($itemsData as $itemData) {
                    $item = new Item();
                    $item->setNameItem($itemData['name']);
                    $item->setCategoryItem($itemData['category']);
                    $item->setPricePerUnitItem($itemData['pricePerUnit']);
                    $item->setQuantityItem($itemData['quantity']);
                    $item->setOrderId($order->getIdOrder()); // Link item to the newly created order

                    // Persist the item to the database
                    $entityManager->persist($item);

                    // Add the item to the orderItems array (if needed)
                    $orderItems[] = $item;
                }
            }

            // Flush all changes (order and items)
            $entityManager->flush();
            // ✅ Add success flash message
            $this->addFlash('success', 'Order created successfully!');
            // Redirect to orders list or order detail page
            return $this->redirectToRoute('orders', ['highlight' => $order->getIdOrder()]);
        }

        return $this->render('backend/order/createOrder.html.twig', [
            'itemsList' => $itemsList,
            'orderItems' => $orderItems,  // Pass the items array to the template
        ]);
    }


    // Fetch An Order's Items
    #[Route('/order/items/{orderId}', name: 'order_items')]
    public function getOrderItems(int $orderId, ItemRepository $itemRepository): JsonResponse
    {
        $items = $itemRepository->findItemsByOrderId($orderId);

        $itemsData = array_map(fn($item) => [
            'id' => $item->getIdItem(),
            'name' => $item->getNameItem(),
            'quantity' => $item->getQuantityItem(),
            'price' => $item->getPricePerUnitItem(),
            'category' => $item->getCategoryItem()
        ], $items);

        return $this->json(['items' => $itemsData]);
    }



    // Filter Orders by Month
    #[Route('/orders/by-month/{month}', name: 'orders_by_month')]
    public function getOrdersByMonth(int $month, OrderRepository $orderRepository): JsonResponse
    {
        $startDate = new \DateTime("first day of $month/1");
        $endDate = new \DateTime("last day of $month/1 23:59:59");

        $orders = $orderRepository->findOrdersByMonth($startDate, $endDate);

        return $this->json([
            'orders' => array_map(fn($order) => [
                'id' => $order->getIdOrder(),
                'supplier' => $order->getSupplierOrder(),
                'date' => $order->getDateOrder()->format('m/d/Y'),
                'status' => $order->getStatusOrder(),
                'total' => $order->getTotalAmountOrder()
            ], $orders)
        ]);
    }


    // Delete An Order
    #[Route('/order/delete/{idOrder}', name: 'order_delete', methods: ['GET'])]
    public function deleteOrder(int $idOrder, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($idOrder);

        if (!$order) {
            $this->addFlash('error', 'Order not found!');
            return $this->redirectToRoute('orders');
        }

        $entityManager->remove($order);
        $entityManager->flush();

        $this->addFlash('success', 'Order deleted successfully!');
        return $this->redirectToRoute('orders');
    }

    // Render User to Suppliers Map
    #[Route('/supplier-map', name: 'supplier_map')]
    public function supplierMap(): Response
    {
        return $this->render('backend/order/map.html.twig');
    }


    // Update An Order
    #[Route('/order/update/{idOrder}', name: 'order_update', methods: ['POST'])]
    public function updateOrder(Request $request, int $idOrder, EntityManagerInterface $entityManager): JsonResponse
    {
        $order = $entityManager->getRepository(Order::class)->find($idOrder);

        if (!$order) {
            return $this->json(['success' => false, 'message' => 'Order not found']);
        }

        $data = json_decode($request->getContent(), true);

        $order->setSupplierOrder($data['supplier']);
        $order->setDateOrder(new \DateTime($data['date']));
        $order->setStatusOrder($data['status']);
        $order->setTotalAmountOrder((float) $data['total']);
        $order->setAddressSupplierOrder($data['address']);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/order/item/update/{itemId}', name: 'update_item_quantity', methods: ['POST'])]
    public function updateItemQuantity(int $itemId,Request $request,EntityManagerInterface $em): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $newQuantity = $data['quantity'] ?? null;

        if (!is_numeric($newQuantity) || $newQuantity < 0) {
            return $this->json(['success' => false, 'message' => 'Invalid quantity'], 400);
        }

        $query = $em->createQuery('UPDATE App\Entity\Item i SET i.quantityItem = :qty WHERE i.idItem = :id')
            ->setParameter('qty', (int) $newQuantity)
            ->setParameter('id', $itemId);

        $rowsAffected = $query->execute();

        if ($rowsAffected === 0) {
            return $this->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        return $this->json(['success' => true, 'newQuantity' => (int) $newQuantity]);
    }



    #[Route('/order/item/delete/{itemId}', name: 'delete_item', methods: ['DELETE'])]
    public function deleteItem(int $itemId,EntityManagerInterface $entityManager): JsonResponse {
        $item = $entityManager->getRepository(Item::class)->find($itemId);

        if (!$item) {
            return $this->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        try {
            $entityManager->remove($item);
            $entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error deleting item: ' . $e->getMessage()
            ], 500);
        }
    }
    #[Route('/api/orders/by-admin/{id}', name: 'orders_by_admin', methods: ['GET'])]
    public function getOrdersByAdmin(OrderRepository $repo, int $id): JsonResponse {
        $orders = $repo->findBy(['idAdmin' => $id]); // Adapt to your entity
        return $this->json($orders);
    }

    

  






}
