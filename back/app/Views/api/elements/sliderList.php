<div class='container mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 rounded shadow-sm'>


    <?php if (isset($dataSlider) && !empty($dataSlider)) : ?>
        <!-- Si des données sont renseignées, les afficher -->
        <div class="d-flex container align-items-center p-2 mb-4">

            <div>
                <p class="pb-2 mb-0">
                    <a id="sliderApiLink" class="link-opacity-100" href="index.php?controller=api&action=getSlider" style="color: #007bff; padding: 8px; border-radius: 4px;">
                        <span class="p-2"><i class="fa-solid fa-link"></i></span>Sliders - Lien vers l'API - format JSON</a>
                </p>
            </div>
        </div>

        <!-- Aperçu de la structure des données dans une fenêtre -->
        <div class="container p-2 border overflow-auto">
            <pre><?php echo json_encode($dataSlider, JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php elseif (isset($dataSlider) && empty($dataSlider)) : ?>
        <!-- Affichage si données vides -->
        <div class="container p-2 border">
            <?php echo "no data"; ?>
        </div>
    <?php endif; ?>

</div>