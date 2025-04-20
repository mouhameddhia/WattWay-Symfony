<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormAddCarType;
use App\Entity\Car;
use App\Entity\Bill;
use App\Form\FormUpdateBillType;
use App\Repository\BillRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\AddCarFormType;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;

class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(FeedbackRepository $feedbackRepository,SluggerInterface $slugger, CarRepository $carRepository, BillRepository $billRepository, UserRepository $userRepository , WarehouseRepository $warehouseRepository, ManagerRegistry $doctrine, Request $request): Response
    {
        // CAR CONTROLLER
        $car = new Car();
        $bill= new Bill();
        $cars = $carRepository->availableCars();
        $formCar = $this->createForm(FormAddCarType::class);
        $formCar->remove('priceCar');
        $formCar->remove('statusCar');
        $formCar->remove('warehouse');
        $formCar->handleRequest($request);
        if ($request->isMethod('POST') && $request->request->has('car_id')) {
            try {
                $carId = $request->request->get('car_id');
                $car = $carRepository->find($carId);
    
                if (!$car) {
                    return $this->json(['error' => 'Car not found'], 404);
                }
                $bill = new Bill();
                $bill->setStatusBill(0);
                $bill->setDateBill(new \DateTime());
                $bill->setTotalAmountBill(($car->getPriceCar() * 1.08) + 1500);
                $bill->setCar($car);
                $bill->setUser($userRepository->getLoggedInUser($this->getUser()->getUserIdentifier()));
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($bill);
                $entityManager->flush();
    
                return $this->json([
                    'success' => true,
                    'message' => 'Purchase successful',
                ]);
    
            } catch (\Exception $e) {
                return $this->json([
                    'error' => 'Server error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        if ($request->isMethod('POST') && $request->request->has('deleteBill')) {
            $idBill = $request->request->get('deleteBill');
            $bill = $billRepository->find($idBill);
    
            if (!$bill) {
                return $this->json([
                    'success' => false,
                    'error' => 'Bill not found',
                ], 404);
            }
    
            $entityManager = $doctrine->getManager();
            $entityManager->remove($bill);
            $entityManager->flush();
    
            // Always return JSON for AJAX requests
            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => true,
                    'message' => 'Bill deleted successfully',
                ]);
            }
    
            return $this->redirectToRoute('Front');
        }
        if($request->isMethod('POST') && $request->request->has('payBill')){
            $idCar = $request->request->get('payBill');
            $car = $carRepository->find($idCar);  
            $idBill = $billRepository->getBillIdByCarUserId($idCar,$userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getEmailUser());
            $bill = $billRepository->find($idBill);
            $billRepository->deleteAllPendingBillsForCarIdExcept($idCar,$userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getEmailUser());
            if (!$bill) {
                throw $this->createNotFoundException('Bill not found');
            }
            $entityManager = $doctrine->getManager();
            $bill->setStatusBill(1);
            $car->setStatusCar('sold');
            $entityManager->persist($bill);
            $entityManager->flush();          
        }
        if($request->isMethod('POST')&& $request->request->has('payBillRent')){
            $bill = new Bill();
            $idCar = $request->request->get('payBillRent');
            $billRepository->deleteAllPendingBillsForCarIdExcept($idCar,$userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getEmailUser());
            $totalAmountBill = $request->request->get('priceRent');
            $car = $carRepository->find($idCar);
            $entityManager = $doctrine->getManager();
            $bill->setDateBill(new \DateTime());
            $bill->setCar($car);
            $bill->setUser($userRepository->getLoggedInUser($this->getUser()->getUserIdentifier()));
            $bill->setTotalAmountBill($totalAmountBill);
            $bill->setStatusBill(1);
            $car->setStatusCar('rented');
            $entityManager->persist($bill);
            $entityManager->flush();
        }
        if ($formCar->isSubmitted() && $formCar->isValid()) {
            $imageFile = $formCar->get('imgCar')->getData();
            $car = $formCar->getData();
            $car->setStatusCar('under repair');
            $car->setPriceCar(0);
            $car->setWarehouse($warehouseRepository->find(69));
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
            }
            $car->setImgCar($newFilename);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($car);
            $entityManager->flush();
        }
        // FEEDBACK CONTROLLER
        $feedbacks = $feedbackRepository->findLatestFeedbacks(5); // Get 5 latest feedbacks
        return $this->render('frontend/baseFront.html.twig', [
            'cars' =>$cars,
            'repaircarform' => $formCar->createView(),
            'user' => $this->getUser(),
            'feedbacks' => $feedbacks,
        ]);
    }
    
}
?>