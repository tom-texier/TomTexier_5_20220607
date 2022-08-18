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
        $sql = "SELECT *
                FROM posts
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql);
        $posts = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $posts[] = new Post($row);
        }

        return $posts;
    }

    /**
     * @param int $postId
     * @return Post|false
     */
    public function get(int $postId)
    {
        $sql = "SELECT * FROM posts WHERE id = ?";

        $post = $this->executeRequest($sql, [$postId]);

        if ($post->rowCount() == 1) {
            $datas = $post->fetch(\PDO::FETCH_ASSOC);

            return new Post($datas);
        }

        return false;
    }

    /**
     * @param Post $post
     * @return false|\PDOStatement
     */
    public function add(Post $post)
    {
        $sql = "INSERT INTO posts (author, title, content, image, createdAt) VALUES (:authorId, :title, :content, :image, :createdAt)";

        return $this->executeRequest($sql, [
            ':authorId' => $post->getAuthor()->getId(),
            ':title' => $post->getTitle(),
            ':content' => $post->getContent(),
            ':image' => $post->getImage(),
            ':createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    public function delete(int $postId)
    {
        $sql = "DELETE FROM posts WHERE id = ?";

        return $this->executeRequest($sql, [$postId]);
    }

    public function update(Post $post)
    {
        $sql = "UPDATE posts
                SET author = :authorId, title = :title, image = :image, content = :content, updatedAt = :updatedAt
                WHERE id = :id";

        return $this->executeRequest($sql, [
            ':authorId' => $post->getAuthor()->getId(),
            ':title' => $post->getTitle(),
            ':image' => $post->getImage(),
            ':content' => $post->getContent(),
            ':updatedAt' => $post->getUpdatedAt()->format('Y-m-d H:i:s'),
            ':id' => $post->getId()
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as numberPosts FROM posts";
        $result = $this->executeRequest($sql)->fetch();

        return $result['numberPosts'];
    }
}

