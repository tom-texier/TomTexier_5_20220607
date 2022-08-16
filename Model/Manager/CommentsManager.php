<?php

namespace App\Model\Manager;

use App\Model\Entity\Comment;
use Texier\Framework\Model;

class CommentsManager extends Model
{
    public function getListByPostId(int $postId): array
    {
        $sql = "SELECT *
                FROM comments
                WHERE postId = ?
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql, [$postId]);
        $comments = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($row);
        }

        return $comments;
    }

    /**
     * @param int $commentId
     * @return Comment|false
     */
    public function get(int $commentId)
    {
        $sql = "SELECT * FROM comments WHERE id = ?";

        $post = $this->executeRequest($sql, [$commentId]);

        if ($post->rowCount() == 1) {
            $datas = $post->fetch(\PDO::FETCH_ASSOC);

            return new Comment($datas);
        }

        return false;
    }

    public function add(Comment $comment)
    {
        $sql = "INSERT INTO comments (author, postId, content, createdAt, status) VALUES (:authorId, :postId, :content, :createdAt, :status)";

        return $this->executeRequest($sql, [
            ':authorId' => $comment->getAuthor()->getId(),
            ':postId' => $comment->getPostId(),
            ':content' => $comment->getContent(),
            ':createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
            ':status' => $comment->getStatus()
        ]);
    }

    public function delete(int $commentId)
    {
        $sql = "DELETE FROM comments WHERE id = ?";

        return $this->executeRequest($sql, [$commentId]);
    }

    public function validate(int $commentId)
    {
        $sql = "UPDATE comments
                SET status = :status
                WHERE id = :commentId";

        return $this->executeRequest($sql, [
            ':status' => Comment::VALIDATED,
            ':commentId' => $commentId
        ]);
    }

    public function disable(int $commentId)
    {
        $sql = "UPDATE comments
                SET status = :status
                WHERE id = :commentId";

        return $this->executeRequest($sql, [
            ':status' => Comment::PENDING,
            ':commentId' => $commentId
        ]);
    }
}