<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FaceAuthenticator extends AbstractAuthenticator
{
    private $entityManager;
    private $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'api_face_login' 
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['descriptor'])) {
            throw new CustomUserMessageAuthenticationException('No face data received');
        }
        
        // Find user by face descriptor
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByFaceDescriptor($data['descriptor']);
            
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('No matching face found');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        return new JsonResponse([
            'success' => true,
            'redirect' => $this->router->generate('Front')
        ]);
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        return new JsonResponse(
            ['success' => false, 'error' => $exception->getMessageKey()],
            Response::HTTP_UNAUTHORIZED
        );
    }
}