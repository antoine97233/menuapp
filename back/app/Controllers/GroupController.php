<?php

namespace App\Controllers;

use App\Models\GroupModel;
use App\Entities\Group;
use App\Helpers\MessageHelpers;
use App\Views\group\Render;
use App\Models\CategoryModel;
use App\src\jwt\ManageJwt;
use App\src\slugBuilder\SlugBuilder;
use App\src\validation\FieldValidation;
use Exception;



class GroupController extends Controller
{
    public function __construct()
    {
        $this->id = isset($_POST["groupId"]) ? (int)$_POST["groupId"] : null;
        $this->title = isset($_POST["groupTitle"]) ? htmlspecialchars($_POST["groupTitle"]) : null;
        $this->description = isset($_POST["groupDescription"]) ? htmlspecialchars($_POST["groupDescription"]) : null;
    }


    /**
     * Affiche la liste des groupes.
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

            $groups = new GroupModel();
            $groupList = $groups->findAll();

            http_response_code(200);
            $response = [
                "groupList" => $groupList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }

        Controller::render("group/index", $response);
    }





    /**
     * ajoute un groupe.
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

            $formData = [$this->title, $this->description];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }

            $slug = SlugBuilder::slugify($this->title);

            $model = new GroupModel();
            $group = new Group();

            $group->setGroupTitle($this->title);
            $group->setGroupDescription($this->description);
            $group->setGroupSlug($slug);

            $isAdded = $model->add($group);

            if ($isAdded == null) {
                throw new Exception("Erreur lors de l'ajout dans la base de données", 500);
            }

            http_response_code(200);
            $groupId = $isAdded;
            $groupTitle = $group->getGroupTitle();
            $groupDescription = $group->getGroupDescription();

            $groupBlock = Render::groupBlock($groupId, $groupTitle, $groupDescription);
            $groupList = Render::groupList($groupId, $groupTitle);
            $successMessage = MessageHelpers::successMessage("ajouté");

            $response = [
                'groupBlock' => $groupBlock,
                'groupList' => $groupList,
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
     * Supprime un groupe.
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

            $model = new GroupModel();
            $categoryModel = new CategoryModel();
            $categoriesInGroup = $categoryModel->find($this->id);

            if (!empty($categoriesInGroup)) {
                throw new Exception("Impossible de supprimer le groupe car il contient des catégories.", 409);
            }

            $model->delete($this->id);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé");
            $response =  [
                "successMessage" => $successMessage,
                "groupId" => $this->id,
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
     * Édite un groupe existant.
     *
     * @return void
     */
    public function editAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $this->title = htmlspecialchars_decode($this->title);
            $this->description = htmlspecialchars_decode($this->description);

            $formData = [$this->title, $this->description];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }

            $slug = SlugBuilder::slugify($this->title);

            $model = new GroupModel();
            $group = new Group();

            $group->setGroupId($this->id);
            $group->setGroupTitle($this->title);
            $group->setGroupDescription($this->description);
            $group->setGroupSlug($slug);

            $model->edit($group);

            http_response_code(200);
            $groupId = $group->getGroupId();
            $groupTitle = $group->getGroupTitle();
            $groupDescription = $group->getGroupDescription();

            $groupUrl = Render::newUrl($groupId);
            $successMessage = MessageHelpers::successMessage("modifié");

            $response = [
                'successMessage' => $successMessage,
                'groupId' => $groupId,
                'groupTitle' => $groupTitle,
                'groupDescription' => $groupDescription,
                'groupUrl' => $groupUrl
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
