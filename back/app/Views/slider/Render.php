<?php

namespace App\Views\Slider;

class Render
{

    /**
     * Génère le HTML pour afficher un bloc de slider.
     *
     * @param string $sliderId        L'ID du slider.
     * @param string $sliderName      Le nom du slider.
     * @param string $sliderTitle     Le titre du slider.
     * @param string $sliderDescription La description du slider.
     * @param string $sliderImage     L'URL de l'image du slider.
     * @param string $sliderRank      Le classement du slider.
     * @return string                Le code HTML du bloc de slider.
     */
    public static function sliderBlock(string $sliderId, string $sliderName, string $sliderTitle, string $sliderDescription, string $sliderImage, string $sliderRank): string
    {
        return "
        <div class='container mb-4 mt-4 p-4 border col-sx-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: #e3f2fd;' id='pushSlider{$sliderId}' data-id='{$sliderId}'>
            <div class='container mb-4'>
                <h2 class='text-center'><span id='title{$sliderId}'>{$sliderName}</span></h4>
            </div>
        
            <div class='form-group p-2 d-flex justify-content-between'>
                <div class='d-flex m-2'>
                    <a href='#formSlider'><button type='button' class='btn btn-primary btn-sm editSlider shadow-sm m-2' data-id='{$sliderId}'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></a>
                    <button type='button' class='btn btn-warning btn-sm shadow-sm m-2' data-bs-toggle='modal' data-bs-target='#sliderModal{$sliderId}'><i class='fa-solid fa-eye' style='color:white'></i></button>
                    <button type='button' class='btn btn-danger btn-sm deleteSlider shadow-sm m-2' data-id='{$sliderId}'><i class='fa-solid fa-trash' style='color:white'></i></button>
                </div>
        
                <div class='modal fade' id='sliderModal{$sliderId}' tabindex='-1' aria-labelledby='sliderModal' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h1 class='modal-title fs-5' id='title{$sliderId}'>{$sliderName}</h1>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <div class='square-image overflow-hidden p-2 mb-2' id='imgPreview' style='height:300px;min-width:250px;max-width:300px;background-color:white'>
                                    <img class='img-thumbnail' id='sliderImgPreview{$sliderId}' src='{$sliderImage}'>
                                </div>
                                <div>
                                    <p><span class='text-secondary'>Titre : </span></br>
                                        <span id='subtitle{$sliderId}'>{$sliderTitle}</span>
                                    </p>
                                </div>
                                <div>
                                    <p><span class='text-secondary'>Description : </span></br>
                                        <span id='desc{$sliderId}'>{$sliderDescription}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class='d-flex m-2'>
                    <div class='input-group mt-1' style='width:40px;height:30px'>
                        <input type='text' id='inputRank{$sliderId}' class='form-control inputRankSlider' value='{$sliderRank}' readonly>
                    </div>
                    <div class='p-1 mt-1'>
                        <button type='button' class='btn btn-sm btn-primary downRankSlider shadow-sm' data-id='{$sliderId}'><i class='fa-solid fa-arrow-up'></i></button>
                    </div>
                    <div class='p-1 mt-1'>
                        <button type='button' class='btn btn-sm btn-primary upRankSlider shadow-sm' data-id='{$sliderId}'><i class='fa-solid fa-arrow-down'></i></button>
                    </div>
                </div>
            </div>
        </div>";
    }





    /**
     * Génère le code HTML pour afficher un élément de liste de slider.
     *
     * @param string $sliderId    L'ID du slider.
     * @param string $sliderTitle Le titre du slider.
     * @return string            Le code HTML de l'élément de liste de slider.
     */
    public static function sliderList(string $sliderId, string $sliderTitle): string
    {
        return "<li class='list-group-item' id='bulletedSliderList{$sliderId}'> 
            <a class='text-decoration-none text-dark id='sliderBullet{$sliderId}' href='#pushSlider{$sliderId}'>
                <div class='justify-content-between d-flex'>
                    <div>
                        <h5>{$sliderTitle}</h5>
                    </div>
                </div>
            </a>
        </li>";
    }
}
