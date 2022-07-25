<?php

namespace Texier\Framework;

abstract class Controller
{
    private $action;
    protected Request $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

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

    public abstract function index();

    protected function generateView($dataView = [])
    {
        $classController = get_class($this);
        $controller = str_replace("Controller", "", $classController);
        $path = Configuration::get('rootWeb') . 'Views/Front';

        if($error_message = $this->request->getSession()->getAttribute('error_message')) {
            $dataView['error_message'] = $error_message;
            $this->request->getSession()->removeAttribute('error_message');
        }

        $view = new View($path, $this->action, $controller);
        $view->generate($dataView);
    }

    protected function redirect($controller, $action = null, $messages = [])
    {
        if(!empty($messages['error'])) {
            $this->request->getSession()->setAttribute('error_message', $messages['error']);
        }

        if(!empty($messages['success'])) {
            $this->request->getSession()->setAttribute('success_message', $messages['success']);
        }

        $rootWeb = Configuration::get("rootWeb", "/");
        header("Location:" . $rootWeb . $controller . "/" . $action);
        exit();
    }
}