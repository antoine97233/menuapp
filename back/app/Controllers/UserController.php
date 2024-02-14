<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Entities\Admin;
use App\Helpers\MessageHelpers;
use App\src\jwt\ManageJwt;
use App\src\validation\FieldValidation;
use Exception;


class UserController extends Controller
{


    /**
     * Affiche la liste des utilisateurs (admins).
     *
     * @return void
     */
    public function indexAction(): void
    {

        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            if (!$this->checkIfSuperAdmin()) {
                throw new Exception("Vous n'avez pas accès à cette fonctionnalité", 401);
            };

            $users = new UserModel();
            $userList = $users->findAll();

            http_response_code(200);
            $response = [
                "userList" => $userList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        Controller::render("user/userList", $response);
    }




    /**
     * Affiche le formulaire pour ajouter ou supprimer un utilisateur (admin).
     *
     * @return void
     */
    public function formAction(): void
    {

        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }


            if (!$this->checkIfSuperAdmin()) {
                throw new Exception("Vous n'avez pas accès à cette fonctionnalité", 401);
            };

            if (isset($_GET['request']) && $_GET['request'] == 'add') {
                $parameters = array(
                    "adminName" => "",
                    "adminEmail" => "",
                    "adminPassword" => "",
                    "action" => "add",
                    "buttonStyle" => "success",
                    "readonly" => "",
                    "disabled" => "",
                    "libAction" => "Ajouter",
                    "actionSubmit" => "addUser",

                );
            } elseif (isset($_GET['request']) && $_GET['request'] == 'delete' && isset($_GET['adminId']) && isset($_GET['adminName']) && isset($_GET['adminEmail'])) {
                $parameters = array(
                    "adminId" => $_GET['adminId'],
                    "adminName" => $_GET['adminName'],
                    "adminEmail" => $_GET['adminEmail'],
                    "action" => "delete",
                    "buttonStyle" => "danger",
                    "readonly" => "readonly",
                    "disabled" => "disabled",
                    "libAction" => "Supprimer",
                    "actionSubmit" => "deleteUser",
                );
            } else {
                $parameters = null;
            }

            http_response_code(200);
            $response = [
                "parameters" => $parameters,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        Controller::render("user/form", $response);
    }



    /**
     * Ajoute un utilisateur (admin).
     *
     * @return void
     */
    public function addAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            if (!$this->checkIfSuperAdmin()) {
                throw new Exception("Vous n'avez pas accès à cette fonctionnalité", 401);
            };


            $adminEmail = isset($_POST["adminEmail"]) ? htmlspecialchars($_POST["adminEmail"]) : NULL;
            $adminName = isset($_POST["adminName"]) ? htmlspecialchars($_POST["adminName"]) : NULL;
            $adminPassword = isset($_POST["adminPassword"]) ? htmlspecialchars($_POST["adminPassword"]) : NULL;


            $formData = [$adminEmail, $adminName, $adminPassword];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }

            if ($this->checkIfEmailExists($adminEmail)) {
                throw new Exception("L'adresse email est déjà utilisée", 409);
            }

            if (!FieldValidation::validatePassword($adminPassword)) {
                throw new Exception("Veuillez respectez les conditions suivantes pour le mot de passe :
                au moins 8 caractères, une majuscule, un chiffre et un symbole:", 442);
            }

            $model = new UserModel();
            $admin = new Admin();
            $admin->setAdminEmail($adminEmail);
            $admin->setAdminName($adminName);
            $admin->setAdminPassword(password_hash($adminPassword, PASSWORD_DEFAULT));
            $admin->setAdminSuper(0);

            $model->add($admin);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("ajouté, veuillez patentier...");
            $response = [
                'successMessage' => $successMessage
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
     * Supprime un utilisateur (admin).
     *
     * @return void
     */
    public function deleteAction(): void
    {

        try {

            $this->checkAdminAuthentication();

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            if (!$this->checkIfSuperAdmin()) {
                throw new Exception("Vous n'avez pas accès à cette fonctionnalité", 401);
            };

            $adminId = isset($_POST['adminId']) ? (int)$_POST['adminId'] : NULL;
            $adminEmail = isset($_POST["adminEmail"]) ? htmlspecialchars($_POST["adminEmail"]) : NULL;


            if ($adminEmail === $_SESSION['adminEmail'] && $this->checkIfSuperAdmin()) {
                throw new Exception("Vous ne pouvez pas supprimer votre propre compte.", 650);
            }

            $model = new UserModel();
            $admin = new Admin();
            $admin->setAdminId($adminId);
            $model->delete($admin);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé, veuillez patentier...");
            $response = [
                'successMessage' => $successMessage
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
     * Vérifie si l'email de l'administrateur existe déjà.
     *
     * @param string $adminEmail L'email de l'administrateur à vérifier.
     * @return bool True si l'email existe déjà, sinon false.
     * @throws Exception En cas d'erreur.
     */
    protected function checkIfEmailExists(string $adminEmail): bool
    {
        try {
            $model = new UserModel();
            $existingEmails = $model->findAllEmails();

            $existingEmailsLower = array_map('strtolower', $existingEmails);
            $adminEmailLower = strtolower($adminEmail);

            return in_array($adminEmailLower, $existingEmailsLower);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la vérification de l'existence de l'email.", 500, $e);
        }
    }
}
