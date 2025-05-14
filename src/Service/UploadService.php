<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class UploadService
{
    private const array ALLOWED_MIME_TYPES = ['image/jpeg', 'image/jpg'];

    public function __construct(private readonly FilesystemOperator $uploadsStorage)
    {
    }

    public function uploadUserImage(UploadedFile $file): string
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new UnsupportedMediaTypeHttpException('Only JPEG images allowed.');
        }

        $filename = 'uncropped_user_' . uniqid('', true) . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'rb');
        $this->uploadsStorage->writeStream($filename, $stream);
        fclose($stream);

        return '/users/uploads/' . $filename;
    }
}
