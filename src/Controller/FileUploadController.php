<?php

namespace App\Controller;

use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Attribute\Route;

class FileUploadController extends AbstractController
{
    public function __construct(private readonly UploadService $uploadService)
    {
    }

    #[Route('/api/upload', name: 'upload', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        $file = $request->files->get('file');

        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $path = $this->uploadService->uploadUserImage($file);

            return $this->json(['path' => $path], Response::HTTP_CREATED);
        } catch (UnsupportedMediaTypeHttpException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }
    }
}
