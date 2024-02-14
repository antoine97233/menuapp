<?php if (isset($error) || !isset($_SESSION['token'])) : ?>
    <?php echo $error; ?>

<?php else : ?>

    <!-- formulaire de connexion à l'application -->

    <div id='formAuth' class='col-md-12 pt-4 pb-4 border'>
        <div id='containerFormAuth' class='position-absolute top-50 start-50 translate-middle'>
            <h4 class='pb-4'>Renseignez vos informations</h4>
            <form class='justify-content' id='newAccountLogin' method='post' action='index.php?controller=auth&action=login'>


                <div class='col-auto p-2'>
                    <label for='adminEmail'>Email</label>
                    <input type='email' class='form-control' name='adminEmail' id='adminEmail' required>
                </div>

                <div class='col-auto p-2'>
                    <label for='adminPassword'>Password</label>
                    <input type='password' class='form-control' name='adminPassword' id='adminPassword' required>
                </div>

                <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">



                <div class='col-auto p-4 text-center'>
                    <button type='button' id="login" class='btn btn-primary mb-3 shadow-sm' name='login' style='width:200px' value=''>Connexion</button>
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>