<?php

use Texier\Framework\Configuration;
use Texier\Framework\Router;

require_once '../vendor/autoload.php';

try {
    date_default_timezone_set(Configuration::get('timezone'));
    $router = new Router();
    $router->routingRequest();
}
catch(\Exception $exception) {
    $rootWeb = Configuration::get("rootWeb", "/");
    header("Location:" . $rootWeb . "error/404");
}
