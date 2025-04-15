<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function Tinify\fromFile;
use function Tinify\setKey;

class ImageService
{
    public function cropAndOptimizeImage(UploadedFile $file): string
    {
        $imagine = new Imagine();

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image = $imagine->open($file->getRealPath());

        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();
        $startX = max(0, intval(($width - 70) / 2));
        $startY = max(0, intval(($height - 70) / 2));

        $cropped = $image->crop(new Point($startX, $startY), new Box(70, 70));
        $fileName = uniqid() . '.jpg';
        $filePath = $uploadDir . '/' . $fileName;
        $cropped->save($filePath, ['jpeg_quality' => 85]);

        $this->optimizeImageWithTinyPng($filePath);

        return str_replace(__DIR__ . '/../../public', '', $filePath);
    }

    private function optimizeImageWithTinyPng(string $filePath): void
    {
        try {
            setKey('hhkj1jSx8N7QN1PQGN4C6sFb8pP9RXrT');

            $source = fromFile($filePath);
            $source->toFile($filePath);
        } catch (\Tinify\Exception $exception) {}
    }
}