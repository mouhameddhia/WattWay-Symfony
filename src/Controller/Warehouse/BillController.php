<?php

namespace App\Controller\Warehouse;

use App\Repository\BillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormUpdateBillType;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class BillController extends AbstractController
{
    #[Route('/dashboard/bill', name: 'bill')]
public function listBills(Request $request, ChartBuilderInterface $chartBuilder, BillRepository $billRepository, EntityManagerInterface $em): Response
{
    $page = max(1, $request->query->getInt('page', 1));
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $query = $em->createQuery('SELECT b FROM App\Entity\Bill b ORDER BY b.idBill DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit);

    $paginator = new Paginator($query);
    $totalBills = count($paginator);
    $totalPages = ceil($totalBills / $limit);

    //Statistical parameters
    $year = 2025;
    $associativeBillArray=$billRepository->getMonthlySumForYear($year);
    $income=array_fill(1,12,0);
    foreach($associativeBillArray as $row){
        $income[$row['month']]=$row['total'];
    }
    $chart = $this->createIncomeChart($chartBuilder, $year, $income);
    $associativeRentedSoldArray=$billRepository->getTotalRentedTotalSoldByYear($year);
    $incomeSold = (float) $associativeRentedSoldArray[0]['soldCars'];
    $incomeRented = (float) $associativeRentedSoldArray[0]['rentedCars'];
    $pieChart=$this->createRentedSoldPieChart($chartBuilder,$year,$incomeSold,$incomeRented);
    if($request->isMethod("POST") && $request->request->has('statYear')){
        $statYear=$request->request->get('statYear');
        $associativeBillArray=$billRepository->getMonthlySumForYear($statYear);
        $income=array_fill(1,12,0);
        foreach($associativeBillArray as $row){
            $income[$row['month']]=$row['total'];
        }
        $chart= $this->createIncomeChart($chartBuilder,$statYear,$income);
        $associativeRentedSoldArray=$billRepository->getTotalRentedTotalSoldByYear($statYear);
        $incomeSold = (float) $associativeRentedSoldArray[0]['soldCars'];
        $incomeRented = (float) $associativeRentedSoldArray[0]['rentedCars'];
        $pieChart=$this->createRentedSoldPieChart($chartBuilder,$statYear,$incomeSold,$incomeRented);
        $renderedChart = $this->renderView('backend/Warehouse/billChartLoader.html.twig', [
            'chart' => $chart,
            'pieChart'=>$pieChart,
        ]);
        return new JsonResponse([
            'success' => true,
            'chart'=>$renderedChart]);
    }
    return $this->render('backend/Warehouse/Bill.html.twig', [
        'bills' => $paginator,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'chart' =>$chart,
        'pieChart'=>$pieChart,
        'year' =>$year,
        'allYears'=> $billRepository->getAllYearsBill(),
    ]);
}
    #[Route('/dashboard/bill/delete{id}', name: 'deleteBill')]
    public function deleteBill(int $id, BillRepository $billRepository, ManagerRegistry $doctrine): Response
    {
        $bill = $billRepository->find($id);
        if ($bill) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($bill);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Bill not found.');
        }
        return $this->redirectToRoute('bill');
    }
    #[Route('/dashboard/bill/update{id}', name: 'updateBill')]
    public function updateBill(int $id, CarRepository $carRepository, BillRepository $billRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $error= $carRepository->areAllNotAvailable();
        $bill = $billRepository->find($id);
        if (!$bill) {
            throw $this->createNotFoundException('Bill not found');
        }
        $form = $this->createForm(FormUpdateBillType::class, $bill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($bill->getStatusBill() == 1){
                $car = $carRepository->find($bill->getCar()->getIdCar());
                if($car){
                    $car->setStatusCar('sold');
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($car);
                    $entityManager->flush();
                }
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($bill);
            $entityManager->flush();
            return $this->redirectToRoute('bill');
        }
        return $this->render('backend/Warehouse/UpdateBill.html.twig', [
            'billupdateform' => $form->createView(),
            'bill' => $bill,
            'error' => $error,
        ]);
    }
    #[Route('/dashboard/bill/paid_bills', name: 'filterPaidBills')]
    public function filterPaidBills(BillRepository $billRepository): JsonResponse{
        $bills = $billRepository->filterByPaidBills();
        if (!$bills) {
            return new JsonResponse(['error' => 'No paid bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/unpaid_bills', name: 'filterUnpaidBills')]
    public function filterUnpaidBills(BillRepository $billRepository): JsonResponse{
        $bills = $billRepository->filterByUnpaidBills();
        if (!$bills) {
            return new JsonResponse(['error' => 'No unpaid bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/search', name: 'searchBill', methods: ['POST'])]
    public function searchBill(Request $request, BillRepository $billRepository): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);

        $searchItem = $data['query'] ?? null;

        if (!$searchItem) {
            return new JsonResponse(['error' => 'No search item provided.'], Response::HTTP_BAD_REQUEST);
        }

        $bills = $billRepository->searchBill($searchItem);

        if (!$bills) {
            return new JsonResponse(['error' => 'No bills found.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/sort_date_asc', name: 'sortBillsByDateASC')]
    public function sortBillsByDateASC(BillRepository $billRepository): JsonResponse
    {
        $bills = $billRepository->sortBillsByDateASC();
        if (!$bills) {
            return new JsonResponse(['error' => 'No bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/sort_date_desc', name: 'sortBillsByDateDESC')]
    public function sortBillsByDateDESC(BillRepository $billRepository): JsonResponse
    {
        $bills = $billRepository->sortBillsByDateDESC();
        if (!$bills) {
            return new JsonResponse(['error' => 'No bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/sort_amount_asc', name: 'sortBillsByAmountASC')]
    public function sortBillsByAmountASC(BillRepository $billRepository): JsonResponse
    {
        $bills = $billRepository->sortBillsByTotalAmountASC();
        if (!$bills) {
            return new JsonResponse(['error' => 'No bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    #[Route('/dashboard/bill/sort_amount_desc', name: 'sortBillsByAmountDESC')]
    public function sortBillsByAmountDESC(BillRepository $billRepository): JsonResponse
    {
        $bills = $billRepository->sortBillsByTotalAmountDESC();
        if (!$bills) {
            return new JsonResponse(['error' => 'No bills found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($bills, Response::HTTP_OK, [], ['groups' => 'bill:read']);
    }
    private function createIncomeChart($chartBuilder, $year, $income){
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            'datasets' => [
                [
                    'label' => 'Income (DT)',
                    'backgroundColor' => 'rgba(78, 115, 223, 0.05)',
                    'borderColor' => 'rgba(78, 115, 223, 1)',
                    'pointRadius' => 3,
                    'pointBackgroundColor' => 'rgba(78, 115, 223, 1)',
                    'pointBorderColor' => 'rgba(78, 115, 223, 1)',
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => 'rgb(255, 0, 0)',
                    'pointHoverBorderColor' => 'rgb(255, 0, 0)',
                    'data' => array_values($income),
                    'fill' => false,
                    'tension' => 0.3,
                ]
            ]
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false, 
            'responsive' => true,  
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 50000,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Monthly Income for '.$year,
                ],
            ],
        ]);
    return $chart;
    }
    private function createRentedSoldPieChart($chartBuilder, $year, $incomeSold, $incomeRented){
        $pieChart = $chartBuilder->createChart(Chart::TYPE_PIE);

        $pieChart->setData([
            'labels' => ['Sold Cars', 'Rented Cars'],
            'datasets' => [[
                'label' => 'Income Distribution',
                'backgroundColor' => ['#4e73df', '#1cc88a'],
                'hoverBackgroundColor' => ['#2e59d9', '#17a673'],
                'borderColor' => '#ffffff',
                'data' => [$incomeSold, $incomeRented],
            ]]
        ]);

        $pieChart->setOptions([
            'maintainAspectRatio' => false, 
            'responsive' => true,  
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Income: Sold vs Rented Cars ('.$year.')',
                ],
            ],
        ]);
        return $pieChart;
    }
}