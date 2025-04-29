<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 60)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\+3706\d{7}$/',
        message: 'Phone number must start with +3706 and contain 7 digits after that.'
    )]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    public int|string $positionId;

    #[Assert\NotNull]
    #[Assert\File(
        maxSize: "5M",
        mimeTypes: ["image/jpeg", "image/jpg"],
        maxSizeMessage: "Photo size must not exceed 5MB.",
        mimeTypesMessage: "Please upload a valid JPEG image."
    )]
    public ?UploadedFile $photo = null;

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

    public function getPhoto(): UploadedFile
    {
        return $this->photo;
    }

    public function setPhoto(?UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function setPositionId($positionId): void
    {
        $this->positionId = $positionId;
    }


}