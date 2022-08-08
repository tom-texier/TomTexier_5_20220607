<?php

namespace App\Model\Manager;

use App\Model\Entity\Post;
use Texier\Framework\Model;

class PostsManager extends Model
{
    /**
     * Obtenir la liste des posts
     * @return Post[]
     */
    public function getList(): array
    {
        $sql = "SELECT id, title, content, image, createdAt, updatedAt
                FROM posts
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql);
        $posts = [];

        while($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = new Post($row);
        }

        return $posts;
    }

    public function get(int $id): Post
    {

    }

    /**
     * @param Post $post
     * @return false|\PDOStatement
     */
    public function add(Post $post)
    {
        $sql = "INSERT INTO posts (authorId, title, content, image, createdAt) VALUES (:authorId, :title, :content, :image, :createdAt)";
        $result = $this->executeRequest($sql, [
            ':authorId'     => $post->getUserID(),
            ':title'        => $post->getTitle(),
            ':content'      => $post->getContent(),
            ':image'        => $post->getImage(),
            ':createdAt'    => $post->getCreatedAt()->format('Y-m-d H:i:s')
        ]);

        return $result;
    }

    public function delete(int $id)
    {

    }

    public function update(Post $post)
    {
        
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as numberPosts FROM posts";
        $result = $this->executeRequest($sql)->fetch();

        return $result['numberPosts'];
    }
}

