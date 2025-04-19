<?php

namespace App\Controller\submission;

use App\Entity\Submission;
use App\Form\FrontSubmissionType;
use App\Repository\SubmissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ResponseRepository;
use App\Repository\CarRepository;


class FrontSubmissionController extends AbstractController
{
    #[Route('/submission', name: 'Front_Submission', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SubmissionRepository $submissionRepository,
        ResponseRepository $responseRepository,
        CarRepository $carRepository
    ): Response {

  
        $submission = new Submission();
        $form = $this->createForm(FrontSubmissionType::class, $submission);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get VIN from unmapped field
            $vinCode = $form->get('vinCode')->getData();
            
            // Find the Car
            $car = $carRepository->findOneBy(['vinCode' => $vinCode]);
            
            if (!$car) {
                $this->addFlash('error', 'No car found with this VIN');
                return $this->redirectToRoute('Front_Submission');
            }
            
            // Link Car to Submission
            $submission->setCar($car);
            
            // Set default values
            $submission->setDateSubmission(new \DateTime());
            $submission->setStatus('Pending');
            
            // Persist and flush
            $entityManager->persist($submission);
            $entityManager->flush();
            
            $this->addFlash('success', 'Submission created!');
            return $this->redirectToRoute('Front_Submission');
        }

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

        return $this->render('frontend/submission/index.html.twig', [
            'submissions' => $submissionsWithResponses,
            'form' => $form->createView()
        ]);
    }


    #[Route('/submission/delete/{idSubmission}', name: 'Front_Submission_delete', methods: ['POST'])]
public function delete(
    Request $request, 
    Submission $submission, 
    EntityManagerInterface $entityManager
): Response {
    if ($this->isCsrfTokenValid('delete'.$submission->getIdSubmission(), $request->request->get('_token'))) {
        $entityManager->remove($submission);
        $entityManager->flush();
        $this->addFlash('success', 'Submission deleted successfully');
    } else {
        $this->addFlash('error', 'Invalid CSRF token');
    }

    return $this->redirectToRoute('Front_Submission');
}

#[Route('/submission/edit/{idSubmission}', name: 'Front_Submission_edit', methods: ['GET', 'POST'])]
public function edit(
    Request $request,
    Submission $submission,
    EntityManagerInterface $entityManager,
    CarRepository $carRepository
): Response {
    // Get current VIN code from associated car
    $currentVin = $submission->getCar() ? $submission->getCar()->getVinCode() : '';

    $form = $this->createForm(FrontSubmissionType::class, $submission);
    $form->get('vinCode')->setData($currentVin); // Pre-fill VIN code

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle VIN code changes
        $newVin = $form->get('vinCode')->getData();
        
        if ($newVin !== $currentVin) {
            $car = $carRepository->findOneBy(['vinCode' => $newVin]);
            if (!$car) {
                $this->addFlash('error', 'No car found with this VIN code');
                return $this->redirectToRoute('Front_Submission_edit', [
                    'idSubmission' => $submission->getIdSubmission()
                ]);
            }
            $submission->setCar($car);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Submission updated successfully!');
        return $this->redirectToRoute('Front_Submission');
    }

    return $this->render('frontend/submission/edit.html.twig', [
        'submission' => $submission,
        'form' => $form->createView(),
    ]);
}
}