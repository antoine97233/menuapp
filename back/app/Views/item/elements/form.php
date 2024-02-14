<div class='container justify-content-between mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 rounded shadow-sm' style='background-color: rgba(86,61,124,.1)'>
    <h4 class='text-center'><?php echo $parameters["libAction"] ?> un Produit</h4>

    <form id="formItem">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="inputItemTitle" class="p-2">Titre :</label>
                    <input type="text" class="form-control" id="inputItemTitle" name="inputItemTitle" value='<?php echo htmlspecialchars($parameters["itemTitle"], ENT_QUOTES); ?>' required>
                </div>
                <div class="form-group mb-2">
                    <label for="inputItemDescription" class="p-2">Description :</label>
                    <textarea class="form-control" id="inputItemDescription" name="inputItemDescription" rows="10" required><?php echo htmlspecialchars($parameters["itemDescription"], ENT_QUOTES); ?></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="inputItemPrice" class="p-2">Prix :</label>
                    <input type="text" class="form-control" id="inputItemPrice" name="inputItemPrice" value='<?php echo htmlspecialchars($parameters["itemPrice"], ENT_QUOTES); ?>' required>
                </div>
                <div class="form-group mb-2">
                    <label for="inputItemStock" class="p-2">Stock :</label>
                    <input type="number" class="form-control" id="inputItemStock" name="inputItemStock" value='<?php echo htmlspecialchars($parameters["itemStock"], ENT_QUOTES); ?>' required>
                </div>
            </div>

            <?php if (isset($_GET["itemId"]) && isset($_GET["request"]) && $_GET["request"] === "edit") : ?>
                <input type='hidden' id='inputItemId' name="inputItemId" value="<?php echo $parameters["itemId"] ?>">
            <?php endif; ?>



            <div class="col-md-6">
                <div class="form-group mb-2">
                    <p>Aperçu visuel:</p>
                    <div class="square-image overflow-hidden mb-2" style='height:300px;min-width:250px;max-width:300px;background-color:white'>
                        <img class="img-thumbnail" id='itemImagePreview' src="<?php echo $parameters["itemImage"]; ?>">
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label for="inputItemImage" class="p-2">Télécharger une image:</label>
                    <input type='file' id='inputItemImage' required>
                </div>
            </div>
        </div>


        <input type="hidden" id="inputCategoryId" name="inputCategoryId" value="<?php echo $parameters['categoryId']; ?>">
        <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">

        <div class='col-auto p-4 text-center'>
            <button type='button' id="<?php echo $parameters['actionSubmit']; ?>" class='btn btn-<?php echo $parameters['buttonStyle']; ?>'><?php echo $parameters['libAction']; ?></button>
        </div>
    </form>
</div>