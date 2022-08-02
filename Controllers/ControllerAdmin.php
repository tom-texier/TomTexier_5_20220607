<?php

use App\Model\Manager\CommentsManager;
use App\Model\Manager\PostsManager;
use App\Model\Manager\UsersManager;
use Texier\Framework\ControllerSecured;

class ControllerAdmin extends ControllerSecured
{
    private PostsManager $postsManager;
    private CommentsManager $commentsManager;
    private UsersManager $usersManager;

    public function __construct()
    {
        $this->postsManager = new PostsManager();
        $this->commentsManager = new CommentsManager();
        $this->usersManager = new UsersManager();
    }

    public function index()
    {
        $this->generateView();
    }

    //======================== Gestion des posts ======================

    public function postsManagement()
    {
        $numberPosts = $this->postsManager->count();
        $posts = $this->postsManager->getList();

        $this->generateView([
            'posts'         => $posts,
            'numberPosts'   => $numberPosts
        ]);
    }

    public function addPost()
    {
        if(
            $this->request->existsParam('title') &&
            $this->request->existsParam('content') &&
            $this->request->existsParam('image')
        ) {

        }
        else {
            $this->generateView();
        }
    }
}