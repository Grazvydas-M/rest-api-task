<?php

namespace App\Tests\Fixtures;

use App\Entity\Position;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestUserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Fixture User');
        $user->setEmail('fixture@fixture.com');
        $user->setPhone('+37061234567');
        $user->setPhoto('/uploads/default.jpg');

        $position = $this->getReference(TestPositionFixture::POSITION_REFERENCE, Position::class);
        $user->setPosition($position);

        $manager->persist($user);
        $manager->flush();
    }
}