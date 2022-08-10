<?php

namespace App\Model\Entity;

use App\Model\Manager\UsersManager;
use Texier\Framework\Entity;

class Post extends Entity
{
    private int $id;
    private User $author;
    private string $title;
    private string $content;
    private string $image;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        if(!is_int($id)) {
            $id = intval($id);
        }
        $this->id = $id;
    }

    /**
     * @param $authorId
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

    /**
     * @param $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @param $createdAt
     * @return void
     * @throws \Exception
     */
    public function setCreatedAt($createdAt)
    {
        if(!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }
        $this->createdAt = $createdAt;
    }

    /**
     * @param $updatedAt
     * @return void
     * @throws \Exception
     */
    public function setUpdatedAt($updatedAt)
    {
        if(!$updatedAt instanceof \DateTime && $updatedAt != '') {
            $updatedAt = new \DateTime($updatedAt);
        }
        $this->updatedAt = $updatedAt;
    }
}