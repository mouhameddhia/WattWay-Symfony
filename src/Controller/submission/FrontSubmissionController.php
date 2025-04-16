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
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\ResponseRepository;

#[Route('/Front/submission')]
final class FrontSubmissionController extends AbstractController
{
    #[Route(name: 'app_Fsubmission_index', methods: ['GET', 'POST'])]
    public function index(SubmissionRepository $submissionRepository, CarRepository $carRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create new submission form
        $submission = new Submission();
        $form = $this->createForm(FrontSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vinCode = $form->get('vinCode')->getData();
            
            if (empty($vinCode)) {
                $this->addFlash('error', 'VIN code is required.');
                return $this->redirectToRoute('app_Fsubmission_index');
            }
            
            $car = $carRepository->findOneByVinCode($vinCode);
            
            if (!$car) {
                $this->addFlash('error', 'Car with this VIN code does not exist.');
                return $this->redirectToRoute('app_Fsubmission_index');
            }
            
            // Set the car ID to the submission
            $submission->setIdCar($car->getIdCar());
            
            try {
                $entityManager->persist($submission);
                $entityManager->flush();
                $this->addFlash('success', 'Submission created successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while creating the submission.');
            }
            
            return $this->redirectToRoute('app_Fsubmission_index', [], Response::HTTP_SEE_OTHER);
        }

        // Filter submissions by idUser if provided
        $userId = $request->query->get('userId');
        $submissions = $submissionRepository->findAll();

        if ($userId) {
            foreach ($submissions as $submission) {
                $submission->isEditable = ($submission->getIdUser() == $userId);
            }
        } else {
            foreach ($submissions as $submission) {
                $submission->isEditable = false;
            }
        }

        // Fetch all submissions and mark the ones related to the userId
        $userId = $request->query->get('userId');
        $submissions = $submissionRepository->findAll();

        foreach ($submissions as $submission) {
            $submission->isRelatedToUser = ($userId && $submission->getIdUser() == $userId);
        }

        // Fetch car details for each submission
        foreach ($submissions as $submission) {
            $car = $carRepository->find($submission->getIdCar());
            if ($car) {
                $submission->carDetails = [
                    'img' => $car->getImgCar(),
                    'year' => $car->getYearCar(),
                    'brand' => $car->getBrandCar(),
                ];
            }

            $responses = $responseRepository->findBySubmissionId($submission->getIdSubmission());
            $submission->responses = $responses;
        }

        return $this->render('frontend/submission/index.html.twig', [
            'submissions' => $submissions,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_Fsubmission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CarRepository $carRepository): Response
    {
        $submission = new Submission();
        $form = $this->createForm(FrontSubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vinCode = $form->get('vinCode')->getData();
            
            if (empty($vinCode)) {
                $this->addFlash('error', 'VIN code is required.');
                return $this->redirectToRoute('app_Fsubmission_new');
            }
            
            $car = $carRepository->findOneByVinCode($vinCode);
            
            if (!$car) {
                $this->addFlash('error', 'Car with this VIN code does not exist.');
                return $this->redirectToRoute('app_Fsubmission_new');
            }
            
            // Set the car ID to the submission
            $submission->setIdCar($car->getIdCar());
            
            try {
                $entityManager->persist($submission);
                $entityManager->flush();
                $this->addFlash('success', 'Submission created successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while creating the submission.');
            }

            return $this->redirectToRoute('app_Fsubmission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontend/submission/new.html.twig', [
            'submission' => $submission,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubmission}', name: 'app_Fsubmission_show', methods: ['GET'])]
    public function show(Submission $submission): Response
    {
        return $this->render('frontend/submission/show.html.twig', [
            'submission' => $submission,
        ]);
    }

    #[Route('/{idSubmission}/edit', name: 'app_Fsubmission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Submission $submission, EntityManagerInterface $entityManager, CarRepository $carRepository): Response
    {
        // Get the current car's VIN code
        $currentCar = $carRepository->find($submission->getIdCar());
        $initialVinCode = $currentCar ? $currentCar->getVinCode() : '';

        $form = $this->createForm(FrontSubmissionType::class, $submission, [
            'car_repository' => $carRepository
        ]);

        // Set the initial VIN code value
        $form->get('vinCode')->setData($initialVinCode);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vinCode = $form->get('vinCode')->getData();
            
            if (empty($vinCode)) {
                $this->addFlash('error', 'VIN code is required.');
                if ($request->isXmlHttpRequest()) {
                    return $this->render('frontend/submission/_edit_form.html.twig', [
                        'form' => $form,
                        'submission' => $submission
                    ]);
                }
                return $this->redirectToRoute('app_Fsubmission_edit', ['idSubmission' => $submission->getIdSubmission()]);
            }
            
            $car = $carRepository->findOneByVinCode($vinCode);
            
            if (!$car) {
                $this->addFlash('error', 'Car with this VIN code does not exist.');
                if ($request->isXmlHttpRequest()) {
                    return $this->render('frontend/submission/_edit_form.html.twig', [
                        'form' => $form,
                        'submission' => $submission
                    ]);
                }
                return $this->redirectToRoute('app_Fsubmission_edit', ['idSubmission' => $submission->getIdSubmission()]);
            }
            
            // Set the car ID to the submission
            $submission->setIdCar($car->getIdCar());
            
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Submission updated successfully!');
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(['success' => true]);
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while updating the submission.');
                if ($request->isXmlHttpRequest()) {
                    return $this->render('frontend/submission/_edit_form.html.twig', [
                        'form' => $form,
                        'submission' => $submission
                    ]);
                }
            }

            return $this->redirectToRoute('app_Fsubmission_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('frontend/submission/_edit_form.html.twig', [
                'form' => $form,
                'submission' => $submission
            ]);
        }

        return $this->render('frontend/submission/edit.html.twig', [
            'submission' => $submission,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubmission}', name: 'app_Fsubmission_delete', methods: ['POST'])]
    public function delete(Request $request, Submission $submission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$submission->getIdSubmission(), $request->get('_token'))) {
            $entityManager->remove($submission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Fsubmission_index', [], Response::HTTP_SEE_OTHER);
    }
}
