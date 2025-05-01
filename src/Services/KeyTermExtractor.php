<?php

namespace App\Services;

class KeyTermExtractor
{
    private $stopWords;
    private $minWordLength = 3; // Increased minimum word length for better quality
    private $minTermFrequency = 1;

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

            // Preprocess text
            $text = $this->preprocessText($text);
            
            // Extract single words and their frequencies
            $terms = $this->extractSingleWords($text);
            
            // Calculate scores
            $scoredTerms = $this->scoreTerms($terms);
            
            // Sort by score and get top terms
            arsort($scoredTerms);
            $keyTerms = array_slice(array_keys($scoredTerms), 0, 10); // Get top 10 terms
            
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
        
        // Remove special characters but keep spaces and apostrophes
        $text = preg_replace('/[^a-z0-9\s\']/', ' ', $text);
        
        // Remove extra spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    private function extractSingleWords(string $text): array
    {
        $terms = [];
        $words = explode(' ', $text);
        
        foreach ($words as $word) {
            // Skip if word is too short or is a stop word
            if (strlen($word) < $this->minWordLength || in_array($word, $this->stopWords)) {
                continue;
            }
            
            // Count word frequency
            $terms[$word] = ($terms[$word] ?? 0) + 1;
        }
        
        return $terms;
    }

    private function scoreTerms(array $terms): array
    {
        $scoredTerms = [];
        $totalTerms = array_sum($terms);
        
        foreach ($terms as $term => $frequency) {
            // Basic score based on frequency
            $score = $frequency / $totalTerms;
            
            // Boost score for longer words (they tend to be more meaningful)
            $score *= (1 + (strlen($term) * 0.1));
            
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