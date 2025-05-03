<?php
// src/Controller/GoogleCalendarController.php
namespace App\Controller;

use App\Service\GoogleCalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class GoogleCalendarController extends AbstractController
{
    #[Route('/assignment/google/auth',  name: 'google_auth')]
    public function auth(GoogleCalendarService $calendarService): RedirectResponse
    {
        // build the consent URL and send the user there
        $url = $calendarService->getAuthUrl();
        return $this->redirect($url);
    }

    #[Route('/assignment/google/callback', name: 'google_callback')]
    public function callback(Request $request, GoogleCalendarService $calendarService): RedirectResponse
    {
        // Google will call back here with ?code=…
        $code = $request->query->get('code');
        if ($code) {
            $calendarService->handleAuthCallback($code);
        }
        // go back to your “new assignment” form (or wherever makes sense)
        return $this->redirectToRoute('app_assignment_new');
    }
}