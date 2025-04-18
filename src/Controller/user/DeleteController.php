<?php
// src/Controller/DeleteAccountController.php
// src/Controller/DeleteController.php
namespace App\Controller\user;

use App\Repository\BillRepository;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteController extends AbstractController
{
    #[Route('/account/delete', name: 'app_delete_account', methods: ['DELETE'])]
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        FeedbackRepository $feedbackRepository,
        TokenStorageInterface $tokenStorage,
        BillRepository $billRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Invalidate session
        $request->getSession()->invalidate();
        $tokenStorage->setToken(null);

        // Remove user from database
        $entityManager->remove($user);
        // Delete associated feedbacks
        
        $feedbackRepository->deleteAllFeedbacksForUser($user->getUserIdentifier());
        // Delete associated bills
        $billRepository->deleteAllBillsForUser($user->getUserIdentifier());
        
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}