<?php

namespace App\Service;

use App\DTO\SentimentAnalysisRequest;
use App\DTO\SentimentAnalysisResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Psr\Log\LoggerInterface;

class SentimentAnalysisService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger
    ) {}

    public function analyze(SentimentAnalysisRequest $request): SentimentAnalysisResponse
    {
        try {
            $this->logger->info('Making request to Flask service');
            
            $response = $this->httpClient->request('POST', 'http://localhost:5000/analyze', [
                'json' => ['text' => $request->text],
                'timeout' => 30
            ]);

            $this->logger->debug('Received response from Flask service');
            
            $data = $response->toArray();
            
            if (!isset($data['text'], $data['sentiment'], $data['polarity'], $data['subjectivity'], $data['confidence'], $data['color'])) {
                throw new \RuntimeException('Invalid response format from Flask service');
            }

            return new SentimentAnalysisResponse(
                text: $data['text'],
                sentiment: $data['sentiment'],
                polarity: $data['polarity'],
                subjectivity: $data['subjectivity'],
                confidence: $data['confidence'],
                color: $data['color']
            );
        } catch (TransportException $e) {
            $this->logger->error('Failed to connect to Flask service: ' . $e->getMessage());
            throw new \RuntimeException('Failed to connect to sentiment analysis service. Please ensure the Flask service is running.');
        } catch (\Exception $e) {
            $this->logger->error('Error processing sentiment analysis: ' . $e->getMessage());
            throw new \RuntimeException('Failed to analyze sentiment: ' . $e->getMessage());
        }
    }
}