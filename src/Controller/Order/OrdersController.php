<?php

namespace App\Controller\Order;
use App\Form\CreateOrderType;
use App\Entity\Order;
use App\Entity\Item;
use App\Repository\OrderRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrdersController extends AbstractController
{

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
        $order->setTotalAmountOrder((float)$data['total']);
        $order->setAddressSupplierOrder($data['address']);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }


    // Classify Items (Client/Admin)
    private function divideItemsByAdmin(array $items, OrderRepository $orderRepository): array
    {
        return $orderRepository->divideItemsByAdmin($items);
    }


    // Calculate Stock Levels
    private function calculateItemQuantities(array $clientItems, array $adminItems): array
    {
        $quantities = [];

        foreach ($clientItems as $item) {
            $itemName = $item->getNameItem();
            $quantities[$itemName]['clientQuantity'] = ($quantities[$itemName]['clientQuantity'] ?? 0) + $item->getQuantityItem();
            $quantities[$itemName]['adminQuantity'] = $quantities[$itemName]['adminQuantity'] ?? 0;
        }

        foreach ($adminItems as $item) {
            $itemName = $item->getNameItem();
            $quantities[$itemName]['adminQuantity'] = ($quantities[$itemName]['adminQuantity'] ?? 0) + $item->getQuantityItem();
            $quantities[$itemName]['clientQuantity'] = $quantities[$itemName]['clientQuantity'] ?? 0;
        }

        return $quantities;
    }

   



    
    
}
