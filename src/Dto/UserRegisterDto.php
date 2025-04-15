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
        pattern: '/^\+380\d{9}$/',
        message: 'Phone number must start with +380 and contain 9 digits after that.'
    )]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    public $position_id;

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

    public function getPositionId()
    {
        return $this->position_id;
    }

    public function getPhoto(): UploadedFile
    {
        return $this->photo;
    }

    public function setPhoto(?UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function setPositionId($position_id): void
    {
        $this->position_id = $position_id;
    }


}