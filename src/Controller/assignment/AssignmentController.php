<?php

namespace App\Controller\assignment;

use App\Entity\Assignment;
use App\Form\AssignmentType;
use App\Entity\Mechanic;
use App\Entity\AssignmentMechanics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AssignmentRepository;
use Symfony\Component\Mailer\MailerInterface;
use Fusonic\MessengerMailerBundle\Component\Mime\TemplatedAttachmentEmail;
use App\Service\AssemblyAIService;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\GoogleCalendarService;
use Psr\Log\LoggerInterface;


#[Route('/assignment')]
final class AssignmentController extends AbstractController
{
    #[Route(name: 'app_assignment_index', methods: ['GET'])]
    public function index(Request $request, AssignmentRepository $repo): Response
    {
        $q    = $request->query->get('q', '');
        $sort = $request->query->get('sort', '');
        $dir  = strtoupper($request->query->get('dir', 'DESC'));

        if ($q !== '') {
            $assignments = $repo->search($q);
        } elseif ($sort === 'date') {
            $assignments = $repo->sortByDate($dir);
        } elseif ($sort === 'status') {
            $assignments = $repo->sortByStatus($dir);
        } elseif ($sort === 'car') {
            $assignments = $repo->sortByCar($dir);
        } else {
            // default ordering
            $assignments = $repo->sortByDate('DESC');
        }

        return $this->render('backend/assignment/index.html.twig', [
            'assignments'   => $assignments,
            'currentSearch' => $q,
            'currentSort'   => $sort,
            'currentDir'    => $dir,
        ]);
    }


    #[Route('/new', name: 'app_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, GoogleCalendarService $calendarService): Response
    {
        $assignment = new Assignment();
        $form = $this->createForm(AssignmentType::class, $assignment);
        $mechanics = $entityManager->getRepository(Mechanic::class)->findAll();
        
        $form->handleRequest($request);
        
        $error = null;

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Please fill all required fields correctly');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($assignment);
                $entityManager->flush();
                
                $selectedMechanicIds = $request->request->all()['assignment']['mechanics'] ?? [];
                
                $entityManager->clear(AssignmentMechanics::class);
                
                foreach ($selectedMechanicIds as $mechanicId) {
                    $mechanic = $entityManager->getRepository(Mechanic::class)->find($mechanicId);
                    if ($mechanic) {
                        $assignmentEntity = $entityManager->find(Assignment::class, $assignment->getIdAssignment());
                        
                        $assignmentMechanic = new AssignmentMechanics();
                        $assignmentMechanic->setIdMechanic($mechanic);
                        $assignmentMechanic->setIdAssignment($assignmentEntity);
                        $assignmentEntity->addAssignmentMechanic($assignmentMechanic);
                        $entityManager->persist($assignmentMechanic);
                    }
                }
                
                $entityManager->flush();

                foreach ($selectedMechanicIds as $mechanicId) {
                    try {
                        $mechanic = $entityManager->getRepository(Mechanic::class)->find($mechanicId);
                        $email = (new TemplatedAttachmentEmail())
                        ->to($mechanic->getEmailMechanic())                      
                        ->from('wattwayorg@gmail.com')
                        ->subject('🔧 New Assignment: '.$assignment->getDescriptionAssignment())
                        ->htmlTemplate('emails/assignment_notifications.html.twig')
                        ->context([
                            'mechanic'   => $mechanic,
                            'assignment' => $assignment,
                        ])
                    ;
                        $mailer->send($email);
                    } catch (\Throwable $e) {
                        $this->addFlash('error', 'Failed to send notification: '.$e->getMessage());
                    }
                }
                try {
                    if ($calendarService->isAuthenticated()) {
                        $start = $assignment->getDateAssignment();
                        $end   = clone $start;

                        $eventUrl = $calendarService->createEvent(
                            $assignment->getDescriptionAssignment(),
                            $start,
                            $end,
                            'New assignment created for mechanic'
                        );
                        // Store URL if needed later
                        $assignment->setCalendarEventUrl($eventUrl);
                        $entityManager->flush();
                        
                        // Option 1: Redirect to calendar event
                        return $this->redirect($eventUrl);
                        
                        // Option 2: Continue to show success message
                        //$this->addFlash('success', 'Assignment and calendar event created!');
                    } else {
                        // Calendar not connected: assignment is still created
                        $this->addFlash('warning', 'Assignment created, but Google Calendar isn’t connected. Connect later to sync events.');
                    }
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'Assignment created but calendar event failed: '.$e->getMessage());
                }
                
