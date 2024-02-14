<?php

namespace App\Controllers;

use App\Entities\Menu;
use App\Helpers\MessageHelpers;
use App\Models\MenuModel;
use App\src\fileTreatment\ManageFile;
use App\src\jwt\ManageJwt;
use App\src\slugBuilder\SlugBuilder;
use App\src\validation\FieldValidation;
use Exception;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->id = isset($_POST["menuId"]) ? (int)$_POST["menuId"] : null;
        $this->title = isset($_POST["menuTitle"]) ? htmlspecialchars($_POST["menuTitle"]) : null;
    }


    /**
     * Affiche le formulaire d'ajout de menu.
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

            $menu = new MenuModel();
            $menuList = $menu->findAll();

            http_response_code(200);
            $response = [
                "menuList" => $menuList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }

        Controller::render("menu/menuList", $response);
    }



    /**
     * Affiche le formulaire pour ajouter ou supprimer un menu PDF.
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


            if (isset($_GET['request']) && $_GET['request'] == 'delete' && isset($_GET['menuId'])) {

                $menuModel = new MenuModel();
                $menuData = $menuModel->findById($_GET['menuId']);

                $parameters = array(
                    "menuId" => $menuData->menuId,
                    "menuTitle" => $menuData->menuTitle,
                    "menuPath" => $menuData->menuPath,
                    "readonly" => "readonly",
                    "disabled" => "disabled",
                    "action" => "edit",
                    "buttonStyle" => "danger",
                    "libAction" => "Supprimer",
                    "actionSubmit" => "deleteMenu",

                );
            } else if (isset($_GET['request']) && $_GET['request'] == 'add') {
                $parameters = array(
                    "menuTitle" => "",
                    "readonly" => "",
                    "disabled" => "",
                    "action" => "add",
                    "buttonStyle" => "success",
                    "libAction" => "Ajouter",
                    "actionSubmit" => "addMenu",
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
        Controller::render("menu/form", $response);
    }




    /**
     * Ajoute un élément.
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

            $formData = [$this->title];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }


            $slug = SlugBuilder::slugify($this->title);

            $model = new MenuModel();
            $existingFile = $model->findAll();

            if ($existingFile) {
                throw new Exception("Un fichier existe déjà. Vous ne pouvez ajouter qu'un seul fichier.", 400);
            }

            $menufilePath = ManageFile::processfile('inputMenuFile', 'assets/pdf/' . $slug  . ".pdf", 500);

            if (!$menufilePath) {
                throw new Exception("Veuillez sélectionner un fichier valide.", 400);
            }

            $menu = new Menu();
            $menu->setMenutitle($this->title);
            $menu->setMenuPath($menufilePath);

            $isAdded = $model->add($menu);

            if ($isAdded == null) {
                throw new Exception("Erreur lors de l'ajout dans la base de données", 500);
            }

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("ajouté");

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
     * Supprime un élément.
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

            $slug = SlugBuilder::slugify($this->title);

            $filePath = 'assets/pdf/' . $slug .  ".pdf";
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $model = new MenuModel();
            $model->delete($this->id);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé");
            $response = [
                "successMessage" => $successMessage,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        echo json_encode($response);
    }
}
