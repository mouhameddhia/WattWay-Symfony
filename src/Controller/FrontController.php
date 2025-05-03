<?php

namespace App\Controller;

use App\Entity\Mechanic;
use App\Entity\Car;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;





class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();
            
        dump($mechanics); // Debug

        $userCars = $entityManager
            ->getRepository(Car::class)
            ->findBy(['user' => 1]);

        dump($userCars);

        return $this->render('frontend/baseFront.html.twig', [
            'mechanics' => $mechanics,
            'userCars' => $userCars,
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
/*
    #[Route('/Front', name: 'Front')]
    public function home(
        Connection $conn,
        UserRepository $userRepository
    ): Response {
        // 1) Dump your connection params:
        dump($conn->getParams());

        // 2) Run the same raw SQL you tried in MySQL:
        $rows = $conn->fetchAllAssociative(
            'SELECT * FROM car WHERE idUser = ?', 
            [1]
        );
        dump($rows);

        dd(); // stop here so you can read the dumps

        // … your normal logic …
        $userCars = $userRepository->find(1)?->getCars() ?? [];
        return $this->render('front/baseFront.html.twig', [
            'userCars' => $userCars,
        ]);
    }
        */
    //Every link of page is placed here
    //#[Route('/entity_name', name: 'your_entity_name')]
}
?>