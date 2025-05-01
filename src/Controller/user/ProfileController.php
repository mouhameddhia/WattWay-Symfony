<?php
namespace App\Controller\user;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\AvatarService;


class ProfileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private AvatarService $avatarService
    ) {}

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        
        if (!$user instanceof User) {
            throw new \LogicException('Logged-in user is not valid.');
        }

        // Generate default avatar URL if no profile picture exists
        $defaultAvatar = $user->getProfilePicture() 
            ? null 
            : $this->avatarService->generateAvatar($user->getEmailUser());

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {
                $uploadDir = $this->getParameter('profile_pictures_directory');
                $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();

                // Delete the old profile picture if exists
                $oldProfilePicture = $user->getProfilePicture();
                if ($oldProfilePicture && file_exists($uploadDir . '/' . basename($oldProfilePicture))) {
                    unlink($uploadDir . '/' . basename($oldProfilePicture));
                }

                // Move and save the new image
                $profilePictureFile->move($uploadDir, $newFilename);
                $user->setProfilePicture('uploads/profile_pictures/' . $newFilename);
            }

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('frontend/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'defaultAvatar' => $defaultAvatar
        ]);
    }

    

    #[Route('/save-face-descriptor', name: 'app_save_face_descriptor', methods: ['POST'])]
    public function saveFaceDescriptor(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['descriptor'])) {
            return $this->json(['error' => 'No face data received'], 400);
        }
        
        // Validate descriptor (128-element float array)
        if (!is_array($data['descriptor']) || count($data['descriptor']) !== 128) {
            return $this->json(['error' => 'Invalid face descriptor format'], 400);
        }
        
        try {
            /** @var User $user */
            $user = $this->getUser();
            $user->setFaceDescriptor($data['descriptor']);
            $em->flush();
            
            // For debugging - save the image temporarily
            if (!empty($data['imageData'])) {
                $imageData = base64_decode(explode(',', $data['imageData'])[1]);
                file_put_contents(
                    $this->getParameter('profile_pictures_directory').'/face_debug_'.$user->getIdUser().'.jpg',
                    $imageData
                );
            }
            
            return $this->json(['success' => true]);
            
        } catch (\Exception $e) {
            return $this->json(['error' => 'Database error: '.$e->getMessage()], 500);
        }
    }
}