<?php

// src/Twig/AppExtension.php
// src/Twig/AvatarExtension.php
// src/Twig/AvatarExtension.php
namespace App\Twig;

use App\Service\AvatarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AvatarExtension extends AbstractExtension
{
    public function __construct(private AvatarService $avatarService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generate_avatar', [$this->avatarService, 'generateAvatar']),
        ];
    }
}