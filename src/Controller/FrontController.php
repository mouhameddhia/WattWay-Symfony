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
    //Every link of page is placed here
    //#[Route('/entity_name', name: 'your_entity_name')]
}
?>