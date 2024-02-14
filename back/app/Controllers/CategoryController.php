<?php

namespace App\Controllers;

use App\Models\GroupModel;
use App\Models\CategoryModel;
use App\Entities\Category;
use App\Helpers\MessageHelpers;
use App\Models\ItemModel;
use App\src\jwt\ManageJwt;
use App\src\slugBuilder\SlugBuilder;
use App\src\validation\FieldValidation;
use App\Views\category\Render;
use Exception;


class CategoryController extends Controller
{
    private $rank;
    private $groupId;

    public function __construct()
    {
        $this->id = isset($_POST["categoryId"]) ? (int)$_POST["categoryId"] : null;
        $this->title = isset($_POST["categoryTitle"]) ? htmlspecialchars($_POST["categoryTitle"]) : null;
        $this->description = isset($_POST["categoryDescription"]) ? htmlspecialchars($_POST["categoryDescription"]) : null;
        $this->rank = isset($_POST["categoryRank"]) ? (int)$_POST["categoryRank"] : null;
        $this->groupId = isset($_POST["groupId"]) ? (int)$_POST["groupId"] : null;
    }


    /**
     * Affiche la page d'administration des catégories.
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

            $groupModel = new GroupModel();
            $groupList = $groupModel->findAll();

            if (!isset($_GET["groupId"])) {
                header('Location: index.php?controller=home');
            } else {
                $groupId = intval($_GET["groupId"]);
            }

            $group = $groupModel->find($groupId);
            $headertitle = $group["groupTitle"];

            $categories = new CategoryModel();
            $categoryList = $categories->find($groupId);
            $categoryMaxRank = $categories->findMaxRank($groupId);

            http_response_code(200);
            $response = [
                "headertitle" => $headertitle,
                "groupList" => $groupList,
                "categoryList" => $categoryList,
                "categoryMaxRank" => $categoryMaxRank ? $categoryMaxRank[0]['maxRank'] + 1 : 1,

            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        Controller::render("category/index", $response);
    }


    /**
     * Ajoute une nouvelle catégorie.
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

            $model = new CategoryModel();
            $category = new Category();

            $category->setCategoryTitle($this->title);
            $category->setCategoryDescription($this->description);
            $category->setCategoryRank($this->rank);
            $category->setGroupId($this->groupId);
            $category->setCategorySlug($slug);

            $isAdded = $model->add($category);

            if ($isAdded == null) {
                throw new Exception("Erreur lors de l'ajout dans la base de données", 500);
            }

            http_response_code(200);
            $categoryId = $isAdded;
            $categoryTitle = $category->getCategoryTitle();
            $categoryDescription = $category->getCategoryDescription();
            $categoryRank = $category->getCategoryRank();
            $groupId = $category->getGroupId();

            $categoryBlock = Render::categoryBlock($groupId, $categoryId, $categoryTitle, $categoryDescription, $categoryRank);
            $categoryList = Render::categoryList($categoryId, $categoryTitle);
            $successMessage = MessageHelpers::successMessage("ajouté");

            $response = [
                'categoryBlock' => $categoryBlock,
                'categoryList' => $categoryList,
                'categoryRank' => $categoryRank,
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
     * Supprime une catégorie.
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

            $model = new CategoryModel();
            $itemModel = new ItemModel();
            $itemsInCategory = $itemModel->find($this->id);

            if (!empty($itemsInCategory)) {
                throw new Exception("Impossible de supprimer la catégorie car elle contient des items.", 409);
            }

            $model->delete($this->id);
            $categoryRankToDelete = $model->findRankById($this->id);
            $model->decrementAllRanks($categoryRankToDelete);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé");
            $response = [
                "successMessage" => $successMessage,
                "categoryId" => $this->id
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
     * Modifie une catégorie existante.
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

            $model = new CategoryModel();
            $category = new Category();

            $category->setCategoryId($this->id);
            $category->setCategoryTitle($this->title);
            $category->setCategoryDescription($this->description);
            $category->setCategorySlug($slug);

            $model->edit($category);

            http_response_code(200);
            $categoryId = $category->getCategoryId();
            $categoryTitle = $category->getCategoryTitle();
            $categoryDescription = $category->getCategoryDescription();

            $successMessage = MessageHelpers::successMessage("modifié");

            $response = [
                'successMessage' => $successMessage,
                "categoryId" => $categoryId,
                "categoryTitle" => $categoryTitle,
                "categoryDescription" => $categoryDescription,
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
     * Déplace une catégorie vers le haut dans la liste.
     *
     * @return void
     */
    public function upRankAction(): void
    {

        try {
            $this->checkAdminAuthentication();

            $thisCategoryId = isset($_POST["thisCategoryId"]) ? (int)$_POST["thisCategoryId"] : null;
            $thisCategoryRankValue = isset($_POST["thisCategoryRankValue"]) ? (int)$_POST["thisCategoryRankValue"] : null;
            $prevCategoryBlocId = isset($_POST["prevCategoryBlocId"]) ? (int)$_POST["prevCategoryBlocId"] : null;
            $previousCategoryRankValue = isset($_POST["previousCategoryRankValue"]) ? (int)$_POST["previousCategoryRankValue"] : null;

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $thisModel = new CategoryModel();
            $prevModel = new CategoryModel();

            $thisCategory = new Category();
            $prevCategory = new Category();

            $thisCategory->setCategoryId($thisCategoryId);
            $thisCategory->setCategoryRank($thisCategoryRankValue);
            $prevCategory->setCategoryId($prevCategoryBlocId);
            $prevCategory->setCategoryRank($previousCategoryRankValue);

            $thisModel->editRank($thisCategory);
            $prevModel->editRank($prevCategory);

            http_response_code(200);
            $thisCategoryId = $thisCategory->getCategoryId();
            $thisCategoryRankValue = $thisCategory->getCategoryRank();

            $prevCategoryBlocId = $prevCategory->getCategoryId();
            $previousCategoryRankValue = $prevCategory->getCategoryRank();

            $response = [
                "thisCategoryId" => $thisCategoryId,
                "thisCategoryRankValue" => $thisCategoryRankValue,
                "prevCategoryBlocId" => $prevCategoryBlocId,
                "previousCategoryRankValue" => $previousCategoryRankValue,
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
     * Déplace une catégorie vers le bas dans la liste.
     *
     * @return void
     */
    public function downRankAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            $thisCategoryId = isset($_POST["thisCategoryId"]) ? (int)$_POST["thisCategoryId"] : null;
            $thisCategoryRankValue = isset($_POST["thisCategoryRankValue"]) ? (int)$_POST["thisCategoryRankValue"] : null;
            $nextCategoryBlocId = isset($_POST["nextCategoryBlocId"]) ? (int)$_POST["nextCategoryBlocId"] : null;
            $nextCategoryRankValue = isset($_POST["nextCategoryRankValue"]) ? (int)$_POST["nextCategoryRankValue"] : null;

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }


            $thisModel = new CategoryModel();
            $nextModel = new CategoryModel();
            $thisCategory = new Category();
            $nextCategory = new Category();

            $thisCategory->setCategoryId($thisCategoryId);
            $thisCategory->setCategoryRank($thisCategoryRankValue);
            $nextCategory->setCategoryId($nextCategoryBlocId);
            $nextCategory->setCategoryRank($nextCategoryRankValue);

            $thisModel->editRank($thisCategory);
            $nextModel->editRank($nextCategory);

            http_response_code(200);
            $thisCategoryId = $thisCategory->getCategoryId();
            $thisCategoryRankValue = $thisCategory->getCategoryRank();
            $nextCategoryBlocId = $nextCategory->getCategoryId();
            $nextCategoryRankValue = $nextCategory->getCategoryRank();

            $response = [
                "thisCategoryId" => $thisCategoryId,
                "thisCategoryRankValue" => $thisCategoryRankValue,
                "nextCategoryBlocId" => $nextCategoryBlocId,
                "nextCategoryRankValue" => $nextCategoryRankValue,
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
