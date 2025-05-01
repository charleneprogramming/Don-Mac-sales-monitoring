<?php

namespace App\Domain\Users;

class User
{
    private ?int $id;

    private string $username;

    private string $password;

    private ?string $name;

    private ?string $contactNumber;

    public function __construct(
        ?int $id = null,
        ?string $username = null,
        ?string $password = null,
        ?string $name = null,
        ?string $contactNumber = null
    ) {
        $this->id = $id ?? 0;
        $this->username = $username ?? '';
        $this->password = $password ?? '';
        $this->name = $name ?? '';
        $this->contactNumber = $contactNumber ?? '';
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContactNumber(): ?string
    {
        return $this->contactNumber;
    }
}
