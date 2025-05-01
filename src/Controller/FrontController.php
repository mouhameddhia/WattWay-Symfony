<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Order;
use App\Entity\Item;
use App\Repository\OrderRepository;
class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(ItemRepository $itemRepository, OrderRepository $orderRepository): Response
    {
        // Fetch all items from the database
        $items = $itemRepository->findAll();

        // Divide items into client and admin items
        $dividedItems = $this->divideItemsByAdmin($items, $orderRepository);

        // Calculate total quantities for each item
        $itemQuantities = $this->calculateItemQuantities($dividedItems['clientItems'], $dividedItems['adminItems']);

        // Render the template and pass the items to it
        return $this->render('frontend/baseFront.html.twig', [
            'items' => $items,
            'itemQuantities' => $itemQuantities,
            'clientItems' => $dividedItems['clientItems'],
            'adminItems' => $dividedItems['adminItems'],
        ]);
    }
    
    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Create new order
        $order = new Order();
        $order->setSupplierOrder('WattShop');
        $order->setDateOrder(new \DateTime());
        $order->setStatusOrder('Pending');
        $order->setAddressSupplierOrder('WattShop Address');
        $order->setTotalAmountOrder($data['totalAmount']);
        $order->setIdAdmin(12); // Fixed admin ID as per requirements

        $entityManager->persist($order);
        $entityManager->flush();

        // Add items to order
        foreach ($data['items'] as $itemData) {
            $item = new Item();
            $item->setNameItem($itemData['name']);
            $item->setCategoryItem(''); // You might want to set this based on your data
            $item->setPricePerUnitItem($itemData['price']);
            $item->setQuantityItem($itemData['quantity']);
            $item->setOrderId($order->getIdOrder());

            $entityManager->persist($item);
        }

        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'orderId' => $order->getIdOrder(),
            'message' => 'Order created successfully'
        ]);
    }

    private function divideItemsByAdmin(array $items, OrderRepository $orderRepository): array
    {
        $clientItems = [];
        $adminItems = [];

        foreach ($items as $item) {
            $orderId = $item->getOrderId();
            if ($orderId === null) {
                continue; // Skip items without an order
            }

            $order = $orderRepository->find($orderId);
            if (!$order) {
                continue; // Skip if order not found
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

    private function calculateItemQuantities(array $clientItems, array $adminItems): array
    {
        $quantities = [];

        // Process client items
        foreach ($clientItems as $item) {
            $itemName = $item->getNameItem();
            $quantity = $item->getQuantityItem();

            if (!isset($quantities[$itemName])) {
                $quantities[$itemName] = [
                    'clientQuantity' => 0,
                    'adminQuantity' => 0,
                ];
            }

            $quantities[$itemName]['clientQuantity'] += $quantity;
        }

        // Process admin items
        foreach ($adminItems as $item) {
            $itemName = $item->getNameItem();
            $quantity = $item->getQuantityItem();

            if (!isset($quantities[$itemName])) {
                $quantities[$itemName] = [
                    'clientQuantity' => 0,
                    'adminQuantity' => 0,
                ];
            }

            $quantities[$itemName]['adminQuantity'] += $quantity;
        }

        return $quantities;
    }
    
    #[Route('/update-cart', name: 'update_cart', methods: ['POST'])]
    public function updateCart(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Example logging to debug
        // dump($data); // or use logger

        // Simulate cart processing (replace with actual logic later)
        if (!isset($data['cart'])) {
            return new JsonResponse(['error' => 'No cart data'], 400);
        }

        // You could save the cart to session or database here

        return new JsonResponse(['success' => true]);
    }

}
?>