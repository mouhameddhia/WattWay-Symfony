<?php
// src/Controller/AvatarController.php
// src/Controller/AvatarController.php
namespace App\Controller\user;

use App\Service\AvatarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvatarController extends AbstractController
{
    public function __construct(private AvatarService $avatarService) {}

    #[Route('/avatar/default/{seed}/{size}', name: 'app_default_avatar')]
    public function defaultAvatar(string $seed, int $size): Response
    {
        $avatarUrl = $this->avatarService->generateCarAvatar($seed, $size);
        return $this->redirect($avatarUrl);
    }
}