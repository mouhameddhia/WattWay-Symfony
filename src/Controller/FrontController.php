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

class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(CarRepository $carRepository, BillRepository $billRepository, UserRepository $userRepository , WarehouseRepository $warehouseRepository, ManagerRegistry $doctrine, Request $request): Response
    {
        
        
        return $this->render('frontend/baseFront.html.twig');
    }
    //Every link of page is placed here
    //#[Route('/entity_name', name: 'your_entity_name')]
}
?>