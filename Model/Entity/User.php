<?php

namespace App\Model\Entity;

use Texier\Framework\Entity;

class User extends Entity
{
    const MEMBER = 1;
    const ADMIN = 2;

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private int $role;
    private \DateTime $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setRole(int $role)
    {
        $this->role = $role;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}