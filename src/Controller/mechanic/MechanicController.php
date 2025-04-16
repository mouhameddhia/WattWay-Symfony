<?php

namespace App\Controller\mechanic;

use App\Entity\Mechanic;
use App\Form\MechanicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/mechanic')]
final class MechanicController extends AbstractController
{
    #[Route(name: 'app_mechanic_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();

        return $this->render('backend/mechanic/index.html.twig', [
            'mechanics' => $mechanics,
        ]);
    }
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new', name: 'app_mechanic_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mechanic = new Mechanic();
        $form = $this->createForm(MechanicType::class, $mechanic);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload from the template (not the form)
            $uploadedFile = $request->files->get('mechanic_image');
            
            if ($uploadedFile) {
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('mechanics_directory'),
                    $newFilename
                );
                $mechanic->setImgMechanic($newFilename);
            }
    
            $this->entityManager->persist($mechanic);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_mechanic_index');
        }
    
        return $this->render('backend/mechanic/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idMechanic}', name: 'app_mechanic_show', methods: ['GET'])]
    public function show(Mechanic $mechanic): Response
    {
        return $this->render('backend/mechanic/show.html.twig', [
            'mechanic' => $mechanic,
        ]);
    }

    #[Route('/{idMechanic}/edit', name: 'app_mechanic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mechanic $mechanic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MechanicType::class, $mechanic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload separately
            $uploadedFile = $request->files->get('mechanic_image');
            
            if ($uploadedFile instanceof UploadedFile) {
                // Remove old file if exists
                if ($mechanic->getImgMechanic()) {
                    $oldFile = $this->getParameter('mechanics_directory').'/'.$mechanic->getImgMechanic();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('mechanics_directory'),
                    $newFilename
                );
                $mechanic->setImgMechanic($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_mechanic_index');
        }

        return $this->render('backend/mechanic/edit.html.twig', [
            'mechanic' => $mechanic,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idMechanic}', name: 'app_mechanic_delete', methods: ['POST'])]
    public function delete(Request $request, Mechanic $mechanic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mechanic->getIdMechanic(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mechanic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mechanic_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/frontend/mechanic', name: 'app_frontend_mechanic_index', methods: ['GET'])]
    public function frontendIndex(EntityManagerInterface $entityManager): Response
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
