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
    $loader = new FilesystemLoader(Configuration::get('rootWeb') . 'Views');
    $twig = new Environment($loader);
    $twig->display('error.html.twig', [
        'msgError'  => $exception->getMessage(),
        'file'      => $exception->getFile(),
        'line'      => $exception->getLine(),
        'traces'     => $exception->getTrace()
    ]);
}