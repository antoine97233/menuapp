<?php if (isset($error)) : ?>
    <?php echo $error; ?>
<?php else : ?>

    <!-- Sidebar Gauche -->
    <?php require_once('elements/sidebar.php'); ?>

    <!-- Ouverture Colonne droite -->
    <div class='col-md-9 col-lg-10 p-4 border'>



        <!-- Affichage du titre du Groupe -->
        <div class=' ml-4 mr-4 mt-4 mb-4 pb-2'>
            <h1 class="text-center">
                <?php if (isset($headertitle)) : ?>
                    <?php echo $headertitle; ?>
                <?php endif; ?>
            </h1>
        </div>


        <!-- Formulaire d'ajout de category -->
        <?php require_once('elements/form.php'); ?>


        <!-- Affichage de la liste des catégories contenant les items -->
        <?php require_once('elements/categoryList.php'); ?>



        <!-- Fermeture Colonne droite -->
    </div>
<?php endif; ?>