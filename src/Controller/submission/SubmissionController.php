<?php

namespace App\Controller\submission;

use App\Entity\Submission;
use App\Form\SubmissionType;
use App\Repository\SubmissionRepository;
use App\Service\ML\PriorityScoringService;
use App\Services\KeyTermExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use MercurySeries\FlashyBundle\FlashyNotifier;

#[Route('/dashboard/submission')]
final class SubmissionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PriorityScoringService $priorityScoringService,
        private FlashyNotifier $flashy,
        private KeyTermExtractor $keyTermExtractor
    ) {
    }

    #[Route('/', name: 'app_submission_index', methods: ['GET'])]
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
            // Predict priority before saving
            try {
                $predictedPriority = $this->priorityScoringService->predictPriority($submission);
                $submission->setUrgencyLevel($predictedPriority);
            } catch (\Exception $e) {
                $this->flashy->warning('Could not predict priority: ' . $e->getMessage());
            }

            $entityManager->persist($submission);
            $entityManager->flush();
            $this->flashy->success('Submission created successfully');
            return $this->redirectToRoute('app_submission_index');
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
            // Predict priority before saving
            try {
                $predictedPriority = $this->priorityScoringService->predictPriority($submission);
                $submission->setUrgencyLevel($predictedPriority);
            } catch (\Exception $e) {
                $this->flashy->warning('Could not predict priority: ' . $e->getMessage());
            }

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
        if ($this->isCsrfTokenValid('delete'.$submission->getIdSubmission(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($submission);
                $entityManager->flush();
                $this->flashy->success('Submission removed successfully');
            } catch (\Exception $e) {
                $this->flashy->info('Please try again in a moment');
            }
        } else {
            $this->flashy->info('Please refresh the page and try again');
        }

        return $this->redirectToRoute('app_submission_index');
    }

    #[Route('/dashboard/submission/kanban', name: 'app_submission_kanban', methods: ['GET'])]
    public function kanban(SubmissionRepository $submissionRepository): Response
    {
        $submissions = [
            'pending' => $submissionRepository->findBy(['status' => 'PENDING']),
            'approved' => $submissionRepository->findBy(['status' => 'APPROVED']),
            'responded' => $submissionRepository->findBy(['status' => 'RESPONDED'])
        ];

        $chartData = $submissionRepository->getChartData();
        $detailedStats = $submissionRepository->getDetailedStatistics();
        $burnDownData = $submissionRepository->getBurnDownData();

        // Create burn-down chart using CMEN GoogleChartsBundle
        $lineChart = new LineChart();
        $lineChart->getData()->setArrayToDataTable($burnDownData);
        
        $lineChart->getOptions()->setTitle('Work Progress');
        $lineChart->getOptions()->setCurveType('function');
        $lineChart->getOptions()->setLineWidth(4);
        $lineChart->getOptions()->setHeight(500);
        $lineChart->getOptions()->setWidth(900);
        $lineChart->getOptions()->getTitleTextStyle()->setBold(true);
        $lineChart->getOptions()->getTitleTextStyle()->setColor('#333');
        $lineChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $lineChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        
        $lineChart->getOptions()->getHAxis()->setTitle('Date');
        $lineChart->getOptions()->getVAxis()->setTitle('Number of Submissions');
        $lineChart->getOptions()->getVAxis()->setMinValue(0);

        return $this->render('backend/submission/kanban.html.twig', [
            'submissions' => $submissions,
            'chartData' => $chartData,
            'detailedStats' => $detailedStats,
            'lineChart' => $lineChart
        ]);
    }

    #[Route('/{idSubmission}/status', name: 'app_submission_update_status', methods: ['PUT'])]
    public function updateStatus(Request $request, Submission $submission, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['status'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Status is required'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validate the status value
            $validStatuses = ['PENDING', 'APPROVED', 'RESPONDED'];
            if (!in_array($data['status'], $validStatuses)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Invalid status value'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Update the status
            $submission->setStatus($data['status']);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'newStatus' => $submission->getStatus()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Failed to update status: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{idSubmission}/predict-priority', name: 'app_submission_predict_priority', methods: ['POST'])]
    public function predictPriority(Submission $submission): Response
    {
        try {
            $predictedPriority = $this->priorityScoringService->predictPriority($submission);
            
            return $this->json([
                'success' => true,
                'predicted_priority' => $predictedPriority
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Error predicting priority: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{idSubmission}/update-priority', name: 'app_submission_update_priority', methods: ['POST'])]
    public function updatePriority(Request $request, Submission $submission, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!isset($data['priority'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Priority is required'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validate the priority value
            $validPriorities = ['LOW', 'MEDIUM', 'HIGH'];
            if (!in_array($data['priority'], $validPriorities)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Invalid priority value'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Update the priority
            $submission->setUrgencyLevel($data['priority']);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Priority updated successfully',
                'newPriority' => $submission->getUrgencyLevel()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Failed to update priority: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{idSubmission}/extract-keyterms', name: 'app_submission_extract_keyterms', methods: ['GET'])]
    public function extractKeyTerms(Submission $submission): Response
    {
        try {
            if (!$submission->getDescription()) {
                return $this->json([
                    'success' => false,
                    'error' => 'No description found for this submission'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Extract key terms
            $result = $this->keyTermExtractor->extract($submission->getDescription());
            
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Failed to extract key terms: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $submission = new Submission();
        $form = $this->createForm(SubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($submission);
                $entityManager->flush();

                $this->addFlash('success', 'Submission created successfully');
                return $this->redirectToRoute('app_submission_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating submission: ' . $e->getMessage());
            }
        }

        return $this->render('backend/submission/new.html.twig', [
            'submission' => $submission,
            'form' => $form->createView(),
        ]);
    }
}