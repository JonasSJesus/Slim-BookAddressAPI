<?php

namespace Agenda\Entities;

class Users
{
    public readonly int $id;
    public readonly string $name;
    public readonly int $phone_number;
    public readonly string $email;
    public readonly string $password;
    
    public function __construct(string $name, int $phone_number, string $email, string $password) {
        $this->name = $name;
        $this->phone_number = $phone_number;
        $this->email = $email;
        $this->password = $password;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
