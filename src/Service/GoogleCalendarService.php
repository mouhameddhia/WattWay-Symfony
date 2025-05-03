<?php
// src/Service/GoogleCalendarService.php
namespace App\Service;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Exception as GoogleServiceException;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class GoogleCalendarService
{
    private Client $client;
    private string $calendarId;
    private string $tokenPath;
    private Filesystem $filesystem;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $calendarId,
        string $projectDir
    ) {
        $this->filesystem = new Filesystem();
        $this->client = new Client();
        $this->calendarId = $calendarId;
        $this->tokenPath = $projectDir.'/var/google_token.json';
        
        $this->configureClient($clientId, $clientSecret, $redirectUri);
        $this->initializeClient();
    }
    
    private function configureClient(
        string $clientId, 
        string $clientSecret, 
        string $redirectUri
    ): void {
        $this->client->setApplicationName('Warehouse Management System');
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setScopes([
            Calendar::CALENDAR_EVENTS,
            Calendar::CALENDAR_READONLY
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->client->setIncludeGrantedScopes(true);
    }
    
    private function initializeClient(): void
    {
        if ($this->filesystem->exists($this->tokenPath)) {
            try {
                $token = json_decode(
                    file_get_contents($this->tokenPath), 
                    true, 
                    512, 
                    JSON_THROW_ON_ERROR
                );
                
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
                throw new Exception('No refresh token available. Please re-authenticate.');
            }
            
            $token = $this->client->fetchAccessTokenWithRefreshToken(
                $this->client->getRefreshToken()
            );
            
            if (isset($token['error'])) {
                throw new Exception('Refresh error: '.$token['error_description'] ?? 'Unknown error');
            }
            
            $this->client->setAccessToken($token);
            $this->saveToken();
        } catch (GoogleServiceException $e) {
            throw new Exception('Token refresh failed: '.$e->getMessage());
        }
    }
    
    private function saveToken(): void
    {
        try {
            $this->filesystem->mkdir(dirname($this->tokenPath));
            $this->filesystem->dumpFile(
                $this->tokenPath,
                json_encode($this->client->getAccessToken(), JSON_PRETTY_PRINT)
            );
        } catch (\Exception $e) {
            throw new Exception('Failed to save token: '.$e->getMessage());
        }
    }
    
    public function isAuthenticated(): bool
    {
        if (!$this->filesystem->exists($this->tokenPath)) {
            return false;
        }
        
        try {
            $token = json_decode(
                file_get_contents($this->tokenPath), 
                true, 
                512, 
                JSON_THROW_ON_ERROR
            );
            
            $this->client->setAccessToken($token);
            
            if ($this->client->isAccessTokenExpired()) {
                return $this->client->getRefreshToken() !== null;
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createEvent(
        string $summary,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        ?string $description = null,
        ?array $attendees = null
    ): string {
        if ($this->client->isAccessTokenExpired()) {
            $this->refreshToken();
        }

        $service = new Calendar($this->client);
        
        $event = new Event([
            'summary' => $summary,
            'description' => $description,
            'start' => $this->createEventDateTime($start),
            'end' => $this->createEventDateTime($end),
            'attendees' => $this->formatAttendees($attendees),
            'reminders' => [
                'useDefault' => true,
            ],
        ]);

        try {
            $createdEvent = $service->events->insert($this->calendarId, $event);
            return $createdEvent->getHtmlLink();
        } catch (GoogleServiceException $e) {
            error_log('Google API Error: '.$e->getMessage());
            throw new Exception('Failed to create calendar event: '.$e->getMessage());
        }
    }
    
    private function createEventDateTime(\DateTimeInterface $dateTime): array
    {
        return [
            'dateTime' => $dateTime->format(\DateTimeInterface::RFC3339),
            'timeZone' => $dateTime->getTimezone()->getName(),
        ];
    }
    
    private function formatAttendees(?array $attendees): ?array
    {
        if (empty($attendees)) {
            return null;
        }
        
        return array_map(function($email) {
            return ['email' => $email];
        }, $attendees);
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleAuthCallback(string $code): void
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new Exception($token['error_description'] ?? 'Authentication failed');
            }
            
            $this->client->setAccessToken($token);
            
            if (!$this->client->getRefreshToken()) {
                throw new Exception('No refresh token received');
            }
            
            $this->saveToken();
        } catch (GoogleServiceException $e) {
            throw new Exception('Authentication failed: '.$e->getMessage());
        }
    }
    
    public function revokeToken(): void
    {
        if ($this->filesystem->exists($this->tokenPath)) {
            try {
                $token = json_decode(
                    file_get_contents($this->tokenPath), 
                    true, 
                    512, 
                    JSON_THROW_ON_ERROR
                );
                
                $this->client->setAccessToken($token);
                $this->client->revokeToken();
                $this->filesystem->remove($this->tokenPath);
            } catch (\Exception $e) {
                throw new Exception('Failed to revoke token: '.$e->getMessage());
            }
        }
    }
}