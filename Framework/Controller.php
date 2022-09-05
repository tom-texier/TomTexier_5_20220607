<?php

namespace Texier\Framework;

abstract class Controller
{
    private $action;
    protected Request $request;

    /**
     * Initialise la requête
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Exécute l'action passée en paramètre du Controller
     * @throws \Exception
     * @return void
     */
    public function executeAction($action)
    {
        if(method_exists($this, $action)) {
            $this->action = $action;
            $this->{$this->action}();
        }
        else {
            $classController = get_class($this);
            throw new \Exception("Action '$action' non définie dans la classe $classController");
        }
    }

    /**
     * Génère la vue principale
     * @return mixed
     */
    public abstract function index();

    protected function generateView($dataView = [])
    {
        $classController = get_class($this);
        $controller = str_replace("Controller", "", $classController);
        $path = Configuration::get('rootPath') . 'Views';

        if($error_message = $this->request->getSession()->getAttribute('error_message')) {
            $dataView['error_message'] = $error_message;
            $this->request->getSession()->removeAttribute('error_message');
        }

        if($error_message = $this->request->getSession()->getAttribute('success_message')) {
            $dataView['success_message'] = $error_message;
            $this->request->getSession()->removeAttribute('success_message');
        }

        $view = new View($path, $this->action, $controller);
        $view->generate($dataView);
    }

    /**
     * Redirige vers une autre page
     * @param string $controller Controller à utiliser
     * @param null $action Action à exécuter
     * @param array $messages Messages à afficher
     * @param int|null $id Identifiant spécifique pour un article, utilisateurs, etc.
     * @return void
     * @throws \Exception
     */
    protected function redirect(string $controller = '', $action = null, array $messages = [], int $id = null)
    {
        if(!empty($messages['error'])) {
            $this->request->getSession()->setAttribute('error_message', $messages['error']);
        }

        if(!empty($messages['success'])) {
            $this->request->getSession()->setAttribute('success_message', $messages['success']);
        }

        $rootWeb = Configuration::get("rootWeb", "/");

        if(empty($controller) && empty($action)) {
            header("Location:" . $rootWeb);
        }
        elseif(is_null($id)) {
            header("Location:" . $rootWeb . $controller . "/" . $action);
        }
        elseif(is_null($action)) {
            header("Location:" . $rootWeb . $controller . "/" . $id);
        }
        else {
            header("Location:" . $rootWeb . $controller . "/" . $action . '/' . $id);
        }

        exit();
    }
}
