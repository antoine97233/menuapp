<?php

namespace App\Controllers;

use App\src\jwt\ManageJwt;
use Exception;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil.
     *
     * @return void
     */
    public function indexAction()
    {

        try {
            $this->checkAdminAuthentication();


            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            http_response_code(200);
            $response =  [
                "error" => null
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }

        Controller::render('home/index', $response);
    }
}
