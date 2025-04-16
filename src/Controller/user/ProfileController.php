<?php
namespace App\Controller\user;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Logged-in user is not valid.');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {
                $uploadDir = $this->getParameter('profile_pictures_directory');
                $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();

                // Delete the old profile picture if a new one is uploaded
                $oldProfilePicture = $user->getProfilePicture();
                if ($oldProfilePicture && file_exists($uploadDir . '/' . basename($oldProfilePicture))) {
                    unlink($uploadDir . '/' . basename($oldProfilePicture));
                }

                // Move the new image to the upload directory
                $profilePictureFile->move($uploadDir, $newFilename);

                // Set the relative path
                $user->setProfilePicture('uploads/profile_pictures/' . $newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('frontend/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}