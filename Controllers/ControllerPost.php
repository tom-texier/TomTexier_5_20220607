<?php

use App\Model\Entity\Comment;
use App\Model\Manager\CommentsManager;
use App\Model\Manager\PostsManager;
use Texier\Framework\Controller;

class ControllerPost extends Controller
{
    private PostsManager $postsManager;
    private CommentsManager $commentsManager;

    public function __construct()
    {
        $this->postsManager = new PostsManager();
        $this->commentsManager = new CommentsManager();
    }

    public function index()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('articles');

        $postId = intval($this->request->getParam('id'));
        $post = $this->postsManager->get($postId);
        $comments = $this->commentsManager->getListByPostId($postId);

        $this->generateView([
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function addComment()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('articles');

        $postId = intval($this->request->getParam('id'));
        $post = $this->postsManager->get($postId);

        if(!$post)
            $this->redirect('articles');

        if(!$authorId = $this->request->getSession()->getAttribute('userID'))
            $this->redirect('article', null, ['error' => "Vous devez être connecté pour ajouter un commentaire."], $post->getId());

        if(!$this->request->existsParam('content') || $this->request->existsParam('country'))
            $this->redirect('article', null, ['error' => "Un ou plusieurs champs contiennent une erreur. Veuillez vérifier et essayer à nouveau."], $post->getId());

        $content = $this->request->getParam('content');

        $comment = new Comment([
            'author' => $authorId,
            'post' => $postId,
            'content' => $content,
            'createdAt' => new DateTime(),
            'status' => Comment::PENDING
        ]);

        $result = $this->commentsManager->add($comment);

        if(!$result)
            $this->redirect('article', null, ['error' => "Une erreur est survenue. Impossible d'ajouter ce commentaire."], $postId);

        $this->redirect('article', null, ['success' => "Votre commentaire a bien été enregistré. Un administrateur l'examinera avant qu'il ne soit publié."], $postId);
    }
}