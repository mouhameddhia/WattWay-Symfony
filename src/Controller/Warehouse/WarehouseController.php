<?php

namespace App\Controller\Warehouse;

use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormAddWarehouseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpClient\HttpClient;

class WarehouseController extends AbstractController
{
    
    #[Route('/dashboard/warehouse', name: 'warehouse')]
    public function listWarehouses(WarehouseRepository $warehouseRepository, EntityManagerInterface $em,Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $warehouse=new Warehouse();
        $form = $this->createForm(FormAddWarehouseType::class, $warehouse);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        if ($form->isSubmitted() && $form->isValid() && !$warehouseRepository->addressExists($warehouse)) { 
            $entityManager->persist($warehouse);
            $entityManager->flush();
            return $this->redirectToRoute('warehouse');
        }
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $query = $em->createQuery('SELECT b FROM App\Entity\Warehouse b ORDER BY b.idWarehouse DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit);

        $paginator = new Paginator($query);
        $totalCars = count($paginator);
        $totalPages = ceil($totalCars / $limit);
        return $this->render('backend/Warehouse/Warehouse.html.twig', [
            'warehouses'=> $paginator,
            'warehouseform'=> $form->createView(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'error' => $warehouseRepository->addressExists($warehouse),
        ]);
    }
    #[Route('/dashboard/warehouse/delete{id}', name: 'deleteWarehouse')]
    public function deleteWarehouse(int $id, WarehouseRepository $warehouseRepository, PersistenceManagerRegistry $doctrine): Response
    {
        $warehouse = $warehouseRepository->find($id);
        if ($warehouse) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($warehouse);
            $entityManager->flush();
        }
        return $this->redirectToRoute('warehouse');
    }
    #[Route('/dashboard/warehouse/update{id}', name: 'updateWarehouse')]
    public function updateWarehouse(int $id, WarehouseRepository $warehouseRepository, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $error=false;
        $warehouse = $warehouseRepository->find($id);
        if (!$warehouse) {
            throw $this->createNotFoundException('Warehouse not found');
        }
        $form = $this->createForm(FormAddWarehouseType::class, $warehouse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !$warehouseRepository->addressExists($warehouse)) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($warehouse);
            $entityManager->flush();
            return $this->redirectToRoute('warehouse');
        }
        else if ($form->isSubmitted() && $form->isValid() && $warehouseRepository->addressExists($warehouse)) {
            $error=true;
        }
        return $this->render('backend/Warehouse/updateWarehouse.html.twig', [
            'warehouseupdateform' => $form->createView(),
            'warehouse' => $warehouse,
            'error' => $error,
        ]);
    }
    #[Route('/dashboard/warehouse/capacity_asc', name: 'sortByCapacityASC')]
    public function sortByCapacityASC(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->sortWarehouseByCapacityASC();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/capacity_desc', name: 'sortByCapacityDESC')]
    public function sortByCapacityDESC(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->sortWarehouseByCapacityDESC();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/city_asc', name: 'sortByCityASC')]
    public function sortByCityASC(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->sortWarehouseByCityASC();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/city_desc', name: 'sortByCityDESC')]
    public function sortByCityDESC(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->sortWarehouseByCityDESC();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/storage', name: 'storageWarehouse')]
    public function storageWarehouse(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->storageWarehouse();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/repair', name: 'repairWarehouse')]
    public function repairWarehouse(WarehouseRepository $warehouseRepository): JsonResponse
    {
        $warehouses = $warehouseRepository->repairWarehouse();
        if (!$warehouses) {
            return $this->json(['message' => 'No warehouses found'], 404);
        }
        return $this->json($warehouses, 200, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/dashboard/warehouse/search', name: 'searchWarehouse', methods: ['POST'])]
    public function searchWarehouse(Request $request, WarehouseRepository $warehouseRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $searchItem = $data['query'] ?? null;
        if ($searchItem === null) {
            return new JsonResponse(['error' => 'No search item provided.'], Response::HTTP_BAD_REQUEST);
        }
        $warehouses = $warehouseRepository->searchWarehouse($searchItem);
        if (!$warehouses) {
            return new JsonResponse(['error' => 'No warehouses found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($warehouses, Response::HTTP_OK, [], ['groups' => 'warehouse:read']);
    }
    #[Route('/get-city-from-coordinates', name: 'get_city_from_coordinates', methods: ['POST'])]
public function getCityFromCoordinates(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $latitude = $data['latitude'] ?? null;
    $longitude = $data['longitude'] ?? null;

    if (!$latitude || !$longitude) {
        return $this->json([
            'success' => false,
            'message' => 'Latitude and longitude are required'
        ], 400);
    }

    try {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://nominatim.openstreetmap.org/reverse', [
            'query' => [
                'format' => 'json',
                'lat' => $latitude,
                'lon' => $longitude,
                'zoom' => 10,
                'addressdetails' => 1
            ],
            'headers' => [
                'User-Agent' => 'YourAppName/1.0 (your@email.com)'
            ]
        ]);

        $data = $response->toArray();

        if (isset($data['address'])) {
            $city = $data['address']['city'] ??
                   'Unknown location';

            return $this->json([
                'success' => true,
                'city' => $city
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Could not determine city from coordinates'
        ]);

    } catch (\Exception $e) {
        return $this->json([
            'success' => false,
            'message' => 'Error fetching location data: ' . $e->getMessage()
        ], 500);
    }
}
}