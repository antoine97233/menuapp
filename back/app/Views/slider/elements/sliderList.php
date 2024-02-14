<div class="pb-4" id='displaySlider'>
    <?php if (isset($sliderList)) : ?>

        <?php foreach ($sliderList as $index => $row) {
            $sliderId = $row['sliderId'];
            $sliderName = $row['sliderName'];
            $sliderTitle = $row['sliderTitle'];
            $sliderDescription = $row['sliderDescription'];
            $sliderImage = $row['sliderImagePath'];
            $sliderRank = $row['sliderRank'];
        ?>

            <div class='container mb-4 mt-4 pt-4 border col-sx-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: #e3f2fd;' id='pushSlider<?php echo $sliderId ?>' data-id='<?php echo $sliderId ?>'>
                <div class='container mb-4'>
                    <h2 class='text-center'><span id='title<?php echo $sliderId ?>'><?php echo $sliderName; ?></span></h4>
                </div>


                <div class='form-group p-2 d-flex justify-content-between'>
                    <div class="d-flex m-2">
                        <a href="#formSlider"><button type='button' class='btn btn-primary btn-sm editSlider shadow-sm m-2' data-id='<?php echo $sliderId ?>'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></a>
                        <button type='button' class='btn btn-warning btn-sm shadow-sm m-2' data-bs-toggle="modal" data-bs-target="#sliderModal<?php echo $sliderId ?>"><i class='fa-solid fa-eye' style='color:white'></i></button>
                        <button type='button' class='btn btn-danger btn-sm deleteSlider shadow-sm  m-2' data-id='<?php echo $sliderId ?>'><i class='fa-solid fa-trash' style='color:white'></i></button>

                    </div>


                    <div class="modal fade" id="sliderModal<?php echo $sliderId ?>" tabindex="-1" aria-labelledby="sliderModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id='title<?php echo $sliderId ?>'><?php echo $sliderName ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="square-image overflow-hidden p-2 mb-2" id='imgPreview' style='height:300px;min-width:250px;max-width:300px;background-color:white'>
                                        <img class="img-thumbnail" id='sliderImgPreview<?php echo $sliderId ?>' src='<?php echo $sliderImage ?>'>
                                    </div>
                                    <div>
                                        <p><span class="text-secondary">Titre : </span></br>
                                            <span id='subtitle<?php echo $sliderId ?>'><?php echo $sliderTitle; ?></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p><span class="text-secondary">Description : </span></br>
                                            <span id='desc<?php echo $sliderId ?>'><?php echo $sliderDescription; ?></span>
                                        </p>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>


                    <div class='d-flex m-2'>
                        <div class='input-group mt-1' style='width:40px;height:30px'>
                            <input type='text' id='inputRank<?php echo $sliderId ?>' class='form-control inputRankSlider' value='<?php echo $sliderRank ?>' readonly>
                        </div>
                        <div class='p-1 mt-1'>
                            <button type='button' class='btn btn-sm btn-primary downRankSlider shadow-sm' data-id='<?php echo $sliderId ?>'><i class='fa-solid fa-arrow-up'></i></button>
                        </div>
                        <div class='p-1 mt-1'>
                            <button type='button' class='btn btn-sm btn-primary upRankSlider shadow-sm' data-id='<?php echo $sliderId ?>'><i class='fa-solid fa-arrow-down'></i></button>
                        </div>
                    </div>

                </div>
            </div>

        <?php } ?>
    <?php endif; ?>

</div>