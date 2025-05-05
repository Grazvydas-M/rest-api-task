<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

//    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'position')]
//    private Collection $users;
//
//    public function __construct()
//    {
//        $this->users = new ArrayCollection();
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Position
    {
        $this->name = $name;

        return $this;
    }

//    public function getUsers(): Collection
//    {
//        return $this->users;
//    }
}
