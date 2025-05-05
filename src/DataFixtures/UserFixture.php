<?php

namespace App\DataFixtures;

use App\Entity\Position;
use App\Entity\User;
use App\Repository\PositionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PositionRepository     $positionRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('lt_LT');
        $positions = $this->positionRepository->findAll();

        if (empty($positions)) {
            throw new \Exception('No positions found. Please seed positions before users.');
        }

        for ($i = 0; $i < 45; $i++) {
            $user = new User();
            $user->setName($faker->name());
            $user->setEmail($faker->email());
            $user->setPhone($faker->regexify('/\+3706\d{7}/'));
            $user->setPhoto('/uploads/default.jpg');
            $user->setPosition($positions[array_rand($positions)]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
