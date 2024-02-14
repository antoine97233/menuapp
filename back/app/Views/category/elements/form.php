<div class='container mt-4 p-4 border col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: rgba(86,61,124,.1)'>
    <div class='container mb-4'>
        <h4 class='text-center'><span id='actionCategory'>Ajouter</span> une catégorie</h4>
    </div>
    <form class='form-inline flex flex-column text-center' id='formCategory'>

        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='categoryTitle' name='categoryTitle' placeholder='Entrées, boissons, plat du jour...' style='text-align:center' required>
        </div>

        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='categoryDescription' name='categoryDescription' placeholder='Description' style='text-align:center' required>
        </div>

        <div class='mb-3 p-2 d-flex justify-content-center row'>
            <label for='categoryRank' class='form-label'>Ordre d'apparition</label>
            <div class='col-sx-4 col-sm-2 col-lg-2'>
                <input type='number' class='form-control border text-center' id='categoryRank' name='categoryRank' style='text-align:center' value="<?php echo $categoryMaxRank; ?>" readonly required>
            </div>
        </div>

        <input type="hidden" id="groupId" name="groupId" value="<?php echo $_GET["groupId"]; ?>">
        <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">


        <div class='form-group p-2'>
            <button type='button' class='btn btn-success m-auto shadow' id='addCategory'>Ajouter</button>
            <button type='button' class='btn btn-warning m-auto shadow' id='updateCategory' style='display: none;'>Modifier</button>
        </div>
    </form>
</div>