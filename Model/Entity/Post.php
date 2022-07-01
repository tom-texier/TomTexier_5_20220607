<?php

namespace App\Model\Entity;

use Texier\Framework\Entity;

class Post extends Entity
{
    private int $id;
    private int $userID;
    private string $title;
    private string $content;
    private string $image;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setUserID(int $userID)
    {
        $this->userID = $userID;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}