<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/sentiment/analyze',
            name: 'analyze_sentiment',
            description: 'Analyze the sentiment of a text',
            input: SentimentAnalysisRequest::class,
            output: SentimentAnalysisResponse::class
        )
    ]
)]
class SentimentAnalysis
{
}

class SentimentAnalysisRequest
{
    #[ApiProperty(description: 'The text to analyze')]
    #[Assert\NotBlank]
    public string $text;
}

class SentimentAnalysisResponse
{
    public function __construct(
        public string $text,
        public string $sentiment,
        public float $polarity,
        public float $subjectivity,
        public float $confidence,
        public string $color
    ) {}
} 