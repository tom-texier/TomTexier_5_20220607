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
        $sql = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.image,
                    p.createdAt,
                    p.updatedAt,
                    u.username,
                    u.email
                FROM posts p, users u
                WHERE p.authorId = u.id
                ORDER BY p.createdAt DESC";

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

    public function add(Post $post)
    {

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

