<?php

namespace App\Model\Entity;

use Texier\Framework\Entity;

class Post extends Entity
{
    private int $id;
    private int $authorId;
    private string $title;
    private string $content;
    private string $image;
    private \DateTime $createdAt;
    private $updatedAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
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
     * @param $userID
     * @return void
     */
    public function setAuthorId($authorId)
    {
        if(!is_int($authorId)) {
            $authorId = intval($authorId);
        }
        $this->authorId = $authorId;
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