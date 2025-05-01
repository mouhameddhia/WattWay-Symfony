<?php

namespace App\Controller\submission;

use App\Entity\Response;
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/response')]
final class ResponseController extends AbstractController
{
    #[Route('/filter', name: 'app_response_filter', methods: ['GET'])]
    public function filter(Request $request, ResponseRepository $responseRepository): HttpResponse
    {
        $type = $request->query->get('type');
        $search = $request->query->get('search');

        $responses = $responseRepository->filterAndSearch($type, $search);

        return $this->render('backend/response/_responses_table.html.twig', [
            'responses' => $responses
        ]);
    }

    #[Route(name: 'app_response_index', methods: ['GET'])]
    public function index(
        ResponseRepository $responseRepository,
        Request $request,
        PaginatorInterface $paginator
    ): HttpResponse {
        $query = $responseRepository->createQueryBuilder('r')
            ->orderBy('r.dateResponse', 'DESC');

        $knp_pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number
            5 // Items per page
        );

        return $this->render('backend/response/index.html.twig', [
            'knp_pagination' => $knp_pagination,
        ]);
    }

    #[Route('/new', name: 'app_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): HttpResponse
    {
        $response = new Response();

        // Retrieve idSubmission from the request
        $idSubmission = $request->query->get('idSubmission');
        if ($idSubmission) {
            $response->setIdSubmission($idSubmission);
        }

        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
        }

        return $this->render('backend/response/new.html.twig', [
            'response' => $response,
            'form' => $form,
        ]);
    }

    #[Route('/{idResponse}', name: 'app_response_show', methods: ['GET'])]
    public function show(Response $response): HttpResponse
    {
        return $this->render('backend/response/show.html.twig', [
            'response' => $response,
        ]);
    }

    #[Route('/{idResponse}/edit', name: 'app_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Response $response, EntityManagerInterface $entityManager): HttpResponse
    {
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
        }

        return $this->render('backend/response/edit.html.twig', [
            'response' => $response,
            'form' => $form,
        ]);
    }

    #[Route('/{idResponse}', name: 'app_response_delete', methods: ['POST'])]
    public function delete(Request $request, Response $response, EntityManagerInterface $entityManager): HttpResponse
    {
        if ($this->isCsrfTokenValid('delete'.$response->getIdResponse(), $request->request->get('_token'))) {
            $entityManager->remove($response);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
    }
}
