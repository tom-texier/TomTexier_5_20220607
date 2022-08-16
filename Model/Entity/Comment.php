<?php

namespace App\Model\Entity;

use App\Model\Manager\UsersManager;
use Texier\Framework\Entity;

class Comment extends Entity
{

    const PENDING = 1;
    const VALIDATED = 2;

    private int $id;
    private User $author;
    private int $postId;
    private string $content;
    private \DateTime $createdAt;
    private int $status;

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getPostId()
    {
        return $this->postId;
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

    /**
     * @param int|string $authorId
     * @return void
     */
    public function setAuthor($authorId)
    {
        if(!is_int($authorId)) {
            $authorId = intval($authorId);
        }

        $usersManager = new UsersManager();
        $this->author = $usersManager->get($authorId);
    }

    public function setPostId(int $postId)
    {
        $this->postId = $postId;
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