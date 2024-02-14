<?php

namespace App\Controllers;

use App\Models\SliderModel;
use App\Entities\Slider;
use App\Helpers\MessageHelpers;
use App\Views\slider\Render;
use App\src\fileTreatment\ManageFile;
use App\src\jwt\ManageJwt;
use App\src\slugBuilder\SlugBuilder;
use App\src\validation\FieldValidation;
use Exception;



class SliderController extends Controller
{
    private $name;
    private $rank;


    public function __construct()
    {
        $this->id = isset($_POST["sliderId"]) ? (int)$_POST["sliderId"] : null;
        $this->name = isset($_POST["sliderName"]) ? htmlspecialchars($_POST["sliderName"]) : null;
        $this->title = isset($_POST["sliderTitle"]) ? htmlspecialchars($_POST["sliderTitle"]) : null;
        $this->description = isset($_POST["sliderDescription"]) ? htmlspecialchars($_POST["sliderDescription"]) : null;
        $this->rank = isset($_POST["sliderRank"]) ? (int)$_POST["sliderRank"] : null;
    }



    /**
     * Affiche la page d'administration des sliders.
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

            $sliders = new SliderModel();
            $sliderList = $sliders->findAll();
            $sliderMaxRank = $sliders->findMaxRank();

            http_response_code(200);
            $response = [
                "sliderList" => $sliderList,
                "sliderMaxRank" => $sliderMaxRank ? $sliderMaxRank[0]['maxRank'] + 1 : 1,
            ];
        } catch (Exception $e) {
            $error = $this->handleException($e);
            $response = [
                'error' => $error
            ];
        }
        Controller::render("slider/index", $response);
    }


    /**
     * Ajoute un slider.
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


            $formData = [$this->name, $this->title, $this->description];

            if (!FieldValidation::validateForm($formData) || !FieldValidation::validateNoSpaces($this->name)) {
                throw new Exception("Veuillez remplir tous les champs correctement", 400);
            }

            if (!isset($_FILES['inputImageSlider']) || $_FILES['inputImageSlider']['error'] !== 0) {
                throw new Exception("Veuillez sélectionner une image valide.", 400);
            }


            if ($this->isSliderNameExists($this->name)) {
                throw new Exception("Un slider avec ce nom existe déjà.", 400);
            }

            $sliderImagePath = ManageFile::processImage('inputImageSlider', 'assets/news/'  . $this->name . ".jpg", 2000);

            if (!$sliderImagePath) {
                throw new Exception("Veuillez sélectionner une image valide.", 400);
            }

            $model = new SliderModel();
            $slider = new Slider();

            $slider->setSliderName($this->name);
            $slider->setSliderTitle($this->title);
            $slider->setSliderDescription($this->description);
            $slider->setSliderImage($sliderImagePath);
            $slider->setSliderRank($this->rank);
            $slider->setSliderSlug($this->name);

            $isAdded = $model->add($slider);

            if ($isAdded == null) {
                throw new Exception("Erreur lors de l'ajout dans la base de données", 500);
            }

            http_response_code(200);
            $sliderId = $isAdded;
            $sliderName = $slider->getSliderName();
            $sliderTitle = $slider->getSliderTitle();
            $sliderDescription = $slider->getSliderDescription();
            $sliderImage = $slider->getSliderImage();
            $sliderRank = $slider->getSliderRank();

            $sliderBlock = Render::sliderBlock($sliderId, $sliderName, $sliderTitle, $sliderDescription, $sliderImage,  $sliderRank);
            $sliderList = Render::sliderList($sliderId, $sliderName);
            $successMessage = MessageHelpers::successMessage("ajouté");

            $response = [
                'sliderBlock' => $sliderBlock,
                'sliderList' => $sliderList,
                'sliderRank' => $sliderRank,
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
     * Supprime un slider.
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

            $model = new SliderModel();

            $itemImagePath = 'assets/news/' . $this->name . ".jpg";
            if (file_exists($itemImagePath)) {
                unlink($itemImagePath);
            }

            $model->delete($this->id);
            $sliderRankToDelete = $model->findRankById($this->id);
            $model->decrementAllRanks($sliderRankToDelete);

            http_response_code(200);
            $successMessage = MessageHelpers::successMessage("supprimé");
            $response = [
                "sliderId" => $this->id,
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




    /**
     * Modifie un slider.
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
            $this->name = htmlspecialchars_decode($this->name);
            $this->description = htmlspecialchars_decode($this->description);

            $formData = [$this->title, $this->name, $this->description];

            if (!FieldValidation::validateForm($formData) || !FieldValidation::validateNoSpaces($this->name)) {
                throw new Exception("Veuillez remplir tous les champs correctement", 400);
            }


            if ($this->isSliderNameExists($this->name, $this->id)) {
                throw new Exception("Un élément avec ce titre existe déjà.", 400);
            }

            $model = new SliderModel();
            $slider = new Slider();

            $slider->setSliderId($this->id);
            $slider->setSliderName($this->name);
            $slider->setSliderTitle($this->title);
            $slider->setSliderDescription($this->description);
            $slider->setSliderRank($this->rank);
            $slider->setSliderSlug($this->name);

            $existingSlider = $model->findById($this->id);
            $oldImage = 'assets/news/' . $existingSlider->sliderName . ".jpg";
            $newImage = 'assets/news/' . $this->name . ".jpg";

            if ($oldImage !== $newImage) {
                $slider->setSliderImage($newImage);
                rename($oldImage, $newImage);
            } else {
                $slider->setSliderImage($oldImage);
            }

            ManageFile::processImage('inputImage', 'assets/news/' . $this->name . ".jpg", 2000);

            $model->edit($slider);

            http_response_code(200);
            $sliderId = $slider->getSliderId();
            $sliderName = $slider->getSliderName();
            $sliderTitle = $slider->getSliderTitle();
            $sliderDescription = $slider->getSliderDescription();
            $sliderImage = $slider->getSliderImage();

            $successMessage = MessageHelpers::successMessage("modifié");

            $response = [
                "sliderId" => $sliderId,
                "sliderName" => $sliderName,
                "sliderTitle" => $sliderTitle,
                "sliderDescription" => $sliderDescription,
                "sliderImage" => $sliderImage,
                "successMessage" => $successMessage
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
     * Réordonne un slider vers le haut.
     *
     * @return void
     */
    public function upRankAction(): void
    {

        try {
            $this->checkAdminAuthentication();

            $thisSliderId = isset($_POST["thisSliderId"]) ? (int)$_POST["thisSliderId"] : null;
            $thisSliderRankValue = isset($_POST["thisSliderRankValue"]) ? (int)$_POST["thisSliderRankValue"] : null;
            $prevSliderBlocId = isset($_POST["prevSliderBlocId"]) ? (int)$_POST["prevSliderBlocId"] : null;
            $previousSliderRankValue = isset($_POST["previousSliderRankValue"]) ? (int)$_POST["previousSliderRankValue"] : null;

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }


            $thisModel = new SliderModel();
            $prevModel = new SliderModel();

            $thisSlider = new Slider();
            $prevSlider = new Slider();

            $thisSlider->setSliderId($thisSliderId);
            $thisSlider->setSliderRank($thisSliderRankValue);
            $prevSlider->setSliderId($prevSliderBlocId);
            $prevSlider->setSliderRank($previousSliderRankValue);

            $thisModel->editRank($thisSlider);
            $prevModel->editRank($prevSlider);

            http_response_code(200);
            $thisSliderId = $thisSlider->getSliderId();
            $thisSliderRankValue = $thisSlider->getSliderRank();
            $prevSliderBlocId = $prevSlider->getSliderId();
            $previousSliderRankValue = $prevSlider->getSliderRank();

            $response = [
                "thisSliderId" => $thisSliderId,
                "thisSliderRankValue" => $thisSliderRankValue,
                "prevSliderBlocId" => $prevSliderBlocId,
                "previousSliderRankValue" => $previousSliderRankValue,
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
     * Réordonne un slider vers le bas.
     *
     * @return void
     */
    public function downRankAction(): void
    {

        try {
            $this->checkAdminAuthentication();

            $thisSliderId = isset($_POST["thisSliderId"]) ? (int)$_POST["thisSliderId"] : null;
            $thisSliderRankValue = isset($_POST["thisSliderRankValue"]) ? (int)$_POST["thisSliderRankValue"] : null;
            $nextSliderBlocId = isset($_POST["nextSliderBlocId"]) ? (int)$_POST["nextSliderBlocId"] : null;
            $nextSliderRankValue = isset($_POST["nextSliderRankValue"]) ? (int)$_POST["nextSliderRankValue"] : null;

            if ($this->checkCsrfTokenValid() || !ManageJwt::validate()) {
                throw new Exception("Session expirée, veuillez vous reconnecter.", 401);
            }


            $thisModel = new SliderModel();
            $nextModel = new SliderModel();
            $thisSlider = new Slider();
            $nextSlider = new Slider();

            $thisSlider->setSliderId($thisSliderId);
            $thisSlider->setSliderRank($thisSliderRankValue);
            $nextSlider->setSliderId($nextSliderBlocId);
            $nextSlider->setSliderRank($nextSliderRankValue);

            $thisModel->editRank($thisSlider);
            $nextModel->editRank($nextSlider);

            http_response_code(200);
            $thisSliderId = $thisSlider->getSliderId();
            $thisSliderRankValue = $thisSlider->getSliderRank();
            $nextSliderBlocId = $nextSlider->getSliderId();
            $nextSliderRankValue = $nextSlider->getSliderRank();

            $response = [
                "thisSliderId" => $thisSliderId,
                "thisSliderRankValue" => $thisSliderRankValue,
                "nextSliderBlocId" => $nextSliderBlocId,
                "nextSliderRankValue" => $nextSliderRankValue,
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
    private function isSliderNameExists(string $title, ?int $excludeId = null): bool
    {
        $model = new SliderModel();
        $existingItems = $model->findByName($title, $excludeId);

        return !empty($existingItems);
    }
}
