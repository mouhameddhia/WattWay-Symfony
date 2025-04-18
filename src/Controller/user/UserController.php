<?php
// src/Controller/UserController.php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface; ///database
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController ///base class(render() redirect())
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Encode the plain password
            $user->setPasswordUser(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
           
            $user->setRoleUser('client');
            
            $entityManager->persist($user); // prepare user object to be saved
            $entityManager->flush(); //execute the db query to save user

            return $this->redirectToRoute('app_login', [
                'firstName' => $user->getFirstNameUser(),
                'lastName' => $user->getLastNameUser()
            ]);
        }

        return $this->render('frontend/user/register.html.twig', [
            'form' => $form->createView(), //pass the form to template (for display)
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
}