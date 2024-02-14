<?php if (isset($error)) : ?>
    <!-- Afficher le message d'erreur -->
    <?php echo $error; ?>
<?php else : ?>

    <!-- Sidebar Gauche -->
    <?php require_once('elements/sidebar.php'); ?>

    <!-- Ouverture Colonne droite -->
    <div class='col-md-9 col-lg-10 p-4 border'>


        <!-- Formulaire d'ajout de slider -->
        <?php require_once('elements/form.php'); ?>


        <!-- Affichage de la liste des sliders -->
        <?php require_once('elements/sliderList.php'); ?>



        <!-- Fermeture Colonne droite -->
    </div>
<?php endif; ?>