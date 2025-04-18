<?php
// src/Controller/DeleteAccountController.php
// src/Controller/DeleteController.php
namespace App\Controller\user;

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
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED); //only authenticated users can delete their accounts.
        }

        // Invalidate session
        $request->getSession()->invalidate(); //nvalidates the current session, effectively logging the user out.
        $tokenStorage->setToken(null); //Removes all session data (e.g., CSRF tokens, user data).


        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}