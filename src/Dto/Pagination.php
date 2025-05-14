<?php

namespace App\Dto;

use App\Entity\User;

readonly class Pagination
{
    /**
     * @param User[] $users
     */
    public function __construct(
        private array $users,
        private bool $hasMore
    ) {
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function hasMore(): bool
    {
        return $this->hasMore;
    }
}
