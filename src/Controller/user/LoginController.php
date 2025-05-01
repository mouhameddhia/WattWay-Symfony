<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForgotPasswordType;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;


class LoginController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    

    #[Route(path: '/login', name: 'app_login')]
public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em): Response
{
    
    if ($this->getUser()) {
        return $this->redirectToRoute('Front');
    }

    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();
    
    $bannedUser = null;
    
    if ($error) {
        if (str_contains($error->getMessageKey(), 'banned')) {
            $user = $em->getRepository(User::class)
                ->findOneBy(['emailUser' => $lastUsername]);
            
            if ($user && $user->isBanned()) {
                $bannedUser = $user;
            }
        }
    }

    return $this->render('frontend/user/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'banned_user' => $bannedUser,
    ]);
}

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/api/get-face-descriptor', name: 'api_get_face_descriptor', methods: ['GET'])]
    public function getFaceDescriptor(): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user->getFaceDescriptor()) {
            return new JsonResponse(
                ['success' => false, 'error' => 'No face profile found'],
                Response::HTTP_NOT_FOUND
            );
        }
        
        return new JsonResponse(
            ['success' => true, 'descriptor' => $user->getFaceDescriptor()],
            Response::HTTP_OK
        );
    }
    #[Route('/api/face-login', name: 'api_face_login', methods: ['POST'])]
    public function faceLogin(): JsonResponse
    {
        // Authentication happens in your FaceAuthenticator
        return $this->json(
            ['success' => true],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

   




  
}
