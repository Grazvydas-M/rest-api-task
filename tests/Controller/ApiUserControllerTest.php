<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Service\ImageService;
use App\Tests\Fixtures\TestPositionFixture;
use App\Tests\Fixtures\TestUserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiUserControllerTest extends WebTestCase
{
    private ImageService&MockObject $imageService;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadTestFixtures();
        $this->imageService = $this->createMock(ImageService::class);
    }

    public function testCreateUser(): void
    {
        static::getContainer()->set(ImageService::class, $this->imageService);

        $this->imageService->method('cropAndOptimizeImage')
            ->willReturn('/uploads/default.jpg');

        $position = static::getContainer()->get('doctrine')->getRepository('App\Entity\Position')->findOneBy(['name' => 'Developer']);

        $this->client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@test.com',
            'password' => 'password',
            'name' => 'User',
            'phone' => '+37061779037',
            'positionId' => $position->getId(),
            'photo' => '/uploads/default.jpg'
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListUsers(): void
    {
        $this->client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals('Fixture User', $data[0]['name']);
    }

    public function testShowUser(): void
    {
        /** @var User $user */
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['name' => 'Fixture User']);

        $this->client->request('GET', '/api/users/' . $user->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($user->getName(), $data['name']);
        $this->assertEquals($user->getEmail(), $data['email']);
        $this->assertEquals($user->getPhone(), $data['phone']);
        $this->assertEquals($user->getPhoto(), $data['photo']);
    }

    protected function loadTestFixtures(): void
    {
        $em = static::getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->addFixture(new TestPositionFixture());
        $loader->addFixture(new TestUserFixture());

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->purge();
        $executor->execute($loader->getFixtures());
    }
}