<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    private const int USERS_COUNT = 45;

    public function __construct(
        private readonly PositionRepository $positionRepository,
        private readonly UserRepository     $userRepository
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

        for ($i = 0; $i < self::USERS_COUNT; $i++) {
            $user = new User();
            $user->setName($faker->name());
            $user->setEmail($faker->email());
            $user->setPhone($faker->regexify('/\+3706\d{7}/'));
            $user->setPhoto('/uploads/default.jpg');
            $user->setPosition($positions[array_rand($positions)]);

            $this->userRepository->save($user);

        }

        if (isset($user)){
            $this->userRepository->save($user, true);
        }
    }
}
