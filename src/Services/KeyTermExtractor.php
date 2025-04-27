<?php

namespace App\Services;

class KeyTermExtractor
{
    private $stopWords;
    private $minWordLength = 2;
    private $maxPhraseLength = 3;
    private $minTermFrequency = 1;
    private $posWeights = [
        'NN' => 1.5,  // Nouns
        'NNS' => 1.5, // Plural nouns
        'NNP' => 2.0, // Proper nouns
        'JJ' => 1.2,  // Adjectives
        'VB' => 1.0,  // Verbs
        'RB' => 0.8,  // Adverbs
    ];

    public function __construct()
    {
        $this->stopWords = $this->loadStopWords();
    }

    public function extract(string $text): array
    {
        try {
            if (empty(trim($text))) {
                return [
                    'success' => false,
                    'error' => 'No text provided for analysis'
                ];
            }

            // Debug: Log original text
            error_log("Original text: " . $text);

            // Preprocess text
            $text = $this->preprocessText($text);
            
            // Debug: Log preprocessed text
            error_log("Preprocessed text: " . $text);
            
            // Extract terms and their frequencies
            $terms = $this->extractTerms($text);
            
            // Debug: Log extracted terms
            error_log("Extracted terms: " . json_encode($terms));
            
            // If no terms found, try a more lenient approach
            if (empty($terms)) {
                $terms = $this->extractTermsLenient($text);
                error_log("Extracted terms (lenient): " . json_encode($terms));
            }
            
            // Calculate TF-IDF scores
            $tfidfScores = $this->calculateTfIdf($terms);
            
            // Debug: Log TF-IDF scores
            error_log("TF-IDF scores: " . json_encode($tfidfScores));
            
            // Combine scores and get top terms
            $scoredTerms = $this->scoreTerms($terms, $tfidfScores);
            
            // Debug: Log final scores
            error_log("Final scores: " . json_encode($scoredTerms));
            
            // Sort by score and get top terms
            arsort($scoredTerms);
            $keyTerms = array_slice(array_keys($scoredTerms), 0, 5);
            
            // Debug: Log final key terms
            error_log("Final key terms: " . json_encode($keyTerms));
            
            return [
                'success' => true,
                'keyTerms' => $keyTerms
            ];
        } catch (\Exception $e) {
            error_log('Key term extraction error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to extract key terms: ' . $e->getMessage()
            ];
        }
    }

    private function preprocessText(string $text): string
    {
        // Convert to lowercase
        $text = strtolower($text);
        
        // Remove URLs
        $text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', ' ', $text);
        
        // Remove special characters but keep spaces, hyphens, and apostrophes
        $text = preg_replace('/[^a-z0-9\s\'-]/', ' ', $text);
        
        // Remove extra spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    private function extractTerms(string $text): array
    {
        $terms = [];
        $words = explode(' ', $text);
        $totalWords = count($words);
        
        // Extract single words and n-grams
        for ($i = 0; $i < $totalWords; $i++) {
            // Single words
            $word = $words[$i];
            if (strlen($word) >= $this->minWordLength && !in_array($word, $this->stopWords)) {
                $terms[$word] = ($terms[$word] ?? 0) + 1;
            }
            
            // N-grams (phrases)
            for ($n = 2; $n <= $this->maxPhraseLength; $n++) {
                if ($i + $n <= $totalWords) {
                    $phrase = implode(' ', array_slice($words, $i, $n));
                    if (strlen($phrase) >= $this->minWordLength * $n) {
                        $terms[$phrase] = ($terms[$phrase] ?? 0) + 1;
                    }
                }
            }
        }
        
        return $terms;
    }

    private function extractTermsLenient(string $text): array
    {
        $terms = [];
        $words = explode(' ', $text);
        $totalWords = count($words);
        
        // More lenient extraction
        for ($i = 0; $i < $totalWords; $i++) {
            $word = $words[$i];
            // Only filter out very common stop words
            if (strlen($word) >= 2 && !in_array($word, ['the', 'and', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'of'])) {
                $terms[$word] = ($terms[$word] ?? 0) + 1;
            }
        }
        
        return $terms;
    }

    private function calculateTfIdf(array $terms): array
    {
        $totalTerms = array_sum($terms);
        $tfidfScores = [];
        
        foreach ($terms as $term => $frequency) {
            // Term Frequency (TF)
            $tf = $frequency / $totalTerms;
            
            // Inverse Document Frequency (IDF)
            // Simplified IDF calculation
            $idf = log(1 + (1 / (1 + strlen($term) / 10)));
            
            // TF-IDF score
            $tfidfScores[$term] = $tf * $idf;
        }
        
        return $tfidfScores;
    }

    private function scoreTerms(array $terms, array $tfidfScores): array
    {
        $scoredTerms = [];
        
        foreach ($terms as $term => $frequency) {
            $score = $tfidfScores[$term];
            
            // Boost score for longer terms (phrases)
            $wordCount = count(explode(' ', $term));
            if ($wordCount > 1) {
                $score *= (1 + ($wordCount * 0.2));
            }
            
            // Boost score for terms with capital letters (proper nouns)
            if (preg_match('/[A-Z]/', $term)) {
                $score *= 1.3;
            }
            
            // Boost score for terms containing numbers
            if (preg_match('/\d/', $term)) {
                $score *= 1.2;
            }
            
            $scoredTerms[$term] = $score;
        }
        
        return $scoredTerms;
    }

    private function loadStopWords(): array
    {
        return [
            'the', 'and', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'about',
            'as', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'from', 'up',
            'down', 'in', 'out', 'over', 'under', 'again', 'further', 'then', 'once', 'here',
            'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more',
            'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so',
            'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now',
            'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
            'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'would', 'should', 'could',
            'might', 'must', 'shall', 'will', 'may', 'need', 'needs', 'needed', 'want', 'wants', 'wanted',
            'like', 'likes', 'liked', 'use', 'uses', 'used', 'get', 'gets', 'got', 'getting', 'make',
            'makes', 'made', 'making', 'go', 'goes', 'went', 'going', 'know', 'knows', 'knew', 'knowing',
            'take', 'takes', 'took', 'taking', 'see', 'sees', 'saw', 'seeing', 'come', 'comes', 'came',
            'coming', 'think', 'thinks', 'thought', 'thinking', 'look', 'looks', 'looked', 'looking',
            'want', 'wants', 'wanted', 'give', 'gives', 'gave', 'giving', 'use', 'uses', 'used', 'using',
            'find', 'finds', 'found', 'finding', 'tell', 'tells', 'told', 'telling', 'ask', 'asks',
            'asked', 'asking', 'work', 'works', 'worked', 'working', 'seem', 'seems', 'seemed', 'seeming',
            'feel', 'feels', 'felt', 'feeling', 'try', 'tries', 'tried', 'trying', 'leave', 'leaves',
            'left', 'leaving', 'call', 'calls', 'called', 'calling'
        ];
    }
} 