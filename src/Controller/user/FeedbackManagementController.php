<?php

namespace App\Controller\user;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackManagementController extends AbstractController
{
    #[Route('/feedback/list', name: 'feedback_list')]
    public function list(FeedbackRepository $feedbackRepository): Response
    {
        // Fetch all feedback with associated user (with first and last name)
        $feedbacks = $feedbackRepository->findAllFeedbackWithUser();

        return $this->render('backend/user/listFeedback.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }

    #[Route('/deleteFeedback/{idFeedback}', name: 'feedbackdelete')]
    public function delete(int $idFeedback, FeedbackRepository $feedbackRepository, EntityManagerInterface $em): Response
    {
        $feedback = $feedbackRepository->find($idFeedback);

        if (!$feedback) {
            throw $this->createNotFoundException('Feedback not found');
        }

        $em->remove($feedback);
        $em->flush();

        return $this->redirectToRoute('feedback_list');
    }
}
