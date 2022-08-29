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
        $numberItems = [
            'posts' => $this->postsManager->count(),
            'users' => $this->usersManager->count(),
            'comments' => $this->commentsManager->count(),
        ];

        $this->generateView([
            'numberItems' => $numberItems
        ]);
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

            $now = new DateTime();

            $post = new Post([
                'title'     => $title,
                'content'   => $content,
                'image'     => $imageName,
                'author'    => $authorId,
                'createdAt' => $now,
                'updatedAt' => $now
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
            $this->redirect('admin', 'postsManagement');

        $postId = intval($this->request->getParam('id'));
        $post = $this->postsManager->get($postId);

        if(!$post)
            $this->redirect('admin', 'postsManagement', ['error' => 'Cet article n\'existe pas.']);

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

            if(!$result instanceof PDOStatement && isset($result['error'])) {
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

    public function editUser()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'usersManagement');

        $userId = intval($this->request->getParam('id'));
        $user = $this->usersManager->get($userId);

        if(!$user)
            $this->redirect('admin', 'usersManagement', ['error' => 'Cet utilisateur n\'existe pas.']);

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

            if($this->request->getSession()->getAttribute('userID') == $userId) {
                $role = $this->request->getSession()->getAttribute('userRole');
            }
            else {
                $role = $this->request->getParam('role');
            }

            if(empty($username) || empty($email) || empty($role)) {
                $this->redirect('admin', 'editUser', ['error' => 'Vous devez renseigner tous les champs obligatoires (*) du formulaire.'], $userId);
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('admin', 'editUser', [
                    'error' => "L'adresse mail n'est pas valide."
                ], $userId);
            }

            if(!empty($password) && !empty($confirm_password)) {
                if(strlen($password) < 6) {
                    $this->redirect('admin', 'editUser', [
                        'error' => "Le mot de passe saisi est trop court."
                    ], $userId);
                }

                if($password !== $confirm_password) {
                    $this->redirect('admin', 'editUser', [
                        'error' => "Les mots de passe ne correspondent pas."
                    ], $userId);
                }

                $password = password_hash($password, PASSWORD_BCRYPT);

                $user->setPassword($password);
            }

            if($role != User::MEMBER && $role != User::ADMIN && $userId != $_SESSION['userID']) {
                $this->redirect('admin', 'editUser', [
                    'error' => "Vous devez renseigner le rôle de l'utilisateur."
                ], $userId);
            }

            $user->setUsername($username);
            $user->setEmail($email);

            if(!empty($role)) {
                $user->setRole($role);
            }

            $result = $this->usersManager->update($user);

            if(!$result)
                $this->redirect('admin', 'editUser', ['error' => 'Une erreur est survenue. Impossible de modifier cet utilisateur.'], $userId);

            if(!$result instanceof PDOStatement && isset($result['error'])) {
                $this->redirect('admin', 'editUser', $result, $userId);
            }
            else {
                if($this->request->getSession()->getAttribute('userID') == $userId) {
                    $this->request->getSession()->setAttribute('userName', $user->getUsername());
                    $this->request->getSession()->setAttribute('userEmail', $user->getEmail());
                }
                $this->redirect('admin', 'editUser', ['success' => 'Utilisateur mis à jour.'], $userId);
            }

        }
        else {
            $this->generateView([
                'user' => $user
            ]);
        }
    }

    //======================== Gestion des commentaires ======================

    public function commentsManagement()
    {
        $numberComments = $this->commentsManager->count();
        $comments = $this->commentsManager->getList();

        $this->generateView([
            'comments' => $comments,
            'numberComments' => $numberComments
        ]);
    }

    public function validateComment()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'commentsManagement');

        $commentId = intval($this->request->getParam('id'));

        $result = $this->commentsManager->validate($commentId);

        if(!$result)
            $this->redirect('admin', 'commentsManagement', ['error' => 'Une erreur est survenue. Impossible de valider ce commentaire.']);

        $this->redirect('admin', 'commentsManagement', ['success' => 'Commentaire validé.']);
    }

    public function disableComment()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'commentsManagement');

        $commentId = intval($this->request->getParam('id'));

        $result = $this->commentsManager->disable($commentId);

        if(!$result)
            $this->redirect('admin', 'commentsManagement', ['error' => 'Une erreur est survenue. Impossible de désactiver ce commentaire.']);

        $this->redirect('admin', 'commentsManagement', ['success' => 'Commentaire désactivé.']);
    }

    public function deleteComment()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'commentsManagement');

        $commentId = intval($this->request->getParam('id'));
        $result = $this->commentsManager->delete($commentId);

        if(!$result)
            $this->redirect('admin', 'commentsManagement', ['error' => 'Une erreur est survenue. Impossible de supprimer ce commentaire.']);

        $this->redirect('admin', 'commentsManagement', ['success' => 'Commentaire supprimé.']);
    }

    public function showComment()
    {
        if(!$this->request->existsParam('id'))
            $this->redirect('admin', 'commentsManagement');

        $commentId = intval($this->request->getParam('id'));
        $comment = $this->commentsManager->get($commentId);

        if(!$comment)
            $this->redirect('admin', 'commentsManagement', ['error' => "Ce commentaire n'existe pas."]);

        $this->generateView([
            'comment' => $comment
        ]);
    }

    //======================== Utilitaires ======================

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