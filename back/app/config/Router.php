<?php

namespace App\config;

use App\Controllers\Controller;
use App\Helpers\MessageHelpers;

class Router
{
    public function routes()
    {
        $controllerName = (isset($_GET['controller'])) ? $_GET['controller'] : "home";
        $controllerName = '\\App\\Controllers\\' . ucfirst($controllerName) . 'Controller';

        $actionName = (isset($_GET['action'])) ? $_GET['action'] : "index";
        $actionName = $actionName . "Action";

        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            if (method_exists($controller, $actionName)) {
                isset($_GET) ? $controller->$actionName($_GET) : $controller->$actionName();
            } else {
                http_response_code(404);
                $message = "La page recherchée n'existe pas";
                $error = MessageHelpers::errorPage($message);
                Controller::render("auth/index", [
                    "error" => $error
                ]);
            }
        } else {
            http_response_code(404);
            $message = "La page recherchée n'existe pas";
            $error = MessageHelpers::errorPage($message);
            Controller::render("auth/index", [
                "error" => $error
            ]);
        }
    }
}
