<?php
namespace App\Controller\submission;

use App\Entity\Submission;
use App\Entity\User;
use App\Form\FrontSubmissionType;
use App\Repository\SubmissionRepository;
use App\Services\GeminiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ResponseRepository;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;

class FrontSubmissionController extends AbstractController
{
    public function __construct(
        private FlashyNotifier $flashy,
        private GeminiService $geminiService
    ) {
    }


    #[Route('/submission/create', name: 'Front_Submission_create', methods: ['POST'])]
    public function create(
        Request $request, 
        EntityManagerInterface $entityManager,
        CarRepository $carRepository,
        UserRepository $userRepository
    ): JsonResponse {
        try {
            $submission = new Submission();
            $form = $this->createForm(FrontSubmissionType::class, $submission);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Get the logged-in user
                $user = $this->getUser();
                if (!$user) {
                    return $this->json([
                        'success' => false,
                        'message' => 'You must be logged in to create a submission'
                    ], 401);
                }

                // Get VIN from unmapped field
                $vinCode = $form->get('vinCode')->getData();
                
                // Find the Car
                $car = $carRepository->findOneBy(['vinCode' => $vinCode]);
                
                if (!$car) {
                    return $this->json([
                        'success' => false,
                        'message' => 'No car found with this VIN code'
                    ], 404);
                }

                // Set the user ID from the logged-in user
                $submission->setIdUser($userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getIdUser());
                
                // Link Car to Submission
                $submission->setCar($car);
                
                // Set default status and date
                $submission->setStatus('PENDING');
                $submission->setDateSubmission(new \DateTime());
                $submission->setLast_modified(new \DateTime());
                $submission->setUrgencyLevel('LOW');

                try {
                    $entityManager->persist($submission);
                    $entityManager->flush();

                    return $this->json([
                        'success' => true,
                        'message' => 'Submission created successfully'
                    ]);
                } catch (\Exception $e) {
                    error_log('Database error: ' . $e->getMessage());
                    return $this->json([
                        'success' => false,
                        'message' => 'Failed to save submission: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Get form errors
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return $this->json([
                'success' => false,
                'message' => 'Invalid form data',
                'errors' => $errors
            ], 400);

        } catch (\Exception $e) {
            error_log('Server error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return $this->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/submission/delete/{idSubmission}', name: 'Front_Submission_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Submission $submission,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse {
        try {
            // Check if user is logged in and owns the submission
            if (!$this->getUser() || $userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getIdUser() !== $submission->getIdUser()) {
                return $this->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this submission'
                ], 403);
            }

            if (!$this->isCsrfTokenValid('delete'.$submission->getIdSubmission(), $request->request->get('_token'))) {
                $this->addFlash('info', 'Please refresh the page and try again');
                return $this->json([
                    'success' => false
                ], 403);
            }

            $entityManager->remove($submission);
            $entityManager->flush();

            $this->addFlash('success', 'Submission deleted successfully');
            return $this->json([
                'success' => true,
                'message' => 'Submission deleted successfully'
            ]);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to delete submission');
            return $this->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/submission/edit/{idSubmission}', name: 'Front_Submission_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Submission $submission,
        EntityManagerInterface $entityManager,
        CarRepository $carRepository,
        ValidatorInterface $validator,
        UserRepository $userRepository
    ): Response {
        // Check if user is logged in and owns the submission
        if (!$this->getUser() || $userRepository->getLoggedInUser($this->getUser()->getUserIdentifier())->getIdUser() !== $submission->getIdUser()) {
            $this->addFlash('error', 'You are not authorized to edit this submission');
            return $this->redirectToRoute('Front');
        }

        // Set the locale
        $locale = $request->getLocale();
        $request->setLocale($locale);

        if ($request->isMethod('GET')) {
            $currentVin = $submission->getCar() ? $submission->getCar()->getVinCode() : '';
            $formSubmission = $this->createForm(FrontSubmissionType::class, $submission);
            $formSubmission->get('vinCode')->setData($currentVin);

            return $this->render('frontend/submission/edit.html.twig', [
                'submission' => $submission,
                'formSubmission' => $formSubmission->createView(),
                'current_locale' => $locale
            ]);
        }

        try {
            $formSubmission = $this->createForm(FrontSubmissionType::class, $submission);
            $formSubmission->handleRequest($request);
            
            if ($formSubmission->isSubmitted() && $formSubmission->isValid()) {
                // Get VIN from unmapped field
                $vinCode = $formSubmission->get('vinCode')->getData();
                
                // Find the Car
                $car = $carRepository->findOneBy(['vinCode' => $vinCode]);
                
                if (!$car) {
                    $this->addFlash('error', 'No car found with this VIN');
                    return $this->redirectToRoute('Front_Submission_edit', ['idSubmission' => $submission->getIdSubmission()]);
                }
                
                // Link Car to Submission
                $submission->setCar($car);
                
                try {
                    $entityManager->flush();
                    $this->addFlash('success', 'Submission updated successfully');
                    return $this->redirectToRoute('Front');
                } catch (\Exception $e) {
                    error_log('Database error: ' . $e->getMessage());
                    $this->addFlash('error', 'Failed to update submission. Please try again.');
                    return $this->redirectToRoute('Front_Submission_edit', ['idSubmission' => $submission->getIdSubmission()]);
                }
            } else {
                // Get form errors
                foreach ($formSubmission->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute('Front_Submission_edit', ['idSubmission' => $submission->getIdSubmission()]);
            }
        } catch (\Exception $e) {
            error_log('Server error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->addFlash('error', 'An error occurred. Please try again.');
            return $this->redirectToRoute('Front_Submission_edit', ['idSubmission' => $submission->getIdSubmission()]);
        }
    }
}