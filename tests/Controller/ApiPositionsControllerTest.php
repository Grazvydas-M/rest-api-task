<?php

namespace App\Tests\Controller;

use App\Tests\Fixtures\TestPositionFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiPositionsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadTestFixtures();
    }

    public function testListPositions(): void
    {
        $this->client->request('GET', '/api/positions');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEquals('Developer', $data[0]['name']);
    }

    protected function loadTestFixtures(): void
    {
        $em = static::getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->addFixture(new TestPositionFixture());

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->purge();
        $executor->execute($loader->getFixtures());
    }
}