<?php

use App\Model\Entity\Post;
use App\Model\Entity\User;
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
                'author'    => $authorId,
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
            $this->request->existsParam('author') ||
            $this->request->existsParam('submit')
        ) {
            $title = $this->request->getParam('title');
            $content = $this->request->getParam('content');
            $image = !empty($_FILES['image']['tmp_name']) ? $_FILES['image'] : false;
            $authorId = $this->request->getParam('author');

            if (empty($title) || empty($content) || empty($authorId)) {
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
            $post->setAuthor($authorId);
            $post->setUpdatedAt(new DateTime());

            $result = $this->postsManager->update($post);

            if(!$result)
                $this->redirect('admin', 'editPost', ['error' => 'Une erreur est survenue. Impossible de modifier cet article.'], $postId);

            $this->redirect('admin', 'editPost', ['success' => 'Article mis à jour.'], $postId);
        }
        else {
            $users = $this->usersManager->getList();

            $this->generateView([
                'post'  => $post,
                'users' => $users
            ]);
        }
    }

    public function deletePost()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'postsManagement');

        $postId = intval($this->request->getParam('id'));
        $post = $this->postsManager->get($postId);

        $result = $this->postsManager->delete($postId);

        if(!$result)
            $this->redirect('admin', 'postsManagement', ['error' => 'Une erreur est survenue. Impossible de supprimer cet article.']);

        $this->deleteFile($post->getImage());
        $this->redirect('admin', 'postsManagement', ['success' => 'Article supprimé.']);
    }

    //======================== Gestion des utilisateurs ======================

    public function usersManagement()
    {
        $numberUsers = $this->usersManager->count();
        $users = $this->usersManager->getList();

        $this->generateView([
            'users' => $users,
            'numberUsers' => $numberUsers
        ]);
    }

    public function deleteUser()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'usersManagement');

        $userId = intval($this->request->getParam('id'));
        $user = $this->usersManager->get($userId);

        $result = $this->usersManager->delete($userId);

        if(!$result)
            $this->redirect('admin', 'usersManagement', ['error' => 'Une erreur est survenue. Impossible de supprimer cet utilisateur.']);

        $this->redirect('admin', 'usersManagement', ['success' => 'Utilisateur supprimé.']);
    }

    public function addUser()
    {
        if(
            $this->request->existsParam('username') ||
            $this->request->existsParam('email') ||
            $this->request->existsParam('password') ||
            $this->request->existsParam('confirm_password') ||
            $this->request->existsParam('role') ||
            $this->request->existsParam('submit')
        ) {
            $username = $this->request->getParam('username');
            $email = $this->request->getParam('email');
            $password = $this->request->getParam('password');
            $confirm_password = $this->request->getParam('confirm_password');
            $role = $this->request->getParam('role');

            if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
                $this->redirect('admin', 'addUser', ['error' => 'Vous devez renseigner tous les champs du formulaire.']);
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('admin', 'addUser', [
                    'error' => "L'adresse mail n'est pas valide."
                ]);
            }

            if(strlen($password) < 6) {
                $this->redirect('admin', 'addUser', [
                    'error' => "Le mot de passe saisi est trop court."
                ]);
            }

            if($password !== $confirm_password) {
                $this->redirect('admin', 'addUser', [
                    'error' => "Les mots de passe ne correspondent pas."
                ]);
            }

            if($role != User::MEMBER && $role != User::ADMIN) {
                $this->redirect('admin', 'addUser', [
                    'error' => "Vous devez renseigner le rôle de l'utilisateur."
                ]);
            }

            $password = password_hash($password, PASSWORD_BCRYPT);

            $user = new User([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'createdAt' => new DateTime()
            ]);

            $result = $this->usersManager->add($user);

            if(!$result)
                $this->redirect('admin', 'addUser', ['error' => 'Une erreur est survenue. Impossible de créer cet utilisateur.']);

            if(isset($result['error'])) {
                $this->redirect('admin', 'addUser', $result);
            }
            else {
                $this->redirect('admin', 'usersManagement', [
                    'success' => 'Utilisateur créé'
                ]);
            }
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

    /**
     * @param string $filename Nom du fichier à supprimer dans le dossier ./assets/img/posts/
     * @return bool
     */
    private function deleteFile(string $filename): bool
    {
        return unlink('./assets/img/posts/' . $filename);
    }
}