<?php

namespace App\Dto;

readonly class Pagination
{

    public function __construct(
        private array $users,
        private bool  $hasMore
    )
    {
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