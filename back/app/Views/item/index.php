<?php if (isset($error)) : ?>
    <?php echo $error; ?>

<?php elseif (isset($parameters) && $parameters === null) : ?>
    <?php header('Location: index.php?controller=home'); ?>
<?php else : ?>

    <!-- Sidebar Gauche -->
    <?php require_once("elements/sidebar.php"); ?>



    <!-- Ouverture Colonne droite -->
    <div id="rightColumn" class='col-md-9 col-lg-10 p-4  border'>



        <!-- Formulaire d'ajout de groupe -->
        <?php require_once("elements/form.php"); ?>




        <!-- Fermeture Colonne droite -->
    </div>

<?php endif; ?>