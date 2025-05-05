<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:seed-users',
    description: 'Generates 45 fake users and saves them to the database.',
)]
class SeedUsersCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();

        for ($i = 0; $i < 45; $i++) {
            $user = new User();
            $user->setName($faker->name);
            $user->setEmail($faker->email);

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        $output->writeln('45 users have been successfully created.');

        return Command::SUCCESS;
    }
}