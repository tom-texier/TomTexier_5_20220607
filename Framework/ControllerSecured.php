<?php

namespace Texier\Framework;

use App\Model\Entity\User;

abstract class ControllerSecured extends Controller
{
    /**
     * Vérifie si l'utilisateur connecté est administrateur
     * @param $action
     * @return void
     * @throws \Exception
     */
    public function executeAction($action)
    {
        if($this->request->getSession()->existsAttribute('userRole')) {
            if($this->request->getSession()->getAttribute('userRole') == User::ADMIN) {
                parent::executeAction($action);
            }
            else {
                $this->redirect('home');
            }
        }
        else {
            $this->redirect('connexion');
        }
    }
}
