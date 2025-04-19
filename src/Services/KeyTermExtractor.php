<?php

namespace App\Services;

use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Stemmers\PorterStemmer;
use NlpTools\PosTaggers\MaxentTagger;

class KeyTermExtractor
{
    private $tagger;

    public function __construct()
    {
        $this->tagger = new MaxentTagger(
            __DIR__.'/en-pos-maxent.bin',
            __DIR__.'/en-token.bin'
        );
        $this->tokenizer = new WhitespaceTokenizer();
        $this->stemmer = new PorterStemmer();
    }
    private $tokenizer;
    private $stemmer;


    public function extract(string $text): array
    {
        $tagged = $this->tagger->tag($this->tokenizer->tokenize($text));
        return array_slice(array_unique(array_map(function($token) {
            return $this->stemmer->stem($token[0]);
        }, array_filter($tagged, function($t) {
            return in_array($t[1], ['NN', 'NNS', 'NNP', 'NNPS']);
        }))), 0, 5);
    }
}