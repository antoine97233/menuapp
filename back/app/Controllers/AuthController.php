<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Entities\Admin;
use App\src\jwt\ManageJwt;
use Exception;


class AuthController extends Controller
{
    /**
     * Affiche la page d'authentification.
     *
     * @return void
     */
    public function indexAction(): void
    {
        session_start();
        $this->generateCsrfToken();
        $this->renderOffline("auth/index");
    }

    /**
     * Effectue la connexion de l'administrateur.
     *
     * @return void
     */
    public function loginAction(): void
    {
        session_start();

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode de requête invalide", 405);
            }

            $adminEmail = isset($_POST['adminEmail']) ? htmlspecialchars($_POST['adminEmail']) : NULL;
            $adminPassword = isset($_POST['adminPassword']) ? htmlspecialchars($_POST['adminPassword']) : NULL;

            if ($this->checkCsrfTokenValid()) {
                throw new Exception("Page expirée, veuillez actualiser la page.", 401);
            };


            $admin = new Admin();
            $admin->setAdminEmail($adminEmail);

            $model = new UserModel();
            $adminData = $model->findByEmail($admin);

            if (empty($adminData) || !password_verify($adminPassword, $adminData[0]['adminPassword'])) {
                throw new Exception("Identifiants de connexion invalides", 401);
            }

            $jwtToken = ManageJwt::create($adminEmail, $adminData[0]['adminSuper']);

            session_regenerate_id();
            $_SESSION['adminEmail'] = $adminEmail;
            $_SESSION['jwtToken'] = $jwtToken;

            $this->generateCsrfToken();

            http_response_code(200);
            $response = [
                'jwtToken' => $jwtToken,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);

            $response = [
                'error' => $error
            ];
        }
        echo json_encode($response);
    }

    /**
     * Déconnecte l'administrateur et redirige vers la page d'authentification.
     *
     * @return void
     */
    public function logoutAction(): void
    {
        $this->logout();
        header("Location: index.php?controller=auth");
    }
}
