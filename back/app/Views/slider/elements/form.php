<div class='container mt-4 p-4 border col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: rgba(86, 61, 124, .1)'>
    <div class='container mb-4'>
        <h4 class='text-center'><span id='actionSlider'>Ajouter</span> un slider</h4>
    </div>
    <form class='form-inline flex flex-column text-center' id='formSlider'>

        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='sliderName' name='sliderName' placeholder="Plat du jour, Evènement... (ne pas mettre d'espace)" style='text-align:center' required>
        </div>

        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='sliderTitle' name='sliderTitle' placeholder='Titre affiché sur le site' style='text-align:center' required>
        </div>

        <div class='input-group p-2 mb-4'>
            <textarea class='form-control border' id='sliderDescription' name='sliderDescription' placeholder='Description affichée sur le site' style='text-align:center' required></textarea>
        </div>

        <div class='container overflow-hidden d-flex flex-column align-items-center'>

            <p>Aperçu visuel:</p>
            <div class="square-image overflow-hidden mb-2" style='height:300px;min-width:300px;max-width:300px;background-color:white'>
                <img id='sliderImagePreview' class='img-thumbnail'>
            </div>

            <div class="form-group mb-2">
                <input type='file' name="inputImageslider" class='m-2' id='inputImageSlider' required>
            </div>
        </div>

        <div class='mb-3 p-2 d-flex justify-content-center row'>
            <label for='categoryRanking' class='form-label'>Ordre d'apparition</label>
            <div class='col-sx-4 col-sm-2 col-lg-2'>
                <input type='number' class='form-control border text-center' id='sliderRank' name='sliderRank' style='text-align:center' value="<?php echo $sliderMaxRank; ?>" readonly required>
            </div>
        </div>

        <div class='form-group p-2'>
            <button type='button' class='btn btn-success m-auto shadow' id='addSlider'>Ajouter</button>
            <button type='button' class='btn btn-warning m-auto shadow' id='updateSlider' style='display: none;'>Modifier</button>
        </div>

        <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">

    </form>
</div>