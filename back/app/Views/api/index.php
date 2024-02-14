<?php if (isset($error)) : ?>
    <!-- Afficher le message d'erreur s'il est renvoyé -->
    <?php echo $error; ?>
<?php else : ?>

    <!-- Sidebar Gauche -->
    <?php require_once('elements/sidebar.php'); ?>

    <!-- Ouverture Colonne droite -->
    <div class='col-md-9 col-lg-10 p-4 border'>



        <?php if (isset($_GET["action"]) && $_GET["action"] === "slider") : ?>

            <?php require_once('elements/sliderList.php'); ?>

        <?php elseif (isset($_GET["action"]) && $_GET["action"] === "group") : ?>

            <?php require_once('elements/groupList.php'); ?>

        <?php elseif (isset($_GET["action"]) && $_GET["action"] === "menu") : ?>

            <?php require_once('elements/menuList.php'); ?>

        <?php endif; ?>


        <!-- Fermeture Colonne droite -->
    </div>
<?php endif; ?>