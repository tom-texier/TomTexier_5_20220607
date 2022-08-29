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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setId($id)
    {
        if(!is_int($id)) {
            $id = intval($id);
        }
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRole($role)
    {
        if(!is_int($role)) {
            $role = intval($role);
        }
        $this->role = $role;
    }

    public function setCreatedAt($createdAt)
    {
        if(!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }
        $this->createdAt = $createdAt;
    }
}
