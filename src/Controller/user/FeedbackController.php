<?php
// src/Controller/FeedbackController.php  // Changed from user subdirectory

namespace App\Controller\user;  // Changed namespace

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class FeedbackController extends AbstractController
{
    #[Route('/', name: 'Front')]  // Added route annotation
    public function showFeedbacks(FeedbackRepository $feedbackRepository): Response
    {
        $feedbacks = $feedbackRepository->findLatestFeedbacks(5); // Get 5 latest feedbacks
        
        return $this->render('frontend/baseFront.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }
    #[Route('/feedback/delete/{id}', name: 'feedback_delete', methods: ['POST'])]
    public function delFeedback(
        Request $request,
        Feedback $feedback,
        EntityManagerInterface $entityManager
    ): Response {
        // 1. Verify the user is logged in
        if (!$this->getUser()) {
            $this->addFlash('error', 'You must be logged in to delete feedback');
            return $this->redirectToRoute('Front');
        }

        // 2. Verify the feedback belongs to the current user
        if ($this->getUser()->getUserIdentifier() !== $feedback->getUser()->getUserIdentifier()) {
            $this->addFlash('error', 'You can only delete your own feedback');
            return $this->redirectToRoute('Front');
        }

        // 3. Verify CSRF token
        if ($this->isCsrfTokenValid('delete-feedback-'.$feedback->getIdFeedback(), $request->request->get('_token'))) {
            $entityManager->remove($feedback);
            $entityManager->flush();
            $this->addFlash('success', 'Feedback deleted successfully');
        } else {
            $this->addFlash('error', 'Invalid security token');
        }

        return $this->redirectToRoute('Front');
        
    }

    #[Route('/submit-feedback', name: 'submit_feedback', methods: ['POST'])]
public function submitFeedback(Request $request, EntityManagerInterface $em): Response
{
    // Check if user is logged in
    if (!$this->getUser()) {
        $this->addFlash('error', 'You must be logged in to submit feedback');
        return $this->redirectToRoute('Front');
    }

    // Create new Feedback entity
    $feedback = new Feedback();
    $feedback->setContent($request->request->get('content'));
    $feedback->setRating($request->request->get('rating'));
    $feedback->setUser($this->getUser());
    $feedback->setDate(new \DateTime());

    // Validate CSRF token
    if (!$this->isCsrfTokenValid('feedback', $request->request->get('_token'))) {
        $this->addFlash('error', 'Invalid security token');
        return $this->redirectToRoute('Front');
    }

    // Save to database
    $em->persist($feedback);
    $em->flush();

    $this->addFlash('success', 'Thank you for your feedback!');
    return $this->redirectToRoute('Front');
}
}