<?php if (isset($error)) : ?>
    <!-- Afficher le message d'erreur -->
    <?php echo $error; ?>
<?php else : ?>

    <div class='container ml-5 mr-5 mt-5 mb-5 min-vh-100'>
        <div class='position-absolute top-50 start-50 translate-middle'>
            <h1 class='text-center'>Accueil</h1>

            <div class='col-auto p-2 text-center'>
                <p>Bienvenue <span class="font-weight-bold"><?php echo $_SESSION['adminEmail']; ?></span> !</p>
            </div>
        </div>

    </div>
<?php endif; ?>