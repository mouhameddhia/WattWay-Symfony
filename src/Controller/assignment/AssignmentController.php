<?php

namespace App\Controller\assignment;

use App\Entity\Assignment;
use App\Form\AssignmentType;
use App\Entity\Mechanic;
use App\Entity\AssignmentMechanics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assignment')]
final class AssignmentController extends AbstractController
{
    #[Route(name: 'app_assignment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $assignments = $entityManager
            ->getRepository(Assignment::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.car', 'c')
            ->leftJoin('a.assignmentMechanics', 'am')
            ->leftJoin('am.idMechanic', 'm')
            ->addSelect('c')
            ->addSelect('am')
            ->addSelect('m')
            ->orderBy('a.dateAssignment', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('backend/assignment/index.html.twig', [
            'assignments' => $assignments,
        ]);
    }

    #[Route('/new', name: 'app_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assignment = new Assignment();
        $form = $this->createForm(AssignmentType::class, $assignment);
        $mechanics = $entityManager->getRepository(Mechanic::class)->findAll();
        
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            file_put_contents('debug.txt', print_r($postData, true));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle mechanics selection manually
            $selectedMechanicIds = $request->request->all()['assignment']['mechanics'] ?? [];

            // First persist the assignment to get an ID
            $entityManager->persist($assignment);
            $entityManager->flush();

            // Clear existing mechanics (in case of form resubmission)
            foreach ($assignment->getAssignmentMechanics() as $am) {
                $assignment->removeAssignmentMechanic($am);
                $entityManager->remove($am);
            }

            // Add selected mechanics
            foreach ($selectedMechanicIds as $mechanicId) {
                $mechanic = $entityManager->getRepository(Mechanic::class)->find($mechanicId);
                if ($mechanic) {
                    $assignmentMechanic = new AssignmentMechanics();
                    $assignmentMechanic->setIdMechanic($mechanic);
                    $assignmentMechanic->setIdAssignment($assignment);
                    $assignment->addAssignmentMechanic($assignmentMechanic);
                    $entityManager->persist($assignmentMechanic);
                }
            }

            // Flush again to save the mechanics
            $entityManager->flush();
            
            return $this->redirectToRoute('app_assignment_index');
        }

        return $this->render('backend/assignment/new.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
            'mechanics' => $mechanics
        ]);
    }

    #[Route('/{idAssignment}', name: 'app_assignment_show', methods: ['GET'])]
    public function show(Assignment $assignment): Response
    {
        return $this->render('backend/assignment/show.html.twig', [
            'assignment' => $assignment,
        ]);
    }

    #[Route('/{idAssignment}/edit', name: 'app_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assignment $assignment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssignmentType::class, $assignment);
        $mechanics = $entityManager->getRepository(Mechanic::class)->findAll();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle mechanics selection
            $selectedMechanicIds = $request->request->all()['assignment']['mechanics'] ?? [];
            
            // Clear existing mechanics
            foreach ($assignment->getAssignmentMechanics() as $am) {
                $assignment->removeAssignmentMechanic($am);
                $entityManager->remove($am);
            }
            
            // Add selected mechanics
            foreach ($selectedMechanicIds as $mechanicId) {
                $mechanic = $entityManager->getRepository(Mechanic::class)->find($mechanicId);
                if ($mechanic) {
                    $assignmentMechanic = new AssignmentMechanics();
                    $assignmentMechanic->setIdMechanic($mechanic);
                    $assignmentMechanic->setIdAssignment($assignment);
                    $assignment->addAssignmentMechanic($assignmentMechanic);
                    $entityManager->persist($assignmentMechanic);
                }
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/assignment/edit.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
            'mechanics' => $mechanics
        ]);
    }

    #[Route('/{idAssignment}', name: 'app_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, Assignment $assignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assignment->getIdAssignment(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($assignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}