                return $this->redirectToRoute('app_assignment_index');
                

            } catch (\Exception $e) {
                error_log($e->getMessage());
                
                $error = 'An error occurred while saving the assignment. Please try again.';
                
                $this->addFlash('error', $error);
            }
        }

        return $this->render('backend/assignment/new.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
            'mechanics' => $mechanics,
            'error' => $error,
            'isGoogleConnected' => $calendarService->isAuthenticated()
        ]);
    }

    #[Route('/{idAssignment}', name: 'app_assignment_show', methods: ['GET'])]
    public function show(Assignment $assignment): Response
    {
        return $this->render('backend/assignment/show.html.twig', [
            'assignment' => $assignment,
        ]);
    }

    #[Route('/{idAssignment}/edit', name: 'app_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assignment $assignment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssignmentType::class, $assignment);
        $mechanics = $entityManager->getRepository(Mechanic::class)->findAll();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle mechanics selection
            $selectedMechanicIds = $request->request->all()['assignment']['mechanics'] ?? [];
            
            // Clear existing mechanics
            foreach ($assignment->getAssignmentMechanics() as $am) {
                $assignment->removeAssignmentMechanic($am);
                $entityManager->remove($am);
            }
            
            // Add selected mechanics
            foreach ($selectedMechanicIds as $mechanicId) {
                $mechanic = $entityManager->getRepository(Mechanic::class)->find($mechanicId);
                if ($mechanic) {
                    $assignmentMechanic = new AssignmentMechanics();
                    $assignmentMechanic->setIdMechanic($mechanic);
                    $assignmentMechanic->setIdAssignment($assignment);
                    $assignment->addAssignmentMechanic($assignmentMechanic);
                    $entityManager->persist($assignmentMechanic);
                }
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/assignment/edit.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
            'mechanics' => $mechanics
        ]);
    }

    #[Route('/{idAssignment}', name: 'app_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, Assignment $assignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assignment->getIdAssignment(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($assignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/transcribe', name: 'app_assignment_transcribe', methods: ['POST'])]
    public function transcribe(
        Request $request,
        AssemblyAIService $service,
        LoggerInterface $logger
    ): JsonResponse {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('audio');
        $logger->debug('Transcribe request received', [
            'files' => $request->files->all(),
            'headers' => $request->headers->all()
        ]);
        if (!$file) {
            $logger->error('No audio file received', [
                'files' => $request->files->all()
            ]);
            return $this->json(['error' => 'No audio file received'], 400);
        }

        try {
            // Validate file
            if (!$file->isValid()) {
                throw new FileException($file->getErrorMessage());
            }

            // Move to temp file
            $tempDir = sys_get_temp_dir();
            if (!is_writable($tempDir)) {
                throw new FileException("Temp directory not writable");
            }

            $filename = uniqid('audio_', true) . '.' . $file->guessExtension();
            $moved = $file->move($tempDir, $filename);
            $filePath = $moved->getPathname();

            // Upload to AssemblyAI
            $uploadUrl = $service->uploadAudio($filePath);
            if (!$uploadUrl) {
                throw new \RuntimeException("Upload failed");
            }

            // Transcribe
            $text = $service->transcribe($uploadUrl);
            if (!$text) {
                throw new \RuntimeException("Transcription failed");
            }

            return $this->json(['text' => $text]);

        } catch (\Throwable $e) {
            $logger->error('Transcription error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->json([
                'error' => 'Transcription failed',
                'details' => $e->getMessage()
            ], 500);
            
        } finally {
            // Clean up
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

}