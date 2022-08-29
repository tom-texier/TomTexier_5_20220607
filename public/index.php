<?php

use Texier\Framework\Configuration;
use Texier\Framework\Router;

use App\Controller\ControllerHome;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

function debug(...$vars) {
    foreach($vars as $var) {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}

try {
    date_default_timezone_set(Configuration::get('timezone'));
    $router = new Router();
    $router->routingRequest();
}
catch(\Exception $exception) {
    $rootWeb = Configuration::get("rootWeb", "/");
    header("Location:" . $rootWeb . "error/404");
}
