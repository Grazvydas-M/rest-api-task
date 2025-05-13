<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ImageService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiUserControllerTest extends WebTestCase
{
    private ImageService&MockObject $imageService;
    private $client;
    private $userRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->imageService = $this->createMock(ImageService::class);
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    public function testCreateUser(): void
    {
        static::getContainer()->set(ImageService::class, $this->imageService);

        $this->imageService->method('cropAndOptimizeImage')
            ->willReturn('/uploads/default.jpg');

        $this->client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@test.com',
            'password' => 'password',
            'name' => 'User',
            'phone' => '+37061779037',
            'positionId' => 1,
            'photo' => '/uploads/default.jpg'
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListUsers(): void
    {
        $user = new User();
        $user->setName('User')
            ->setEmail('test@test.com')
            ->setPhone('+37061779037')
            ->setPhoto('/uploads/default.jpg');

        $this->userRepository->method('findAll')
            ->willReturn([$user]);

        $this->client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals('User', $data[0]['name']);
    }
}