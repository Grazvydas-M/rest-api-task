<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $positions = ['Manager', 'Developer', 'Designer', 'Tester', 'HR'];

        foreach ($positions as $positionName) {
            $position = new Position();
            $position->setName($positionName);
            $manager->persist($position);
        }

        $manager->flush();
    }
}