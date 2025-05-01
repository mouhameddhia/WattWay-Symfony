<?php
// src/Controller/UserController.php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
public function register(
    Request $request, 
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager
): Response {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        // 1. Verify reCAPTCHA
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        
        if (empty($recaptchaResponse)) {
            $this->addFlash('error', 'Please complete the reCAPTCHA');
        } else {
            $secret = '6Lf07igrAAAAAAWRHZgW2V1tqV70ghT_ltOr6j3r'; 
            $client = HttpClient::create();
            
            try {
                $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret' => $secret,
                        'response' => $recaptchaResponse,
                    ],
                ]);
                
                $result = $response->toArray();
                
                if ($result['success'] && $form->isValid()) {
                    // Register user
                    $user->setPasswordUser(
                        $passwordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                    $user->setRoleUser('client');
                    
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $this->addFlash('success', 'Registration successful!');
                    return $this->redirectToRoute('app_login');
                } else {
                    $this->addFlash('error', 'reCAPTCHA verification failed');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Verification service unavailable');
            }
        }
    }

    return $this->render('frontend/user/register.html.twig', [
        'form' => $form->createView(),
    ]);
}
    #[Route('/create-admin-now', name: 'create_admin_now')]
    public function createAdminNow(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
        $admin = new User();
        $admin->setEmailUser('admin@example.com');
        $admin->setFirstNameUser('Admin');
        $admin->setLastNameUser('User');
        $admin->setPasswordUser($hasher->hashPassword($admin, 'admin123'));
        $admin->setRoleUser('ADMIN');
        
        // Add these required fields
        $admin->setPaymentDetails('PAYPAL'); // or 'CREDIT_CARD' or 'BANK_TRANSFER'
        $admin->setAddress('Default admin address'); // if address is required
        
        $em->persist($admin);
        $em->flush();
        
        return new Response('Admin created! Email: admin@example.com | Password: admin123');
    }


    // In your UserController.php add this new route
#[Route('/generate-password', name: 'generate_password')]
public function generatePassword(): JsonResponse
{
    $apiKey = 'lKoloI23HFrBDoRojbs/ug==iUwQhGYwxfvB7ZLr';
    $client = HttpClient::create();
    
    try {
        $response = $client->request('GET', 'https://api.api-ninjas.com/v1/passwordgenerator', [
            'headers' => [
                'X-Api-Key' => $apiKey
            ],
            'query' => [
                'length' => 16,
                'exclude_numbers' => 'false',
                'exclude_special_chars' => 'false'
            ]
        ]);
        
        $data = $response->toArray();
        
        return $this->json([
            'password' => $data['random_password'] ?? null
        ]);
    } catch (\Exception $e) {
        return $this->json([
            'error' => 'Failed to generate password'
        ], 500);
    }
}
}