<?php

namespace App\Service;

use Exception;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

use function Tinify\fromFile;
use function Tinify\setKey;

class ImageService
{
    private string $publicDir = __DIR__ . '/../../public';

    public function cropAndOptimizeImage(string $relativePath): string
    {
        $imagine = new Imagine();

        $uploadDir = rtrim($this->publicDir . '/uploads', '/');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fullPath = $this->publicDir . $relativePath;
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Image not found at path: $relativePath");
        }

        $image = $imagine->open($fullPath);

        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();
        $startX = max(0, intval(($width - 100) / 2));
        $startY = max(0, intval(($height - 100) / 2));

        $cropped = $image->crop(new Point($startX, $startY), new Box(100, 100));
        $fileName = 'user' . uniqid() . '.jpg';
        $filePath = $uploadDir . '/' . $fileName;
        $cropped->save($filePath, ['jpeg_quality' => 85]);

        $this->optimizeImageWithTinyPng($filePath);

        return '/uploads/' . $fileName;
    }

    private function optimizeImageWithTinyPng(string $filePath): void
    {
        try {
            setKey('hhkj1jSx8N7QN1PQGN4C6sFb8pP9RXrT');

            $source = fromFile($filePath);
            $source->toFile($filePath);
        } catch (Exception $exception) {
        }
    }
}
