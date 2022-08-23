<?php

use App\Model\Entity\User;
use App\Model\Manager\UsersManager;
use Texier\Framework\Controller;
use DateTime;

class ControllerRegistration extends Controller
{
    private UsersManager $usersManager;

    public function __construct()
    {
        $this->usersManager = new UsersManager();
    }

    public function index()
    {
        if($this->request->getSession()->existsAttribute('userID')) {
            $this->redirect();
        }

        $this->generateView();
    }

    public function register()
    {
        if($this->request->existsParam("username")
            && $this->request->existsParam("email")
            && $this->request->existsParam("password")
            && $this->request->existsParam('confirm_password'))
        {
            $username = $this->request->getParam("username");
            $email = $this->request->getParam("email");
            $password = $this->request->getParam("password");
            $confirm_password = $this->request->getParam("confirm_password");

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('inscription', null, [
                    'error' => "L'adresse mail n'est pas valide."
                ]);
            }

            if(strlen($password) < 6) {
                $this->redirect('inscription', null, [
                    'error' => "Le mot de passe saisi est trop court."
                ]);
            }

            if($password !== $confirm_password) {
                $this->redirect('inscription', null, [
                    'error' => "Les mots de passe ne correspondent pas."
                ]);
            }

            $password = password_hash($password, PASSWORD_BCRYPT);
            
            $user = new User([
                'username'  => $username,
                'email'     => $email,
                'password'  => $password,
                'role'      => User::MEMBER,
                'createdAt' => new DateTime()
            ]);

            $result = $this->usersManager->add($user);

            if(isset($result['error'])) {
                $this->redirect('inscription', null, $result);
            }
            else {
                $this->redirect('connexion', null, [
                    'success' => 'Votre inscription a bien été prise en compte. Vous pouvez maintenant vous connecter.'
                ]);
            }
        }
    }
}