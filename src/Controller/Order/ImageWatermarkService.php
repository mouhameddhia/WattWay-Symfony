<?php

namespace App\Controller\Order;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageWatermarkService
{
    private $imageManager;

    public function __construct()
    {
        // Use Driver object instead of array config
        $this->imageManager = new ImageManager(new Driver());
    }

    public function applyWatermarkBehind(string $originalPath, string $watermarkPath, string $outputPath): void
    {
        $original = $this->imageManager->read($originalPath);
        $watermark = $this->imageManager->read($watermarkPath)
            ->resize($original->width(), $original->height());

        // Create a blank canvas and apply watermark first
        $canvas = $this->imageManager->create($original->width(), $original->height());
        $canvas->place($watermark, 'center');
        $canvas->place($original, 'center');

        $canvas->toJpeg(90)->save($outputPath); // Save as JPEG with 90% quality
    }
}
