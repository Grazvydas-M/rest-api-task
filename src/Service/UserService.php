<?php

namespace App\Service;

use App\Dto\Pagination;
use App\Dto\UserRegisterDto;
use App\Entity\Position;
use App\Entity\User;
use App\Exception\PositionNotFoundException;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageService $imageService,
        private PositionRepository $positionRepository,
    ) {
    }

    /**
     * @throws PositionNotFoundException
     */
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

        $this->userRepository->save($user, true);

        return $user;
    }

    public function getPagination(int $page): Pagination
    {
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $users = $this->userRepository->findBy([], null, $limit, $offset);
        $totalUsers = $this->userRepository->count();
        $hasMore = ($page * $limit) < $totalUsers;

        return new Pagination($users, $hasMore);
    }

    /**
     * @throws PositionNotFoundException
     */
    private function getPositionOrFail(int $positionId): Position
    {
        $position = $this->positionRepository->find($positionId);

        if (!$position) {
            throw new PositionNotFoundException('Position with id ' . $positionId . ' not found');
        }

        return $position;
    }
}
