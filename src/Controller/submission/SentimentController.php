<?php

namespace App\Controller\submission;

use App\Service\SentimentAnalysisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class SentimentController extends AbstractController
{
    #[Route('/api/analyze', name: 'analyze_sentiment', methods: ['POST'])]
    public function analyze(Request $request, SentimentAnalysisService $sentimentService): JsonResponse
    {
        // Get and validate input
        $data = json_decode($request->getContent(), true);
        $text = $data['text'] ?? '';

        $validator = Validation::createValidator();
        $violations = $validator->validate($text, [
            new NotBlank([
                'message' => 'Text cannot be empty'
            ])
        ]);

        if ($violations->count() > 0) {
            return $this->json([
                'error' => $violations[0]->getMessage()
            ], 400);
        }

        try {
            $result = $sentimentService->analyze($text);
            return $this->json($result);
            
        } catch (\RuntimeException $e) {
            return $this->json([
                'error' => 'Sentiment analysis failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}