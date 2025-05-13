<?php

namespace App\Tests\Service;

use App\Service\ImageService;
use PHPUnit\Framework\TestCase;

class ImageServiceTest extends TestCase
{
    private ImageService $imageService;
    private string $publicDir;
    private string $testUploadsDir;
    private string $testImagePath;
    private string $relativePath;

    protected function setUp(): void
    {
        $this->publicDir = realpath(__DIR__ . '/../../public');
        $this->testUploadsDir = $this->publicDir . '/uploads';

        if (!is_dir($this->testUploadsDir)) {
            mkdir($this->testUploadsDir, 0777, true);
        }

        $this->testImagePath = $this->testUploadsDir . '/test-default.jpg';
        copy(__DIR__ . '/../Fixtures/default.jpg', $this->testImagePath);

        $this->relativePath = '/uploads/test-default.jpg';

        $this->imageService = new ImageService();
    }

    public function testCropAndOptimizeImageReturnsPath(): void
    {
        $resultPath = $this->imageService->cropAndOptimizeImage($this->relativePath);

        $this->assertStringStartsWith('/uploads/user', $resultPath, 'Image path should start with /uploads/user');
        $this->assertStringEndsWith('.jpg', $resultPath, 'Image path should end with .jpg');

        $fullResultPath = $this->publicDir . $resultPath;
        $this->assertFileExists($fullResultPath, 'Cropped and optimized image should exist');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testImagePath)) {
            unlink($this->testImagePath);
        }

        foreach (glob($this->testUploadsDir . '/user*.jpg') as $file) {
            unlink($file);
        }
    }
}
