<?php

namespace App\Controller\assignment;

use App\Entity\Assignment;
use App\Form\AssignmentType;
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assignment);
            $entityManager->flush();

            return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/assignment/new.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/assignment/edit.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
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
