<?php

namespace App\Controller;
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
    public function orders(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();
        $currentYear = date('Y');
        
        $totalOrdersCount = count($orders);
        $clientOrdersCount = $orderRepository->countClientOrders();
        $adminOrdersCount = $totalOrdersCount - $clientOrdersCount;
        $completedOrdersCount = $orderRepository->count(['statusOrder' => 'Delivered']);

        $adminOrdersByMonth = $orderRepository->getMonthlyOrderCounts($currentYear, false);
        $clientOrdersByMonth = $orderRepository->getMonthlyOrderCounts($currentYear, true);

        $expensesByMonth = $orderRepository->getMonthlyOrderAmounts($currentYear, false);
        $revenuesByMonth = $orderRepository->getMonthlyOrderAmounts($currentYear, true);

        return $this->render('backend/order/orders.html.twig', [
            'orders' => $orders,
            'total_orders_count' => $totalOrdersCount,
            'admin_orders_count' => $adminOrdersCount,
            'client_orders_count' => $clientOrdersCount,
            'completed_orders_count' => $completedOrdersCount,
            'admin_orders_by_month' => $adminOrdersByMonth,
            'client_orders_by_month' => $clientOrdersByMonth,
            'expenses_by_month' => $expensesByMonth,
            'revenues_by_month' => $revenuesByMonth,
        ]);
    }


    // Redirect to Items Page 
    #[Route('/items', name: 'items')]
    public function items(ItemRepository $itemRepository, OrderRepository $orderRepository): Response
    {
        $items = $itemRepository->findAll();

        $dividedItems = $itemRepository->divideItemsByAdmin($items, $orderRepository);
        $itemQuantities = $itemRepository->calculateItemQuantities(
            $dividedItems['clientItems'], 
            $dividedItems['adminItems']
        );

        return $this->render('backend/order/items.html.twig', [
            'items' => $items,
            'itemQuantities' => $itemQuantities,
            'clientItems' => $dividedItems['clientItems'],
            'adminItems' => $dividedItems['adminItems'],
        ]);
    }

    
    

}
