<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 60)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\+3706\d{7}$/',
        message: 'Phone number must start with +3706 and contain 7 digits after that.'
    )]
    private string $phone;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    private int|string $positionId;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $photo = null;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        int $positionId,
        ?string $photo = null
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->positionId = $positionId;
        $this->photo = $photo;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPositionId(): int|string
    {
        return $this->positionId;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    public function setPositionId($positionId): void
    {
        $this->positionId = $positionId;
    }


}