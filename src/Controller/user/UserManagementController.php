<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BanUserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;




class UserManagementController extends AbstractController
{
   


#[Route('/list', name: 'user_list')]
public function list(
    Request $request,
    UserRepository $userRepository,
    \Knp\Component\Pager\PaginatorInterface $paginator
): Response {

    $userRepository->clearExpiredBans();
 
    $users = $userRepository->findByRole('CLIENT');
   
    if ($request->query->has('search')) {
        $users = $userRepository->searchUsers($request->query->get('search'));
    }
    
   
    if ($request->query->has('sort')) {
        $users = $userRepository->sortUsers(
            $request->query->get('sort'),
            $request->query->get('direction', 'ASC')
        );
    }
    
  
    if ($request->query->has('filter')) {
        $users = $userRepository->filterUsers(
            $request->query->get('filter'),
            $request->query->get('value')
        );
    }

    // Paginate the array results (keeping your exact methods)
    $pagination = $paginator->paginate(
        $users, // Your pre-filtered array
        $request->query->getInt('page', 1), // Page number
        10 
    );

    return $this->render('backend/user/listUser.html.twig', [
        'pagination' => $pagination,
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


    #[Route('/ban/{idUser}', name: 'user_ban', methods: ['GET', 'POST'])]
public function ban(int $idUser, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
{
    $user = $userRepository->find($idUser);
    
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    $form = $this->createFormBuilder()
        ->add('duration', ChoiceType::class, [
            'choices' => [
                '1 minute' => '1 minute',
                '5 minutes' => '5 minutes',
                '1 hour' => '1 hour',
                '1 day' => '1 day',
                '1 week' => '1 week',
                'Permanent' => 'permanent',
                'Custom duration' => 'custom'
            ],
            'attr' => [
                'class' => 'form-control',
                'onchange' => "toggleCustomDate(this.value)"
            ]
        ])
        ->add('customDate', DateTimeType::class, [
            'required' => false,
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'form-control datetimepicker',
                'style' => 'display: none;'
            ],
            'format' => 'yyyy-MM-dd HH:mm'
        ])
        ->add('reason', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        
        $user->setIsBanned(true);
        $user->setBanReason($data['reason']);
        
        if ($data['duration'] === 'custom' && $data['customDate']) {
            $user->setBanUntil($data['customDate']);
        } elseif ($data['duration'] !== 'permanent') {
            $banUntil = new \DateTime('+' . $data['duration']);
            $user->setBanUntil($banUntil);
        } else {
            $user->setBanUntil(null); // Permanent ban
        }

        $em->flush();

        $this->addFlash('success', 'User has been banned.');
        return $this->redirectToRoute('user_list');
    }

    return $this->render('backend/user/ban.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}

#[Route('/unban/{idUser}', name: 'user_unban', methods: ['GET', 'POST'])]
public function unban(int $idUser, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
{
    $user = $userRepository->find($idUser);
    
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }
    
    // Handle CSRF token if it's a POST request
    if ($request->isMethod('POST')) {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('unban'.$idUser, $submittedToken)) {
            throw new AccessDeniedException('Invalid CSRF token');
        }
    }
    
    $user->setIsBanned(false);
    $user->setBanUntil(null);
    $user->setBanReason(null);
    
    $em->flush();

    $this->addFlash('success', 'User has been unbanned successfully.');
    return $this->redirectToRoute('user_list');
}
}
