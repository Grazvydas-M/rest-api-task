<?php

namespace App\Tests\Service;

use App\Dto\UserRegisterDto;
use App\Entity\Position;
use App\Entity\User;
use App\Exception\PositionNotFoundException;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;
use App\Service\ImageService;
use App\Service\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private ImageService&MockObject $imageService;

    private UserRepository&MockObject $userRepository;

    private PositionRepository&MockObject $positionRepository;

    private UserService $userService;

    public function setUp(): void
    {
        $this->imageService = $this->createMock(ImageService::class);
        $this->positionRepository = $this->createMock(PositionRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->userService = new UserService($this->userRepository, $this->imageService, $this->positionRepository);
    }

    public function testCreateUser(): void
    {
        $dto = new UserRegisterDto('Jimmy', 'jimmy@jimmy.com', '+37061234567', 1);
        $dto->setPhoto('/default.jpg');

        $this->imageService->expects($this->once())
            ->method('cropAndOptimizeImage')
            ->with('/default.jpg')
            ->willReturn('/uploads/optimized.jpg');

        $position = new Position();
        $position->setName('Lawyer');

        $this->positionRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($position);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class), true);

        $user = $this->userService->createUser($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($dto->getName(), $user->getName());
        $this->assertSame('jimmy@jimmy.com', $user->getEmail());
        $this->assertSame('+37061234567', $user->getPhone());
        $this->assertSame('/uploads/optimized.jpg', $user->getPhoto());
        $this->assertSame($position, $user->getPosition());
    }

    public function testCreateUserShouldFailWhenPositionNotFound(): void
    {
        $dto = new UserRegisterDto('Jimmy', 'jimmy@jimmy.com', '+37061234567', 1);
        $dto->setPhoto('/default.jpg');

        $this->imageService->expects($this->once())
            ->method('cropAndOptimizeImage')
            ->with('/default.jpg')
            ->willReturn('/uploads/optimized.jpg');

        $this->positionRepository->expects($this->once())
            ->method('find')
            ->with($dto->getPositionId())
            ->willReturn(null);

        $this->expectException(PositionNotFoundException::class);
        $this->expectExceptionMessage('Position with id ' . $dto->getPositionId() . ' not found');

        $this->userService->createUser($dto);


    }
}
