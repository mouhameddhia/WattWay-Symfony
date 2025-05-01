<?php


// src/Service/AvatarService.php
namespace App\Service;

class AvatarService
{
    public function generateAvatar(
        string $seed, 
        int $size = 100,
        string $style = 'identicon',
        ?string $backgroundColor = 'b6e3f4'
    ): string {
        $params = [
            'seed' => $seed,
            'size' => $size,
            'backgroundColor' => $backgroundColor,
            'radius' => 50,
            'scale' => 80
        ];

        return 'https://api.dicebear.com/7.x/'.$style.'/svg?'.http_build_query(array_filter($params));
    }
}