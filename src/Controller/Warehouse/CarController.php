<?php

namespace App\Controller\Warehouse;

use App\Form\FormAddCarType;

use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\AddCarFormType;
use App\Entity\Car;
use App\Entity\Bill;
use App\Repository\WarehouseRepository;
use App\Repository\BillRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarController extends AbstractController
{
    #[Route('/dashboard/car', name: 'car')]
    public function listCars(Request $request, EntityManagerInterface $em, CarRepository $carRepository, WarehouseRepository $warehouseRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $car = new Car();
        $car->setStatusCar('available');
        $form = $this->createForm(FormAddCarType::class, $car);
        $form->remove('statusCar');
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid() && !$warehouseRepository->warehouseIsFull($car->getWarehouse())) {
        $imageFile = $form->get('imgCar')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('warehouse_images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                return $this->redirectToRoute('car');
            }
            $car->setImgCar($newFilename);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($car);
        $entityManager->flush();

        return $this->redirectToRoute('car', [
            'error' => false,
            'errorMessage' => 'Warehouse capacity exceeded',
        ]);
        }
        else if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('car', [
                'error' => true,
                'errorMessage' => 'Warehouse capacity exceeded',
            ]);
        }
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $query = $em->createQuery('SELECT b FROM App\Entity\Car b ORDER BY b.idCar DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit);

        $paginator = new Paginator($query);
        $totalCars = count($paginator);
        $totalPages = ceil($totalCars / $limit);

        return $this->render('backend/Warehouse/Car.html.twig', [
            'cars' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'carform' => $form->createView(),
            'error' => false,
        ]);
    }
    #[Route('/dashboard/car/delete{id}', name: 'deleteCar')]
    public function deleteCar(int $id, CarRepository $carRepository, ManagerRegistry $doctrine): Response
    {
        $car = $carRepository->find($id);
        if ($car) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($car);
            $entityManager->flush();
        }
        return $this->redirectToRoute('car');
    }
    #[Route('/dashboard/car/update{id}', name: 'updateCar')]
    public function updateCar(int $id, Request $request,CarRepository $carRepository, WarehouseRepository $warehouseRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $car = $carRepository->find($id);
        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }
        $form = $this->createForm(FormAddCarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !$warehouseRepository->warehouseIsFull($car->getWarehouse())) {
            $imageFile = $form->get('imgCar')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('warehouse_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                    return $this->redirectToRoute('updateCar', [
                        'id' => $id,
                        'error' => false,
                        'errorMessage' => 'Warehouse capacity exceeded',
                    ]);
                }
                $car->setImgCar($newFilename);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($car);
            $entityManager->flush();
            return $this->redirectToRoute('car');
        }
        else if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('updateCar', [
                'id' => $id,
                'error' => true,
                'errorMessage' => 'Warehouse capacity exceeded',
            ]);
        }
        return $this->render('backend/Warehouse/updateCar.html.twig',[
                'carupdateform' => $form->createView(),
                'car' => $car,
                'error' => false,
                'errorMessage' => 'Warehouse capacity exceeded',
            ]);
    }
    #[Route('/dashboard/car/available', name: 'availableCar')]
    public function availableCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->availableCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No available cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/not_available', name: 'notAvailableCar')]
    public function notAvailableCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->unavailableCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No unavailable cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/rented', name: 'rentedCar')]
    public function rentedCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->rentedCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No rented cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sold', name: 'soldCar')]
    public function soldCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->soldCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No sold cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/new', name: 'newCar')]
    public function newCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->newCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No new cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/used', name: 'usedCar')]
    public function usedCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->usedCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No used cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/under_repair', name: 'underRepairCar')]
    public function underRepairCars(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->underRepairCars();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars under repair found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_price_asc', name: 'sortCarsByPriceASC')]
    public function sortCarsByPriceASC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByPriceASC(); 
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_price_desc', name: 'sortCarsByPriceDESC')]
    public function sortCarsByPriceDESC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByPriceDESC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_kilometrage_asc', name: 'sortCarsByKilometrageASC')]
    public function sortCarsByKilometrageASC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByKilometrageASC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_kilometrage_desc', name: 'sortCarsByKilometrageDESC')]
    public function sortCarsByKilometrageDESC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByKilometrageDESC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_brand_model_asc', name: 'sortCarsByBrandModelASC')]
    public function sortCarsByBrandModelASC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByBrandModelASC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_brand_model_desc', name: 'sortCarsByBrandModelDESC')]
    public function sortCarsByBrandModelDESC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByBrandModelDESC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_year_asc', name: 'sortCarsByYearASC')]
    public function sortCarsByYearASC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByYearASC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/sort_year_desc', name: 'sortCarsByYearDESC')]
    public function sortCarsByYearDESC(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->sortCarsByYearDESC();
        if (!$cars) {
            return $this->json([
                'error' => 'No cars found',
            ], 404);
        }
        return $this->json($cars, 200, [], ['groups' => 'car:read']);
    }
    #[Route('/dashboard/car/search', name: 'searchCar', methods: ['POST'])]
    public function searchCar(Request $request, CarRepository $carRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $searchItem = $data['query'] ?? null;
        if ($searchItem === null) {
            return new JsonResponse(['error' => 'No search item provided.'], Response::HTTP_BAD_REQUEST);
        }
        $cars = $carRepository->searchCar($searchItem);
        if (!$cars) {
            return new JsonResponse(['error' => 'No cars found.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($cars, Response::HTTP_OK, [], ['groups' => 'car:read']);
    }

}
