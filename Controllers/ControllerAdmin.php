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
            $this->request->existsParam('title') ||
            $this->request->existsParam('content') ||
            $this->request->existsParam('submit')
        ) {
            $title = $this->request->getParam('title');
            $content = $this->request->getParam('content');
            $image = !empty($_FILES['image']['tmp_name']) ? $_FILES['image'] : false;
            $authorId = $this->request->getSession()->getAttribute('userID');

            if (empty($title) || empty($content)) {
                $this->redirect('admin', 'addPost', ['error' => 'Vous devez renseigner tous les champs du formulaire.']);
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
     * @throws Exception
     */
    public function editPost()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'postsManagement', ['error' => 'Sélectionnez un article à modifier.']);

        $postId = intval($this->request->getParam('id'));
        $post = $this->postsManager->get($postId);

        if(!$post) {
            $this->redirect('admin', 'addPost', ['error' => 'Cet article n\'existe pas, veuillez le créer grâce au formulaire ci-dessous.']);
        }

        if (
            $this->request->existsParam('title') ||
            $this->request->existsParam('content') ||
            $this->request->existsParam('submit')
        ) {
            $title = $this->request->getParam('title');
            $content = $this->request->getParam('content');
            $image = !empty($_FILES['image']['tmp_name']) ? $_FILES['image'] : false;

            if (empty($title) || empty($content)) {
                $this->redirect('admin', 'editPost', ['error' => 'Vous devez renseigner tous les champs du formulaire.'], $postId);
            }

            if ($image) {
                $this->deleteFile($post->getImage());
                $imageName = $this->uploadFile($image);

                if(isset($imageName['error'])) {
                    $this->redirect('admin', 'addPost', $imageName);
                }

                $post->setImage($imageName);
            }

            $post->setTitle($title);
            $post->setContent($content);
            $post->setUpdatedAt(new DateTime());

            $result = $this->postsManager->update($post);

            if(!$result)
                $this->redirect('admin', 'editPost', ['error' => 'Une erreur est survenue. Impossible de modifier cet article.'], $postId);

            $this->redirect('admin', 'editPost', ['success' => 'Article mis à jour.'], $postId);
        }
        else {
            $this->generateView([
                'post'  => $post
            ]);
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

    /**
     * @param string $filename Nom du fichier à supprimer dans le dossier ./assets/img/posts/
     * @return bool
     */
    private function deleteFile(string $filename): bool
    {
        return unlink('./assets/img/posts/' . $filename);
    }
}