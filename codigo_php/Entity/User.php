<?php

class User
{
    public const ROLE_REGISTERED = 'registered';
    public const ROLE_AUTHORIZED = 'authorized';
    public const ROLE_SUPERADMIN = 'superadmin';

    private int $id;
    private string $email;
    private string $username;
    private bool $enabled;
    private DateTime $lastAccess;
    private ?string $password;
    private bool $isSuperAdmin;

    public function __construct(
        int $id,
        string $email,
        string $username,
        bool $enabled,
        DateTime $lastAccess,
        ?string $password,
        bool $isSuperAdmin
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
        $this->enabled = $enabled;
        $this->lastAccess = $lastAccess;
        $this->password = $password;
        $this->isSuperAdmin = $isSuperAdmin;

    }

    public function id(): int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function lastAccess(): DateTime
    {
        return $this->lastAccess;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function isSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }
}