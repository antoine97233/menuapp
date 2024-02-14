<!-- Formulaire d'ajout d'aministrateur -->
<div class='col-md-12 pt-4 pb-4 border'>
    <div style='max-width:400px' class='position-relative top-50 start-50 translate-middle'>
        <h4 class='pb-4'>Renseignez vos informations</h4>
        <form class='justify-content' id='newAccountLogin'>

            <div class='col-auto p-2'>
                <label for='adminName'>Name</label>
                <input type='text' class='form-control' name='adminName' id='adminName' value='<?php echo htmlspecialchars($parameters["adminName"], ENT_QUOTES); ?>' <?php echo htmlspecialchars($parameters["readonly"]) ?> required>
            </div>

            <div class='col-auto p-2'>
                <label for='adminEmail'>Email</label>
                <input type='email' class='form-control' name='adminEmail' id='adminEmail' value='<?php echo htmlspecialchars($parameters["adminEmail"], ENT_QUOTES); ?>' <?php echo  htmlspecialchars($parameters["readonly"]) ?> required>
            </div>

            <?php if (isset($_GET['request']) && $_GET['request'] === 'add') : ?>
                <div class='col-auto p-2'>
                    <label for='adminPassword'>Password</label>
                    <input type='password' class='form-control' name='adminPassword' id='adminPassword' value='<?php echo htmlspecialchars($parameters["adminPassword"], ENT_QUOTES); ?>' <?php echo htmlspecialchars($parameters["readonly"]); ?> required>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['request']) && $_GET['request'] === 'delete') : ?>
                <input type="hidden" id="adminId" name="adminId" value="<?php echo htmlspecialchars($parameters["adminId"], ENT_QUOTES); ?>">
            <?php endif; ?>

            <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">

            <div class='col-auto p-4 text-center'>
                <a href='index.php?controller=user' type='button' class='btn btn-primary mb-3 shadow-sm' style='width:200px'>Précédent</a>
                <button type='button' id='<?php echo $parameters["actionSubmit"] ?>' class=' btn btn-<?php echo $parameters["buttonStyle"] ?> mb-3 shadow-sm' name='action' style='width:200px' value='<?php echo $parameters["action"] ?>'><?php echo $parameters["libAction"] ?></button>
            </div>



        </form>
    </div>
</div>