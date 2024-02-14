<!-- Formulaire d'jaout d'un menu PDF -->

<div class='col-12 p-4 border'>


    <div class='container justify-content-between mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-6 col-xl-4 rounded shadow-sm' style='background-color: rgba(86,61,124,.1)'>
        <h4 class='text-center mb-4'><?php echo $parameters["libAction"] ?> un Menu PDF</h4>

        <form id="formItem" class="text-center">
            <div class="form-group mb-2">
                <label for="inputMenuTitle" class="p-2">Titre du menu</label>
                <input type="text" class="form-control text-center" id="inputMenuTitle" name="inputMenuTitle" value='<?php echo htmlspecialchars($parameters["menuTitle"], ENT_QUOTES); ?>' <?php echo $parameters["disabled"]; ?> <?php echo $parameters["readonly"]; ?> required>
            </div>

            <?php if (isset($_GET["request"]) && $_GET['request'] === 'delete') : ?>
                <div class="form-group mb-2">
                    <label for="inputMenuPath" class="p-2">Nom du fichier</label>
                    <input type="text" class="form-control text-center" id="inputMenuPath" name="inputMenuPath" value='<?php echo htmlspecialchars($parameters["menuPath"], ENT_QUOTES); ?>' disabled readonly>
                </div>
                <input type="hidden" id="inputMenuId" name="inputMenuId" value='<?php echo $parameters["menuId"]; ?>'>

            <?php else : ?>
                <div class="form-group mb-2">
                    <label for="inputMenuPdf" class="p-2">Télécharger un fichier PDF:</label>
                    <input type='file' id='inputMenuPdf' required>
                </div>


            <?php endif; ?>




            <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">

            <div class='col-auto p-4 text-center'>
                <button type='button' id="<?php echo $parameters['actionSubmit']; ?>" class='btn btn-<?php echo $parameters['buttonStyle']; ?>'><?php echo $parameters['libAction']; ?></button>
            </div>
        </form>
    </div>


</div>