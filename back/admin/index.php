<?php


require_once '../vendor/autoload.php';
require_once '../app/config/Router.php';

use App\config\Router;


$router = new Router;

$router->routes();
