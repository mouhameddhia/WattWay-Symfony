<?php

namespace App\Controller\Api;

use App\Entity\Submission;
use App\Service\ML\PriorityScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PriorityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PriorityScoringService $priorityScoringService
    ) {
    }

    #[Route('/submission/{id}/predict-priority', name: 'api_submission_predict_priority', methods: ['POST'])]
    public function predictPriority(int $id): JsonResponse
    {
        $submission = $this->entityManager->getRepository(Submission::class)->find($id);
        
        if (!$submission) {
            return $this->json(['error' => 'Submission not found'], 404);
        }

        try {
            $predictedPriority = $this->priorityScoringService->predictPriority($submission);
            
            return $this->json([
                'success' => true,
                'predicted_priority' => $predictedPriority
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Error predicting priority: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/submission/{id}/update-priority', name: 'api_submission_update_priority', methods: ['POST'])]
    public function updatePriority(int $id, Request $request): JsonResponse
    {
        $submission = $this->entityManager->getRepository(Submission::class)->find($id);
        
        if (!$submission) {
            return $this->json(['error' => 'Submission not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['priority'])) {
            return $this->json(['error' => 'Priority not provided'], 400);
        }

        try {
            $submission->setUrgencyLevel($data['priority']);
            $this->entityManager->flush();
            
            return $this->json([
                'success' => true,
                'message' => 'Priority updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Error updating priority: ' . $e->getMessage()
            ], 500);
        }
    }
} 