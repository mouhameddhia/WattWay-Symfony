<?php
namespace App\Controller\submission;

use App\Entity\Submission;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;

class FrontSubmissionController extends AbstractController
{
    public function __construct(
        private FlashyNotifier $flashy,
        private GeminiService $geminiService
    ) {
    }

    #[Route('/submission', name: 'Front_Submission', methods: ['GET'])]
    public function index(
        Request $request,
        SubmissionRepository $submissionRepository,
        ResponseRepository $responseRepository
    ): Response {
        // Get existing submissions with responses
        $submissions = $submissionRepository->findAll();
        $submissionsWithResponses = [];
        
        foreach ($submissions as $submission) {
            $responses = $responseRepository->findBySubmissionId($submission->getIdSubmission());
            $submissionsWithResponses[] = [
                'idSubmission' => $submission->getIdSubmission(),
                'status' => $submission->getStatus(),
                'urgencyLevel' => $submission->getUrgencyLevel(),
                'description' => $submission->getDescription(),
                'dateSubmission' => $submission->getDateSubmission(),
                'preferredContactMethod' => $submission->getPreferredContactMethod(),
                'preferredAppointmentDate' => $submission->getPreferredAppointmentDate(),
                'responses' => $responses
            ];
        }

        $submission = new Submission();
        $formSubmission = $this->createForm(FrontSubmissionType::class, $submission);

        return $this->render('frontend/submission/index.html.twig', [
            'submissions' => $submissionsWithResponses,
            'formSubmission' => $formSubmission->createView()
        ]);
    }

    #[Route('/submission/create', name: 'Front_Submission_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        CarRepository $carRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            // Create new submission and form
            $submission = new Submission();
            $formSubmission = $this->createForm(FrontSubmissionType::class, $submission);
            
            // Handle the request
            $formSubmission->handleRequest($request);
            
            if ($formSubmission->isSubmitted()) {
                if (!$formSubmission->isValid()) {
                    // Get form errors
                    $errors = [];
                    foreach ($formSubmission->getErrors(true) as $error) {
                        $errors[] = $error->getMessage();
                    }
                    
                    return $this->json([
                        'success' => false,
                        'message' => 'Please check your input and try again',
                        'errors' => $errors
                    ], 400);
                }

                // Get VIN from unmapped field
                $vinCode = $formSubmission->get('vinCode')->getData();
                
                // Find the Car
                $car = $carRepository->findOneBy(['vinCode' => $vinCode]);
                
                if (!$car) {
                    return $this->json([
                        'success' => false,
                        'message' => 'Please verify the VIN code and try again'
                    ], 404);
                }

                // Check content for inappropriate language
                try {
                    $contentCheck = $this->geminiService->checkContent($submission->getDescription());
                    
                    if (!$contentCheck['is_appropriate']) {
                        return $this->json([
                            'success' => false,
                            'message' => $contentCheck['message']
                        ], 400);
                    }
                } catch (\Exception $e) {
                    // Log the error but allow the submission to proceed
                    error_log('Gemini API error: ' . $e->getMessage());
                }
                
                // Link Car to Submission
                $submission->setCar($car);
                
                // Set default values
                $submission->setDateSubmission(new \DateTime());
                $submission->setStatus('Pending');
                $submission->setUrgencyLevel('Low');
                
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
                        'message' => 'Please try again in a moment'
                    ], 500);
                }
            }

            return $this->json([
                'success' => false,
                'message' => 'Invalid form submission'
            ], 400);

        } catch (\Exception $e) {
            error_log('Server error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return $this->json([
                'success' => false,
                'message' => 'Please try again in a moment'
            ], 500);
        }
    }

    #[Route('/submission/delete/{idSubmission}', name: 'Front_Submission_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Submission $submission,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
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
        ValidatorInterface $validator
    ): Response {
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