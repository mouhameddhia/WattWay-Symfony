<?php

namespace App\Controller\submission;

use App\Entity\Submission;
use App\Form\SubmissionType;
use App\Repository\SubmissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/submission')]
final class SubmissionController extends AbstractController
{
    #[Route(name: 'app_submission_index', methods: ['GET'])]
    public function index(SubmissionRepository $submissionRepository, Request $request): Response
    {
        $status = $request->query->get('status');
        $urgency = $request->query->get('urgency');
        $keyword = $request->query->get('keyword');

        return $this->render('backend/submission/index.html.twig', [
            'submissions' => $submissionRepository->findByFilters($status, $urgency, $keyword),
        ]);
    }

    #[Route('/new', name: 'app_submission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $submission = new Submission();
        $form = $this->createForm(SubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($submission);
            $entityManager->flush();

            return $this->redirectToRoute('app_submission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/submission/new.html.twig', [
            'submission' => $submission,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubmission}', name: 'app_submission_show', methods: ['GET'])]
    public function show(Submission $submission): Response
    {
        return $this->render('backend/submission/show.html.twig', [
            'submission' => $submission,
        ]);
    }

    #[Route('/{idSubmission}/edit', name: 'app_submission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Submission $submission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_submission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/submission/edit.html.twig', [
            'submission' => $submission,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubmission}', name: 'app_submission_delete', methods: ['POST'])]
    public function delete(Request $request, Submission $submission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$submission->getIdSubmission(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($submission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_submission_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idSubmission}/export-pdf', name: 'app_submission_export_pdf', methods: ['GET'])]
    public function exportPdf(Submission $submission, Dompdf\Dompdf $dompdf): Response
    {
        $html = $this->renderView('backend/submission/export_pdf.html.twig', [
            'submission' => $submission
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="submission-'.$submission->getIdSubmission().'.pdf"'
        ]);
    }
}
