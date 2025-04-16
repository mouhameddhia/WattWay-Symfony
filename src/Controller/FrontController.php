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
        return $this->render('frontend/baseFront.html.twig');
    }
}