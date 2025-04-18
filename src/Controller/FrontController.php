<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/Front', name: 'Front')]
    public function index(): Response
    {
        return $this->render('frontend/baseFront.html.twig', [
            'user' => $this->getUser() // Pass the user object to the template
        ]);
    }
    //Every link of page is placed here
    //#[Route('/entity_name', name: 'your_entity_name')]
}
?>