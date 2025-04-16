<?php

namespace App\Controller;

use App\Entity\Mechanic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();
            
        dump($mechanics); // Debug

        return $this->render('frontend/baseFront.html.twig', [
            'mechanics' => $mechanics,
        ]);
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

    #[Route('/car-status', name: 'app_car_status')]
    public function carStatus(EntityManagerInterface $entityManager): Response
    {
        // For now, we'll just pass an empty array
        // Later, we'll implement the logic to fetch user's cars and their assignments
        $userCars = [];
        
        return $this->render('frontend/car_status/index.html.twig', [
            'userCars' => $userCars,
        ]);
    }

    //Every link of page is placed here
    //#[Route('/entity_name', name: 'your_entity_name')]
}
?>