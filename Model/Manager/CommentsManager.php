<?php

namespace App\Model\Manager;

use App\Model\Entity\Comment;
use Texier\Framework\Model;

class CommentsManager extends Model
{
    /**
     * Retourne la liste des commentaires pour un article
     * @param int $postId
     * @return Comment[]
     * @throws \Exception
     */
    public function getListByPostId(int $postId): array
    {
        $sql = "SELECT *
                FROM comments
                WHERE post = :postId
                AND status = :status
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql, [
            ':postId' => $postId,
            ':status' => Comment::VALIDATED
        ]);
        $comments = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($row);
        }

        return $comments;
    }

    /**
     * Retourne la liste de tous les commentaires
     * @return Comment[]
     * @throws \Exception
     */
    public function getList(): array
    {
        $sql = "SELECT *
                FROM comments
                ORDER BY createdAt DESC";

        $result = $this->executeRequest($sql);
        $comments = [];

        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($row);
        }

        return $comments;
    }

    /**
     * Retourne un commentaire
     * @param int $commentId
     * @return Comment|false
     * @throws \Exception
     */
    public function get(int $commentId)
    {
        $sql = "SELECT * FROM comments WHERE id = ?";

        $comment = $this->executeRequest($sql, [$commentId]);

        if ($comment->rowCount() == 1) {
            $datas = $comment->fetch(\PDO::FETCH_ASSOC);

            return new Comment($datas);
        }

        return false;
    }

    /**
     * Ajoute un nouveau commentaire
     * @param Comment $comment
     * @return false|\PDOStatement
     * @throws \Exception
     */
    public function add(Comment $comment)
    {
        $sql = "INSERT INTO comments (author, post, content, createdAt, status) VALUES (:authorId, :postId, :content, :createdAt, :status)";

        return $this->executeRequest($sql, [
            ':authorId' => $comment->getAuthor()->getId(),
            ':postId' => $comment->getPost()->getId(),
            ':content' => $comment->getContent(),
            ':createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
            ':status' => $comment->getStatus()
        ]);
    }

    /**
     * Supprime un commentaire
     * @param int $commentId
     * @return false|\PDOStatement
     * @throws \Exception
     */
    public function delete(int $commentId)
    {
        $sql = "DELETE FROM comments WHERE id = ?";

        return $this->executeRequest($sql, [$commentId]);
    }

    /**
     * Passe le status du commentaire à Validé
     * @param int $commentId
     * @return false|\PDOStatement
     * @throws \Exception
     */
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

    /**
     * Passe le status du commentaire à Désactivé
     * @param int $commentId
     * @return false|\PDOStatement
     * @throws \Exception
     */
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

    /**
     * Retourne le nombre de commentaires
     * @return mixed
     * @throws \Exception
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as numberComments FROM comments";
        $result = $this->executeRequest($sql)->fetch();

        return $result['numberComments'];
    }
}
