<div class='col-md-3 col-lg-2 pt-4 pb-4 border' style="background-color: rgba(86,61,124,.1)">
    <div class="sticky-top">

        <div class="p-2 mb-4">
            <h3>Slider</h3>

        </div>

        <ul class="list-group" id='displayBulletPointSlider'>
            <?php if (isset($sliderList)) : ?>
                <!-- Liste des accès aux différents sliders -->

                <?php foreach ($sliderList as $row) : ?>
                    <?php $sliderId = $row['sliderId']; ?>
                    <?php $sliderName = $row['sliderName']; ?>

                    <li class='list-group-item' id='bulletedSliderList<?php echo $sliderId; ?>'>
                        <a class='text-decoration-none text-dark' id='sliderBullet<?php echo $sliderId; ?>' href='#pushSlider<?php echo $sliderId; ?>'>

                            <div class="justify-content-between d-flex">
                                <div>
                                    <h5>
                                        <?php echo $sliderName; ?>
                                    </h5>
                                </div>

                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

        </ul>
    </div>
</div>