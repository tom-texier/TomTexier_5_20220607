<?php

namespace App\Model\Entity;

use App\Model\Manager\PostsManager;
use App\Model\Manager\UsersManager;
use Texier\Framework\Entity;

class Comment extends Entity
{

    const PENDING = 1;
    const VALIDATED = 2;

    private int $id;
    private User $author;
    private Post $post;
    private string $content;
    private \DateTime $createdAt;
    private int $status;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getPost(): Post
    {
        return $this->post;
    } 

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setId($id)
    {
        if(!is_int($id)) {
            $id = intval($id);
        }
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

    public function setPost($postId)
    {
        if(!is_int($postId)) {
            $postId = intval($postId);
        }

        $postManager = new PostsManager();
        $this->post = $postManager->get($postId);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setCreatedAt($createdAt)
    {
        if(!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }
        $this->createdAt = $createdAt;
    }

    public function setStatus($status)
    {
        if(!is_int($status)) {
            $status = intval($status);
        }
        $this->status = $status;
    }
}