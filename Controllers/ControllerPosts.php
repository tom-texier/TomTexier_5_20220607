<?php

use App\Model\Manager\PostsManager;
use Texier\Framework\Controller;

class ControllerPosts extends Controller
{
    private PostsManager $postsManager;

    /**
     * Constructeur de classe
     */
    public function __construct()
    {
        $this->postsManager = new PostsManager();
    }

    /**
     * Génère la page de présentation de tous les articles
     * @return void
     */
    public function index()
    {
        $posts = $this->postsManager->getList();
        $this->generateView([
            'posts' => $posts
        ]);
    }
}
