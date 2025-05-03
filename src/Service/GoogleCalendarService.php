<?php
// src/Service/GoogleCalendarService.php
namespace App\Service;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Exception as GoogleServiceException;
use Exception;

class GoogleCalendarService
{
    private Client $client;
    private string $calendarId;
    private string $tokenPath;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $calendarId,
        string $projectDir
    ) {
        $this->client = new Client();
        $this->client->setApplicationName('Your Application Name');
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->addScope(Calendar::CALENDAR_EVENTS);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);
        
        $this->calendarId = $calendarId;
        $this->tokenPath = $projectDir.'/var/google_token.json';
        
        $this->initializeClient();
    }
    
    private function initializeClient(): void
    {
        if (file_exists($this->tokenPath)) {
            try {
                $token = json_decode(file_get_contents($this->tokenPath), true, 512, JSON_THROW_ON_ERROR);
                $this->client->setAccessToken($token);
                
                if ($this->client->isAccessTokenExpired()) {
                    $this->refreshToken();
                }
            } catch (\JsonException $e) {
                throw new Exception('Failed to parse Google token: '.$e->getMessage());
            }
        }
    }
    
    private function refreshToken(): void
    {
        try {
            if (!$this->client->getRefreshToken()) {
                throw new Exception('No refresh token available');
            }
            
            $token = $this->client->fetchAccessTokenWithRefreshToken(
                $this->client->getRefreshToken()
            );
            $this->client->setAccessToken($token);
            $this->saveToken();
        } catch (GoogleServiceException $e) {
            throw new Exception('Token refresh failed: '.$e->getMessage());
        }
    }
    
    private function saveToken(): void
    {
        error_log('🏷️ GoogleCalendarService saving token to: ' . $this->tokenPath);
        if (!file_exists(dirname($this->tokenPath))) {
            mkdir(dirname($this->tokenPath), 0700, true);
        }
        
        file_put_contents($this->tokenPath, json_encode($this->client->getAccessToken(), JSON_PRETTY_PRINT));
    }
    
    public function isAuthenticated(): bool
    {
        return file_exists($this->tokenPath) && 
               !$this->client->isAccessTokenExpired() && 
               $this->client->getRefreshToken() !== null;
    }

    public function createEvent(
        string $summary,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        ?string $description = null
    ): string {
        if ($this->client->isAccessTokenExpired()) {
            $this->refreshToken();
        }

        $service = new Calendar($this->client);
        
        $event = new Event([
            'summary' => $summary,
            'description' => $description,
            'start' => [
                'dateTime' => $start->format(\DateTimeInterface::RFC3339),
                'timeZone' => $start->getTimezone()->getName(),
            ],
            'end' => [
                'dateTime' => $end->format(\DateTimeInterface::RFC3339),
                'timeZone' => $end->getTimezone()->getName(),
            ],
        ]);

        try {
            $createdEvent = $service->events->insert($this->calendarId, $event);
            return $createdEvent->getHtmlLink();
        } catch (GoogleServiceException $e) {
            throw new Exception('Failed to create calendar event: '.$e->getMessage());
        }
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleAuthCallback(string $code): void
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($token);
            $this->saveToken();
        } catch (GoogleServiceException $e) {
            throw new Exception('Authentication failed: '.$e->getMessage());
        }
    }
}