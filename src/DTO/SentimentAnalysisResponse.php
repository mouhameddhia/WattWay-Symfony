<?php

namespace App\DTO;

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