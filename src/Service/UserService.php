<?php

namespace App\Service;

use App\Dto\UserRegisterDto;
use App\Entity\User;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageService $imageService,
    )
    {
    }

    public function createUser(UserRegisterDto $userDto): User
    {
        $photo = $this->imageService->cropAndOptimizeImage($userDto->getPhoto());

        $user = new User();
        $user->setName($userDto->getName())
            ->setEmail($userDto->getEmail())
            ->setPhone($userDto->getPhone())
            ->setPhoto($photo)
            ->setPositionId((int)$userDto->getPositionId());

        $this->userRepository->save($user);

        return $user;
    }
}