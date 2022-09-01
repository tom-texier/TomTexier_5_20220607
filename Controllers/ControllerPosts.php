<?php

use App\Model\Manager\PostsManager;
use Texier\Framework\Controller;

class ControllerPosts extends Controller
{
    private PostsManager $postsManager;

    public function __construct()
    {
        $this->postsManager = new PostsManager();
    }

    public function index()
    {
        $posts = $this->postsManager->getList();
        $this->generateView([
            'posts' => $posts
        ]);
    }
}
