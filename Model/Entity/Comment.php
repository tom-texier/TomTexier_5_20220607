<?php

namespace App\Model\Entity;

use Texier\Framework\Entity;

class Comment extends Entity
{

    const PENDING = 1;
    const VALIDATED = 2;

    private int $id;
    private int $authorId;
    private int $postID;
    private string $content;
    private \DateTime $createdAt;
    private int $status;

    public function getId()
    {
        return $this->id;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getPostID()
    {
        return $this->postID;
    } 

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
    }

    public function setPostID(int $postID)
    {
        $this->postID = $postID;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}