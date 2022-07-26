<?php

use App\Model\Entity\User;
use App\Model\Manager\UsersManager;
use Texier\Framework\Controller;

class ControllerLogin extends Controller
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

    public function login()
    {
        if($this->request->existsParam('email') && $this->request->existsParam('password'))
        {
            $email = $this->request->getParam('email');
            $password = $this->request->getParam('password');

            if($this->usersManager->login($email, $password)) {
                $user = $this->usersManager->get($email);
                $this->request->getSession()->setAttribute('userID', $user->getId());
                $this->request->getSession()->setAttribute('userName', $user->getUsername());
                $this->request->getSession()->setAttribute('userEmail', $user->getEmail());
                $this->request->getSession()->setAttribute('userRole', $user->getRole());

                if($user->getRole() == User::ADMIN) {
                    $this->redirect('admin');
                }

                $this->redirect();
            }
            else {
                $this->redirect('login', null, [
                    'error' => "Impossible de vous connecter. Les identifiants fournis semblent incorrects."
                ]);
            }
        }
        else {
            $this->redirect('login', null, [
                'error' => "Vous devez renseigner votre email et votre mot de passe."
            ]);
        }
    }

    public function logout()
    {
        $this->request->getSession()->destroy();
        $this->redirect('login');
    }
}