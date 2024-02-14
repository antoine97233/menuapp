<?php if (isset($error)) : ?>
    <?php echo $error; ?>
<?php else : ?>
    <!-- Sidebar Gauche -->
    <?php require_once("elements/sidebar.php"); ?>

    <!-- Ouverture Colonne droite -->
    <div id="rightColumn" class='col-md-9 col-lg-10 p-4  border'>


        <!-- Formulaire d'ajout de groupe -->
        <?php require_once("elements/form.php"); ?>

        <!-- Liste de tous les groupes de produit -->
        <?php require_once("elements/groupList.php"); ?>

        <!-- Fermeture Colonne droite -->
    </div>
<?php endif; ?>