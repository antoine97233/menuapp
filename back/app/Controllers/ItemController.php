<?php

namespace App\Controllers;


use App\Models\ItemModel;
use App\Entities\Item;
use App\Helpers\MessageHelpers;
use App\Models\GroupModel;
use App\src\fileTreatment\ManageFile;
use App\src\jwt\ManageJwt;
use App\src\slugBuilder\SlugBuilder;
use App\src\validation\FieldValidation;
use Exception;


class ItemController extends Controller
{
    private $categoryId;
    private $price;
    private $stock;


    public function __construct()
    {
        $this->id = isset($_POST["itemId"]) ? (int)$_POST["itemId"] : null;
        $this->title = isset($_POST["itemTitle"]) ? htmlspecialchars($_POST["itemTitle"]) : null;
        $this->description = isset($_POST["itemDescription"]) ? htmlspecialchars($_POST["itemDescription"]) : null;
        $this->categoryId = isset($_POST["categoryId"]) ? (int)$_POST["categoryId"] : null;
        $this->price = isset($_POST["itemPrice"]) ? htmlspecialchars($_POST["itemPrice"]) : null;
        $this->stock = isset($_POST["itemStock"]) ? (int)$_POST["itemStock"] : null;
    }


    /**
     * Affiche le formulaire pour ajouter ou supprimer un item (admin).
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

            $groups = new GroupModel();
            $groupList = $groups->findAll();

            if (isset($_GET['request']) && $_GET['request'] == 'edit' && isset($_GET['itemId']) && isset($_GET["categoryId"])) {

                $itemModel = new ItemModel();
                $itemData = $itemModel->findById($_GET['itemId']);


                $parameters = array(
                    "itemId" => $_GET['itemId'],
                    "itemTitle" => $itemData->itemTitle,
                    "itemDescription" => $itemData->itemDescription,
                    "itemPrice" => $itemData->itemPrice,
                    "itemStock" => $itemData->itemStock,
                    "itemImage" => $itemData->itemImagePath,
                    "categoryId" => $itemData->categoryId,
                    "action" => "edit",
                    "buttonStyle" => "warning",
                    "libAction" => "Modifier",
                    "actionSubmit" => "updateItem",

                );
            } else if (isset($_GET['request']) && $_GET['request'] == 'add' && isset($_GET["categoryId"])) {
                $parameters = array(
                    "itemTitle" => "",
                    "itemDescription" => "",
                    "itemPrice" => "",
                    "itemStock" => "",
                    "itemImage" => "",
                    "categoryId" => $_GET["categoryId"],
                    "action" => "add",
                    "buttonStyle" => "success",
                    "libAction" => "Ajouter",
                    "actionSubmit" => "addItem",
                );
            } else {
                $parameters = null;
            }

            http_response_code(200);
            $response = [
                "parameters" => $parameters,
                "groupList" => $groupList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        Controller::render("item/index", $response);
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

            $formData = [$this->title, $this->description];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }

            if (!isset($_FILES['inputImageItem']) || $_FILES['inputImageItem']['error'] !== 0) {
                throw new Exception("Veuillez sélectionner une image valide.", 400);
            }

            $slug = SlugBuilder::slugify($this->title);

            if ($this->isItemTitleExists($slug, $this->categoryId)) {
                throw new Exception("Un élément avec ce titre existe déjà.", 400);
            }

            $itemImagePath = ManageFile::processImage('inputImageItem', 'assets/' . $this->categoryId . "-" . $slug . ".jpg", 500);

            if (!$itemImagePath) {
                throw new Exception("Veuillez sélectionner une image valide.", 400);
            }

            $model = new ItemModel();
            $item = new Item();

            $item->setItemTitle($this->title);
            $item->setItemDescription($this->description);
            $item->setItemPrice($this->price);
            $item->setItemStock($this->stock);
            $item->setItemImagePath($itemImagePath);
            $item->setCategoryId($this->categoryId);
            $item->setItemSlug($slug);

            $isAdded = $model->add($item);

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

            $itemImagePath = 'assets/' . $this->categoryId . "-" . $slug . ".jpg";
            if (file_exists($itemImagePath)) {
                unlink($itemImagePath);
            }

            $model = new ItemModel();
            $model->delete($this->id);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé");
            $response = [
                "successMessage" => $successMessage,
                "itemId" => $this->id,
                "categoryId" => $this->categoryId
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
     * Modifie un élément.
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

            $formData = [$this->title, $this->description];

            if (!FieldValidation::validateForm($formData)) {
                throw new Exception("Veuillez remplir tous les champs", 400);
            }

            $slug = SlugBuilder::slugify($this->title);


            if ($this->isItemTitleExists($slug, $this->categoryId, $this->id)) {
                throw new Exception("Un élément avec ce titre existe déjà.", 400);
            }


            $model = new ItemModel();
            $item = new Item();

            $item->setItemId($this->id);
            $item->setItemTitle($this->title);
            $item->setItemDescription($this->description);
            $item->setItemPrice($this->price);
            $item->setItemStock($this->stock);
            $item->setCategoryId($this->categoryId);
            $item->setItemSlug($slug);

            $existingItem = $model->findById($this->id);
            $oldImage = 'assets/' . $this->categoryId . '-' . $existingItem->itemSlug . ".jpg";
            $newImage = 'assets/' . $this->categoryId . '-' . $slug . ".jpg";

            if ($oldImage !== $newImage) {
                $item->setItemImagePath($newImage);
                rename($oldImage, $newImage);
            } else {
                $item->setItemImagePath($oldImage);
            }

            ManageFile::processImage('inputImageItem', 'assets/' . $this->categoryId . "-" . $slug . ".jpg", 500);

            $model->edit($item);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("modifié");
            $response = [
                'successMessage' => $successMessage,
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
     * Vérifie si un élément avec le même titre existe déjà dans la catégorie donnée,
     * en excluant l'élément actuellement en cours de modification.
     *
     * @param string $title     Le titre de l'élément.
     * @param int    $categoryId L'ID de la catégorie.
     * @param int|null $excludeId L'ID de l'élément à exclure de la recherche.
     *
     * @return bool True si un élément avec le même titre existe déjà, sinon False.
     */
    private function isItemTitleExists(string $title, int $categoryId, ?int $excludeId = null): bool
    {
        $model = new ItemModel();
        $existingItems = $model->findByTitleAndCategory($title, $categoryId, $excludeId);

        return !empty($existingItems);
    }
}
