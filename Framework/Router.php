<?php

namespace Texier\Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Router
{
    public function routingRequest()
    {
        $request = new Request($_REQUEST);

        $controller = $this->createController($request);
        $action = $this->createAction($request);

        $controller->executeAction($action);
    }

    private function createController(Request $request)
    {
        $controller = 'Home'; // Default Controller

        $rootWeb = Configuration::get('rootWeb');

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
        $action = "index"; // Default Action

        if($request->existsParam('action')) {
            $action = $request->getParam('action');
        }

        return $action;
    }
}