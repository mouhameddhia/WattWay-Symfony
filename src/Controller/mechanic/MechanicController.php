<?php

namespace App\Controller\mechanic;

use App\Repository\MechanicRepository;
use App\Entity\AssignmentMechanics;
use App\Entity\Mechanic;
use App\Form\MechanicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;




#[Route('/mechanic')]
final class MechanicController extends AbstractController
{
    #[Route(name: 'app_mechanic_index', methods: ['GET'])]
    public function index(Request $request, MechanicRepository $repo, ChartBuilderInterface $chartBuilder): Response
    {
        $q    = $request->query->get('q', '');
        $sort = $request->query->get('sort', '');
        $dir  = strtoupper($request->query->get('dir', 'ASC'));

        if ($q !== '') {
            $mechanics = $repo->search($q);
        } elseif ($sort === 'name') {
            $mechanics = $repo->sortByName($dir);
        } elseif ($sort === 'speciality') {
            $mechanics = $repo->sortBySpeciality($dir);
        } elseif ($sort === 'carsRepaired') {
            $mechanics = $repo->sortByCarsRepaired($dir);
        } else {
            $mechanics = $repo->findAll();
        }
        
        $labels = [];
        $data   = [];
        foreach ($mechanics as $m) {
            $labels[] = $m->getNameMechanic();
            $data[]   = $m->getCarsRepaired();
        }

        // 3) build the Chart.js configuration array
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label'           => 'Cars Repaired',
                        'data'            => $data,
                        'backgroundColor' => 'rgba(78, 115, 223, .5)',
                        'borderColor'     => 'rgba(78, 115, 223, 1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales'     => [
                    'y' => ['beginAtZero' => true],
                ],
            ],
        ];


        return $this->render('backend/mechanic/index.html.twig', [
            'mechanics'    => $mechanics,
            'currentSearch'=> $q,
            'currentSort'  => $sort,
            'currentDir'   => $dir,
            'chartConfig'  => json_encode($chartConfig),
        ]);
    }
        
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new', name: 'app_mechanic_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mechanic = new Mechanic();
        $form = $this->createForm(MechanicType::class, $mechanic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload separately
            $uploadedFile = $request->files->get('mechanic_image');
            if ($uploadedFile) {
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('mechanics_directory'),
                    $newFilename
                );
                $mechanic->setImgMechanic($newFilename);
            }

            $this->entityManager->persist($mechanic);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mechanic_index');
        }

        return $this->render('backend/mechanic/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{idMechanic}/edit', name: 'app_mechanic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mechanic $mechanic): Response
    {
        $oldImage = $mechanic->getImgMechanic();
        $form = $this->createForm(MechanicType::class, $mechanic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $request->files->get('mechanic_image');
            if ($uploadedFile) {
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $this->getParameter('mechanics_directory'),
                    $newFilename
                );
                
                // Delete old image
                if ($oldImage) {
                    $oldPath = $this->getParameter('mechanics_directory').'/'.$oldImage;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $mechanic->setImgMechanic($newFilename);
            }

            $this->entityManager->flush();
            return $this->redirectToRoute('app_mechanic_index');
        }

        return $this->render('backend/mechanic/edit.html.twig', [
            'mechanic' => $mechanic,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{idMechanic}/show', name: 'app_mechanic_show', methods: ['GET'])]
    public function show(Mechanic $mechanic): Response
    {
        return $this->render('backend/mechanic/show.html.twig', [
            'mechanic' => $mechanic,
        ]);
    }

    

    #[Route('/{idMechanic}/delete', name: 'app_mechanic_delete', methods: ['POST'])]
    public function delete(Request $request, Mechanic $mechanic, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token');
        
        if ($this->isCsrfTokenValid('delete' . $mechanic->getIdMechanic(), $token)) {
            // Check if mechanic has any assignments
            $assignmentCount = $entityManager->getRepository(AssignmentMechanics::class)
                ->count(['idMechanic' => $mechanic->getIdMechanic()]);
            
            if ($assignmentCount > 0) {
                $this->addFlash('error', sprintf(
                    'Cannot delete mechanic "%s" - they are currently assigned to %d assignment(s). '.
                    'Please unassign them from all assignments before deleting.',
                    $mechanic->getNameMechanic(),
                    $assignmentCount
                ));
            } else {
                // Only delete if no assignments exist
                try {
                    $entityManager->remove($mechanic);
                    $entityManager->flush();
                    $this->addFlash('success', 'Mechanic deleted successfully');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error during deletion: ' . $e->getMessage());
                }
            }
        } else {
            $this->addFlash('error', 'Invalid security token');
        }
        
        return $this->redirectToRoute('app_mechanic_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/frontend/mechanic', name: 'app_frontend_mechanic_index', methods: ['GET'])]
    public function frontendIndex(EntityManagerInterface $entityManager): Response
    {
        // Debug to check if route is hit
        dump('Route hit!');
        
        $mechanics = $entityManager
            ->getRepository(Mechanic::class)
            ->findAll();
            
        // Debug to check mechanics data
        dump($mechanics);

        return $this->render('frontend/mechanic/index.html.twig', [
            'mechanics' => $mechanics,
        ]);
    }
    #[Route('/mechanic/search', name: 'app_mechanic_search', methods: ['GET'])]
    public function search(Request $request, MechanicRepository $repo): JsonResponse
    {
        $term = $request->query->get('q', '');
        $results = $repo->search($term);
        return $this->json($results);
    }
    #[Route('/mechanic/sort/name', name: 'app_mechanic_sort_name', methods: ['GET'])]
    public function sortByName(Request $request, MechanicRepository $repo): JsonResponse
    {
        $dir = strtoupper($request->query->get('dir', 'ASC'));
        $list = $repo->sortByName($dir);
        return $this->json($list);
    }

    #[Route('/mechanic/sort/speciality', name: 'app_mechanic_sort_speciality', methods: ['GET'])]
    public function sortBySpeciality(Request $request, MechanicRepository $repo): JsonResponse
    {
        $dir = strtoupper($request->query->get('dir', 'ASC'));
        return $this->json($repo->sortBySpeciality($dir));
    }
    #[Route('/mechanic/sort/cars-repaired', name: 'app_mechanic_sort_cars', methods: ['GET'])]
    public function sortByCarsRepaired(Request $request, MechanicRepository $repo): JsonResponse
    {
        $dir = strtoupper($request->query->get('dir', 'ASC'));
        return $this->json($repo->sortByCarsRepaired($dir));
    }

}
