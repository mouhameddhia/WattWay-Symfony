<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeminiService
{
    private const API_KEY = 'AIzaSyDmPbDHQxHwKEDH8mkuFBiUXm0aiNrdVC0';
    private const API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    private $httpClient;
    private array $bannedWords;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
        $this->bannedWords = [
            // Profanity
            'fuck', 'shit', 'ass', 'bitch', 'cunt', 'dick', 'pussy', 'cock', 'bastard', 'motherfucker',
            'fucking', 'shitty', 'asshole', 'bitchy', 'cocksucker', 'dickhead', 'piss', 'pissed',
            'fucker', 'fuckers', 'fucked', 'fuckin', 'fucka', 'fuckass', 'fuk', 'fukin', 'fukka', 'fucken',
            'shithead', 'shitty', 'shitting', 'shitted', 'shits', 'shitass', 'shat', 'crap', 'crappy',
            'asshole', 'assholes', 'asswipe', 'asshat', 'assclown', 'assface', 'assmunch', 'asslicker',
            'bitchy', 'bitching', 'bitched', 'bitches', 'bich', 'biatch', 'beyotch',
            'cunt', 'cunts', 'cunty', 'cunting', 'cuntasaur', 'cuntface',
            'dick', 'dicks', 'dickhead', 'dickface', 'dickwad', 'dickweed', 'dickbag', 'dickhole',
            'pussy', 'pussies', 'puss', 'pussys', 'puzzy', 'pussey', 'pussbag',
            'cock', 'cocks', 'cocksucker', 'cocksucking', 'cockhead', 'cockface', 'cockmaster',
            'bastard', 'bastards', 'bastardy',
            'motherfucker', 'motherfuckers', 'motherfucking', 'muthafucker', 'mofo',
        
            // Offensive terms
            'nigger', 'nigga', 'niggas', 'niggaz', 'niggah', 'niggahs',
            'retard', 'retarded', 'retards', 'windowlicker',
            'fag', 'faggot', 'fags', 'faggots', 'faggy',
            'whore', 'whores', 'whoring', 'whorish',
            'slut', 'sluts', 'slutty', 'slutting', 'skank', 'skanky',
            'hoe', 'hoes', 'ho', 'h0e', 'h03',
            'tramp', 'slag', 'tart',
        
            // Common variations and misspellings
            'f*ck', 'sh*t', 'a$$', 'b*tch', 'c*nt', 'd*ck', 'p*ssy', 'c*ck',
            'f**k', 's**t', 'a**', 'b**ch', 'c**t', 'd**k', 'p**sy', 'c**k',
            'f***', 's***', 'a***', 'b***h', 'c***t', 'd***k', 'p***y', 'c***k',
            'fck', 'sht', 'assh', 'btch', 'cnt', 'dck', 'pssy', 'ck',
            'fuk', 'sht', 'assh', 'bch', 'cnt', 'dck', 'pssy', 'ck',
            'fuq', 'fuk', 'phuck', 'phuk', 'sheit', 'shiet', 'shyt', 'scheisse',
        
            // Additional mild profanities
            'damn', 'dammit', 'darn', 'crap', 'bloody', 'bugger', 'bollocks',
            'bollok', 'bullshit', 'bullcrap', 'bullshitting', 'bullshitter', 'screw', 'screwed',
            'prick', 'twat', 'wanker', 'tosser', 'git', 'douche', 'douchebag', 'douchebags', 'twatwaffle',
            'shitfaced', 'shitstorm', 'shitshow', 'jackass', 'jackasses', 'dipshit', 'dumbass', 'butthead',
        
            // Extra slur words
            'chink', 'gook', 'kike', 'spic', 'wetback', 'cracker', 'hillbilly', 'redneck', 'gypsy', 'pikey', 'trailertrash',
        ];
        
    }

    /**
     * Check if the text contains inappropriate content
     *
     * @param string $text The text to analyze
     * @return array ['is_appropriate' => bool, 'message' => string]
     * @throws \Exception
     */
    public function checkContent(string $text): array
    {
        // First, check against local banned words list
        $textLower = strtolower($text);
        foreach ($this->bannedWords as $word) {
            if (strpos($textLower, $word) !== false) {
                return [
                    'is_appropriate' => false,
                    'message' => 'Your submission contains inappropriate content. Please edit and try again.'
                ];
            }
        }

        // Then, use Gemini API for additional context analysis
        try {
            $response = $this->httpClient->request('POST', self::API_URL . '?key=' . self::API_KEY, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "You are a strict content moderator. Analyze this text for ANY inappropriate content, profanity, or offensive language. " .
                                        "Return ONLY 'true' if the content is completely clean and appropriate, or 'false' if it contains ANY inappropriate content. " .
                                        "Be extremely strict in your analysis. " .
                                        "Text to analyze: " . $text
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'topK' => 1,
                        'topP' => 1,
                        'maxOutputTokens' => 1,
                    ],
                    'safetySettings' => [
                        [
                            'category' => 'HARM_CATEGORY_HARASSMENT',
                            'threshold' => 'BLOCK_LOW_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_HATE_SPEECH',
                            'threshold' => 'BLOCK_LOW_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                            'threshold' => 'BLOCK_LOW_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                            'threshold' => 'BLOCK_LOW_AND_ABOVE'
                        ]
                    ]
                ]
            ]);

            $content = $response->toArray();
            
            // Check for safety ratings
            if (isset($content['promptFeedback']['safetyRatings'])) {
                foreach ($content['promptFeedback']['safetyRatings'] as $rating) {
                    if ($rating['probability'] !== 'NEGLIGIBLE') {
                        return [
                            'is_appropriate' => false,
                            'message' => 'Your submission contains inappropriate content. Please edit and try again.'
                        ];
                    }
                }
            }

            // Check the response
            $result = strtolower(trim($content['candidates'][0]['content']['parts'][0]['text']));
            
            if ($result === 'true') {
                return [
                    'is_appropriate' => true,
                    'message' => 'Content is appropriate'
                ];
            } else {
                return [
                    'is_appropriate' => false,
                    'message' => 'Your submission contains inappropriate content. Please edit and try again.'
                ];
            }
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new \Exception('Error checking content: ' . $e->getMessage());
        }
    }

    public function generateText(string $prompt): array
    {
        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ],
                    'safetySettings' => [
                        [
                            'category' => 'HARM_CATEGORY_HARASSMENT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_HATE_SPEECH',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ],
                        [
                            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getContent(), true);

            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => false,
                    'error' => 'Invalid response from Gemini API'
                ];
            }

            return [
                'success' => true,
                'text' => $data['candidates'][0]['content']['parts'][0]['text']
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 