<?php

namespace App\Controller\user;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FeedbackManagementController extends AbstractController
{
   

   #[Route('/feedback/list', name: 'feedback_list')]
   public function list(Request $request, FeedbackRepository $feedbackRepository): Response
   {
       
       $feedbacks = $feedbackRepository->findAllFeedbackWithUser();
       
       if ($request->query->has('search')) {
           $searchQuery = $request->query->get('search');
           $feedbacks = $feedbackRepository->searchFeedbacks($searchQuery);
       }
       
       if ($request->query->has('sort')) {
           $sortBy = $request->query->get('sort');
           $direction = $request->query->get('direction', 'DESC');
           $feedbacks = $feedbackRepository->sortFeedbacks($sortBy, $direction);
       }
       
       if ($request->query->has('filter')) {
           $filterBy = $request->query->get('filter');
           $value = $request->query->get('value');
           $feedbacks = $feedbackRepository->filterFeedbacks($filterBy, $value);
       }
       
       // Get rating data for the chart
       $ratingData = $feedbackRepository->getRatingDistribution();
       
       // If no ratings exist, use demo data
       if (array_sum($ratingData) === 0) {
           $ratingData = [1 => 2, 2 => 3, 3 => 8, 4 => 12, 5 => 7];
           $this->addFlash('info', 'Demo rating data shown for illustration');
       }
   
       return $this->render('backend/user/listFeedbacks.html.twig', [
           'feedbacks' => $feedbacks,
           'ratingData' => array_values($ratingData) // Convert to indexed array for JS
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

    #[Route('/feedback/dashboard', name: 'feedback_dashboard')]
public function dashboard(FeedbackRepository $feedbackRepo): Response
{
    $ratingData = $feedbackRepo->getRatingDistribution();
    
    // Simulate data if empty (for demo purposes)
    if (array_sum($ratingData) === 0) {
        $ratingData = [1 => 2, 2 => 3, 3 => 8, 4 => 12, 5 => 7];
        $this->addFlash('info', 'Demo data shown for illustration');
    }

    return $this->render('backend/user/feedback_dashboard.html.twig', [
        'ratingData' => array_values($ratingData), // Convert to indexed array for JS
    ]);
}
}
