<?php

namespace App\Tests\Fixtures;

use App\Entity\Position;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class TestPositionFixture extends AbstractFixture
{
    public const string POSITION_REFERENCE = 'test-position';

    public function load(ObjectManager $manager): void
    {
        $position = new Position();
        $position->setName('Developer');
        $manager->persist($position);
        $manager->flush();

        $this->addReference(self::POSITION_REFERENCE, $position);
    }
}