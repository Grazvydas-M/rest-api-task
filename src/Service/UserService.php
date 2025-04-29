<?php

namespace App\Service;

use App\Dto\UserRegisterDto;
use App\Entity\Position;
use App\Entity\User;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;
use http\Exception\InvalidArgumentException;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageService $imageService,
        private PositionRepository $positionRepository,
    )
    {
    }

    public function createUser(UserRegisterDto $userDto): User
    {
        $photo = $this->imageService->cropAndOptimizeImage($userDto->getPhoto());
        $position = $this->getPositionOrFail($userDto->getPositionId());

        $user = new User();
        $user->setName($userDto->getName())
            ->setEmail($userDto->getEmail())
            ->setPhone($userDto->getPhone())
            ->setPhoto($photo)
            ->setPosition($position);

        $this->userRepository->save($user);

        return $user;
    }

    private function getPositionOrFail(int $positionId): Position
    {
        $position = $this->positionRepository->find($positionId);

        if (!$position) {
            throw new InvalidArgumentException('Position with id ' . $positionId . ' not found');
        }

        return $position;
    }
}