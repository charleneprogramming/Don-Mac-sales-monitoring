<?php

namespace App\Domain\Users;

class User
{
    private ?int $id;

    private string $username;

    private string $password;

    private bool $isAdmin;

    public function __construct(
        ?int $id = null,
        ?string $username = null,
        ?string $password = null,
        ?bool $isAdmin = null
    ) {
        $this->id = $id ?? 0;
        $this->username = $username ?? '';
        $this->password = $password ?? '';
        $this->isAdmin = $isAdmin ?? false;
    }

    public function getID(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }
}
