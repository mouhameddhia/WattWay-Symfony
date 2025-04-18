<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserManagementController extends AbstractController
{
   
#[Route('/list', name: 'user_list')]
public function list(UserRepository $userRepository): Response
{
   
    $users = $userRepository->findByRole('CLIENT');

    return $this->render('backend/user/listUsers.html.twig', [
        'users' => $users,
    ]);
}

    #[Route('/delete/{idUser}', name: 'user_delete')]
    public function delete(int $idUser, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($idUser);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_list');
    }
}
