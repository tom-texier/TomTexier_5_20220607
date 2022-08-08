<?php

use App\Model\Entity\Post;
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
            'posts' => $posts,
            'numberPosts' => $numberPosts
        ]);
    }

    public function addPost()
    {
        if (
            $this->request->existsParam('title') &&
            $this->request->existsParam('content') &&
            $this->request->existsParam('submit')
        ) {
            $title = $this->request->getParam('title');
            $content = $this->request->getParam('content');
            $image = !empty($_FILES['image']['tmp_name']) ? $_FILES['image'] : false;
            $authorId = $this->request->getSession()->getAttribute('userID');

            if (empty($title) || empty($content)) {
                $this->redirect('admin', 'addPost', ['error' => 'Vous devez renseigner le titre et le contenu de votre article.']);
            }

            if (!$image) {
                $this->redirect('admin', 'addPost', ['error' => 'Vous devez renseigner l\'image de votre article.']);
            }
            else {
                $imageName = $this->uploadFile($image);
            }

            if(isset($imageName['error'])) {
                $this->redirect('admin', 'addPost', $imageName);
            }

            $post = new Post([
                'title'     => $title,
                'content'   => $content,
                'image'     => $imageName,
                'userID'    => $authorId,
                'createdAt' => new DateTime(),
                'updatedAt' => new DateTime()
            ]);

            $result = $this->postsManager->add($post);

            if(!$result)
                $this->redirect('admin', 'addPost', ['error' => 'Une erreur est survenue. Impossible de créer cet article.']);

            $this->redirect('admin', 'postsManagement', ['success' => 'Votre article a bien été créé.']);

        }
        else {
            $this->generateView();
        }
    }

    /**
     * @param $file
     * @return string|string[]
     */
    private function uploadFile($file)
    {
        if (empty($file)) return '';
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $max_file_size = 2000000;

        $arrayExtension = explode('.', $file['name']);
        $extension = strtolower(end($arrayExtension));

        if(!in_array($extension, $extensions)) {
            return ['error' => "Ce type de fichier n'est pas pris en charge."];
        }
        elseif($file['size'] > $max_file_size) {
            return ['error' => "Ce fichier est trop volumineux. Essayez de le compresser. (Max. 2Mo)"];
        }
        elseif($file['error'] != 0) {
            return ['error' => "Une erreur est survenue."];
        }

        $uniq = uniqid('post-', true);
        $file['name'] = $uniq . "." . $extension;

        $uploadResult = move_uploaded_file($file['tmp_name'], './assets/img/posts/' . $file['name']);

        if(!$uploadResult)
            return ['error' => "Impossible d'ajouter cette image. Veuillez réessayer."];

        return $file['name'];
    }
}