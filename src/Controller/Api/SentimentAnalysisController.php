<?php

namespace App\Controller\Api;

use App\DTO\SentimentAnalysisRequest;
use App\Service\SentimentAnalysisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;

#[Route('/api')]
class SentimentAnalysisController extends AbstractController
{
    public function __construct(
        private readonly SentimentAnalysisService $sentimentAnalysisService,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/sentiment/analyze', name: 'api_sentiment_analyze', methods: ['POST'])]
    public function analyze(Request $request): JsonResponse
    {
        try {
            $this->logger->info('Received sentiment analysis request');
            
            $content = $request->getContent();
            $this->logger->debug('Request content: ' . $content);
            
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON in request body: ' . json_last_error_msg());
            }
            
            if (!isset($data['text'])) {
                throw new \InvalidArgumentException('Missing text parameter in request');
            }
            
            $sentimentRequest = new SentimentAnalysisRequest();
            $sentimentRequest->text = $data['text'];
            
            $this->logger->info('Processing sentiment analysis for text: ' . substr($sentimentRequest->text, 0, 100) . '...');
            
            try {
                $result = $this->sentimentAnalysisService->analyze($sentimentRequest);
                $this->logger->info('Sentiment analysis completed successfully');
                return $this->json($result);
            } catch (TransportException $e) {
                $this->logger->error('Failed to connect to Flask service: ' . $e->getMessage());
                return $this->json([
                    'error' => 'Failed to connect to sentiment analysis service. Please ensure the Flask service is running.'
                ], 503);
            } catch (\Exception $e) {
                $this->logger->error('Error in sentiment analysis service: ' . $e->getMessage());
                return $this->json([
                    'error' => 'An error occurred while processing your request: ' . $e->getMessage()
                ], 500);
            }
        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Invalid request: ' . $e->getMessage());
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error: ' . $e->getMessage());
            return $this->json([
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
} 