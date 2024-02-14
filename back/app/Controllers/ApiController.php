<?php

namespace App\Controllers;

use App\Models\GroupModel;
use App\Models\CategoryModel;
use App\Models\ItemModel;
use App\Models\MenuModel;
use App\Models\SliderModel;
use App\src\api\ApiBuilder;
use App\src\jwt\ManageJwt;
use Exception;

class ApiController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = getenv('BASE_URL');
    }

    /**
     * Affiche la page d'accueil de l'API.
     *
     * @return void
     * @throws Exception En cas d'erreur d'authentification ou de validation JWT.
     */
    public function indexAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $model = new GroupModel();
            $groupList = $model->findAll();

            http_response_code(200);
            $response = [
                "groupList" => $groupList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response =  [
                "error" => $error
            ];
        }
        Controller::render("api/index",  $response);
    }

    /**
     * Affiche les sliders et la liste des groupes.
     *
     * @return void
     * @throws Exception En cas d'erreur d'authentification ou de validation JWT.
     */
    public function sliderAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $groupModel = new GroupModel();
            $groupList = $groupModel->findAll();

            $sliderModel = new SliderModel();
            $sliderList = $sliderModel->findAll();

            $apiBuilder = new ApiBuilder();
            $dataSlider = $apiBuilder->buildSliderData($sliderList);

            http_response_code(200);
            $response = [
                'dataSlider' => $dataSlider,
                'groupList' => $groupList,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response =  [
                "error" => $error
            ];
        }
        Controller::render("api/index", $response);
    }

    /**
     * Affiche le menu PDF téléchargé .
     *
     * @return void
     * @throws Exception En cas d'erreur d'authentification ou de validation JWT.
     */
    public function menuAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $menuModel = new MenuModel();
            $dataFileMenu = $menuModel->findAll();

            http_response_code(200);
            $response = [
                'dataFileMenu' => $dataFileMenu,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response =  [
                "error" => $error
            ];
        }
        Controller::render("api/index", $response);
    }

    /**
     * Affiche les données d'un groupe spécifique ou de tous les groupes.
     *
     * @return void
     * @throws Exception En cas d'erreur d'authentification ou de validation JWT.
     */
    public function groupAction(): void
    {
        try {
            $this->checkAdminAuthentication();

            if (!ManageJwt::validate($_SESSION['jwtToken'])) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }

            $groupModel = new GroupModel();
            $apiBuilder = new ApiBuilder;

            $groupList = $groupModel->findAll();

            http_response_code(200);
            $response = [
                'groupList' => $groupList,
            ];

            if (isset($_GET["id"])) {
                $groupId = $_GET["id"];
                $response['dataGroup'] = $apiBuilder->buildDataByGroup($groupId, $groupModel, new CategoryModel, new ItemModel);
            } else {
                $response['dataAllGroups'] = $apiBuilder->buildAllGroupsData($groupModel, new CategoryModel, new ItemModel);
            }
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response =  [
                "error" => $error
            ];
        }
        Controller::render("api/index", $response);
    }

    /**
     * Affiche les sliders en format JSON.
     *
     * @return void
     */
    public function getSliderAction(): void
    {
        try {
            $sliderModel = new SliderModel();
            $sliderList = $sliderModel->findAll();

            $apiBuilder = new ApiBuilder();
            $dataSlider = $apiBuilder->buildSliderData($sliderList);

            header('Access-Control-Allow-Origin: ' . $this->baseUrl);
            header("Access-Control-Allow-Headers: Content-Type");
            header('Content-Type: application/json');

            $response = json_encode($dataSlider, JSON_UNESCAPED_UNICODE);
            echo $response;
        } catch (Exception $e) {
            echo $this->handleExceptionJson($e);
        }
    }

    /**
     * Affiche le menu au format PDF.
     *
     * @return void
     */
    public function getMenuAction(): void
    {
        try {
            $menuModel = new MenuModel();
            $dataFileMenu = $menuModel->findAll();

            if (empty($dataFileMenu)) {
                throw new Exception("Aucun fichier PDF trouvé.", 404);
            }

            $pdfPath = $this->baseUrl . '/back/admin/' . $dataFileMenu[0]["menuPath"];

            http_response_code(200);
            header('Access-Control-Allow-Origin: ' . $this->baseUrl);

            echo "<embed src='{$pdfPath}' type='application/pdf' width='100%' height='1000px'>";
        } catch (Exception $e) {
            echo $this->handleExceptionJson($e);
        }
    }

    /**
     * Affiche les données d'un groupe en format JSON.
     *
     * @return void
     */
    public function getGroupAction(): void
    {
        try {
            if (!isset($_GET["id"])) {
                throw new Exception("L'identifiant du groupe est manquant.", 400);
            }

            $groupId = $_GET["id"];
            $apiBuilder = new ApiBuilder;
            $dataGroup = $apiBuilder->buildDataByGroup(
                $groupId,
                new GroupModel,
                new CategoryModel,
                new ItemModel
            );

            header('Access-Control-Allow-Origin: ' . $this->baseUrl);
            header("Access-Control-Allow-Headers: Content-Type");
            header('Content-Type: application/json');

            $response = json_encode($dataGroup, JSON_UNESCAPED_UNICODE);
            echo $response;
        } catch (Exception $e) {
            echo $this->handleExceptionJson($e);
        }
    }

    /**
     * Affiche les données de tous les groupes en format JSON.
     *
     * @return void
     */
    public function getAllGroupsAction(): void
    {
        try {
            $apiBuilder = new ApiBuilder;
            $dataGroup = $apiBuilder->buildAllGroupsData(
                new GroupModel,
                new CategoryModel,
                new ItemModel
            );
            header('Access-Control-Allow-Origin: ' . $this->baseUrl);
            header("Access-Control-Allow-Headers: Content-Type");
            header('Content-Type: application/json');

            $response = json_encode($dataGroup, JSON_UNESCAPED_UNICODE);
            echo $response;
        } catch (Exception $e) {
            echo $this->handleExceptionJson($e);
        }
    }

    /**
     * Exporte les données d'un groupe et ses catégories au format CSV.
     *
     * @param int $groupId L'identifiant du groupe à exporter.
     * @return void
     */
    public function exportGroupAction($groupId): void
    {
        try {
            if (!isset($_GET["id"])) {
                throw new Exception("L'identifiant du groupe est manquant.", 400);
            }

            $groupId = $_GET["id"];
            $apiBuilder = new ApiBuilder;
            $dataGroup = $apiBuilder->buildDataByGroup(
                $groupId,
                new GroupModel,
                new CategoryModel,
                new ItemModel
            );

            $csvContent = "GroupTitle,GroupDescription,CategoryTitle,CategoryDescription,ItemTitle,ItemDescription,ItemPrice,ItemImagePath,ItemStock\n";

            foreach ($dataGroup as $group) {
                $groupInfo = $group['group'];
                foreach ($group['categories'] as $category) {
                    $categoryInfo = $category['category'];
                    foreach ($category['items'] as $item) {
                        $itemInfo = $item;

                        $csvContent .= "{$groupInfo['groupTitle']},{$groupInfo['groupDescription']},{$categoryInfo['categoryTitle']},{$categoryInfo['categoryDescription']},{$itemInfo['itemTitle']},{$itemInfo['itemDescription']},{$itemInfo['itemPrice']},{$itemInfo['itemImagePath']},{$itemInfo['itemStock']}\n";
                    }
                }
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=group_data.csv');
            echo $csvContent;
        } catch (Exception $e) {
            echo $this->handleExceptionJson($e);
        }
    }
}
