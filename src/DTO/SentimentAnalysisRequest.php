<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SentimentAnalysisRequest
{
    #[Assert\NotBlank]
    public string $text;
} 