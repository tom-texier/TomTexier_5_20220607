<?php

namespace Texier\Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Router
{
    const DEFAULT_CONTROLLER = 'Home';
    const DEFAULT_ACTION = 'index';

    public function routingRequest()
    {
        $request = new Request($_REQUEST);

        $controller = $this->createController($request);
        $action = $this->createAction($request);

        $controller->executeAction($action);
    }

    private function createController(Request $request)
    {
        $controller = self::DEFAULT_CONTROLLER;

        $rootWeb = Configuration::get('rootPath');

        if($request->existsParam('controller')) {
            $controller = $request->getParam('controller');
            $controller = ucfirst($controller);
        }

        $classController = 'Controller' . $controller;
        $fileController = $rootWeb . 'Controllers/' . $classController . '.php';

        if(file_exists($fileController)) {
            require_once $fileController;
            /** @var Controller $controller */
            $controller = new $classController();
            $controller->setRequest($request);

            return $controller;
        }
        else {
            throw new \Exception("Fichier '$fileController' introuvable");
        }
    }

    private function createAction(Request $request)
    {
        $action = self::DEFAULT_ACTION;

        if($request->existsParam('action')) {
            $action = $request->getParam('action');
        }

        return $action;
    }
}