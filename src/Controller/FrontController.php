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
use App\Entity\Mechanic;
use App\Entity\Submission;
use App\Form\FormUpdateBillType;
use Doctrine\DBAL\Connection;
use App\Repository\BillRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\AddCarFormType;
use App\Form\FrontSubmissionType;
use App\Repository\FeedbackRepository;
use App\Repository\ResponseRepository;
use App\Repository\SubmissionRepository;
use App\Service\PDFService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SendGrid;
use SendGrid\Mail\Mail;
use App\Repository\ItemRepository;
use App\Entity\Order;
use App\Entity\Item;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManager;

class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(EntityManagerInterface $entityManager,ItemRepository $itemRepository, OrderRepository $orderRepository, SubmissionRepository $submissionRepository, ResponseRepository $responseRepository,PDFService $pdfService, HttpClientInterface $client, FeedbackRepository $feedbackRepository, SluggerInterface $slugger, CarRepository $carRepository, BillRepository $billRepository, UserRepository $userRepository , WarehouseRepository $warehouseRepository, ManagerRegistry $doctrine, Request $request): Response
    {
        // CAR CONTROLLER
        $car = new Car();
        $bill= new Bill();
        //DEFAULT CAR CATALOGUE
        $brand='all';$city='all';$sliderValue=$carRepository->maxPriceCar();$direction='ASC';
        $cars = $carRepository->getSliderCars($brand,$city,$sliderValue,$direction);
        //
        $formCar = $this->createForm(FormAddCarType::class);
        $formCar->remove('priceCar');
        $formCar->remove('statusCar');
        $formCar->remove('warehouse');
        $formCar->handleRequest($request);
        //Make this yours button implementation
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
        //Cancel button implementation
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
        // Proceed button implementation
        if($request->isMethod('POST') && $request->request->has('payBill')){
            $idCar = $request->request->get('payBill');
            $car = $carRepository->find($idCar);  
            $user=$userRepository->getLoggedInUser($this->getUser()->getUserIdentifier());
            $idBill = $billRepository->getBillIdByCarUserId($idCar,$user->getEmailUser());
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
            $this->addFlash('success','A '.$car->getBrandCar().' '.$car->getModelCar().' was purchased for '.$bill->getTotalAmountBill().'DT by the client '.$user->getFirstNameUser().' '.$user->getLastNameUser());
            $pdfUrl = $pdfService->generatePDF($idBill, $bill->getDateBill()->format('Y-m-d'), $car->getBrandCar()." ".$car->getModelCar(), $bill->getTotalAmountBill(), $user->getFirstNameUser(), $user->getAddress(), $user->getPhoneNumber());

            if ($pdfUrl) {
                $templateData=['brand_car'=> $car->getBrandCar(),
                'model_car' => $car->getModelCar(),
                'car_url' => $pdfUrl];
                $this->sendCarNotification($user->getEmailUser(),$user->getLastNameUser(),$templateData);
                return $this->redirect($pdfUrl);
            }
            return new Response('Failed to generate PDF.', Response::HTTP_INTERNAL_SERVER_ERROR);          
        }
        // Take this for a drive button implementation
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
        //Repair car implementation
        if ($formCar->isSubmitted() && $formCar->isValid()) {
            $imageFile = $formCar->get('imgCar')->getData();
            $car = $formCar->getData();
            $car->setStatusCar('under repair');
            $car->setPriceCar(0);
            $car->setWarehouse($warehouseRepository->find(69));
            $car->setUser($userRepository->getLoggedInUser($this->getUser()->getUserIdentifier()));
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
        //City API determination via OpenStreet
        if ($request->isXmlHttpRequest() && $request->request->has('lat') && $request->request->has('lon')) {
            $lat = $request->request->get('lat');
            $lon = $request->request->get('lon');
    
            $apiUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon&zoom=10&addressdetails=1&accept-language=en";
    
            $response = $client->request('GET', $apiUrl, [
                'headers' => [
                    'User-Agent' => 'SymfonyApp'
                ]
            ]);
    
            $data = $response->toArray();
    
            $city = $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null;
    
            return new JsonResponse(['city' => $city]);
        }
        //Basic features for car catalogue (AJAX)
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $brand = $request->request->get('brand');
            $city = $request->request->get('city');
            $sliderValue = $request->request->get('sliderValue');
            $direction = $request->request->get('direction');
            $filteredCars = $carRepository->getSliderCars($brand, $city, $sliderValue, $direction);
            $html=$this->renderView('frontend/Warehouse/filteredCars.html.twig', [
                'cars' => $filteredCars,
            ]);
            return $this->json([
                'success' => true,
                'html' => $html
            ]);
        }
        // FEEDBACK CONTROLLER
        $feedbacks = $feedbackRepository->findLatestFeedbacks(5); // Get 5 latest feedbacks
        //SUBMISSION CONTROLLER
        $submissions = $submissionRepository->findAll();
        $submissionsWithResponses = [];
        
        foreach ($submissions as $submission) {
            $responses = $responseRepository->findBySubmissionId($submission->getIdSubmission());
            $submissionsWithResponses[] = [
                'idSubmission' => $submission->getIdSubmission(),
                'status' => $submission->getStatus(),
                'urgencyLevel' => $submission->getUrgencyLevel(),
                'description' => $submission->getDescription(),
                'dateSubmission' => $submission->getDateSubmission(),
                'preferredContactMethod' => $submission->getPreferredContactMethod(),
                'preferredAppointmentDate' => $submission->getPreferredAppointmentDate(),
                'responses' => $responses
            ];
        }

        $submission = new Submission();
        $formSubmission = $this->createForm(FrontSubmissionType::class, $submission);

        //ORDER CONTROLLER
        // Fetch all items from the database
        $items = $itemRepository->findAll();

        // Divide items into client and admin items
        $dividedItems = $this->divideItemsByAdmin($items, $orderRepository);

        // Calculate total quantities for each item
        $itemQuantities = $this->calculateItemQuantities($dividedItems['clientItems'], $dividedItems['adminItems']);
        // MECHANIC CONTROLLER
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();
            
        dump($mechanics); // Debug

        $userCars = $entityManager
            ->getRepository(Car::class)
            ->findBy(['user' => 68]);

        dump($userCars);
        return $this->render('frontend/baseFront.html.twig', [
            'mechanics' => $mechanics,
            'userCars' => $userCars,
            'items' => $items,
            'itemQuantities' => $itemQuantities,
            'clientItems' => $dividedItems['clientItems'],
            'adminItems' => $dividedItems['adminItems'],
            'submissions' => $submissionsWithResponses,
            'formSubmission' => $formSubmission->createView(),
            'cars' =>$cars,
            'maximumCarPrice'=>$carRepository->maxPriceCar(),
            'minimumCarPrice'=>$carRepository->minPriceCar(),
            'brands'=>$carRepository->getAllBrands(),
            'repaircarform' => $formCar->createView(),
            'user' => $this->getUser(),
            'feedbacks' => $feedbacks,
        ]);
    }
    private function sendCarNotification(string $toEmail, string $toName, array $templateData): void
{
    $email = new Mail();
    $email->setFrom("haroun.zriba@esprit.tn", "Wattway Bill Services");
    $email->addTo($toEmail, $toName);
    $email->setTemplateId($_ENV['SENDGRID_TEMPLATE_ID']); // or use parameter
    $email->addDynamicTemplateDatas($templateData);

    $sendgrid = new SendGrid($_ENV['SENDGRID_API_KEY']);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() >= 400) {
            throw new \Exception("SendGrid failed: " . $response->body());
        }

    } catch (\Exception $e) {
        // Handle logging or rethrow
        throw $e;
    }
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
        #[Route('/mechanics', name: 'app_front_mechanics')]
    public function mechanics(EntityManagerInterface $entityManager): Response
    {
        // Debug to check if route is hit
        dump('Route hit!');
        
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();
            
        // Debug to check mechanics data
        dump($mechanics);

        return $this->render('frontend/mechanic/index.html.twig', [
            'mechanics' => $mechanics,
        ]);
    }
    
}
?>
