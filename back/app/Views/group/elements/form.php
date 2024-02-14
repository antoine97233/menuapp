<div class='container justify-content-between mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: rgba(86,61,124,.1)'>
    <div class='container mb-4'>
        <h4 class='text-center'><span id='actionGroup'>Ajouter</span> un groupe</h4>
    </div>
    <form class='form-inline flex flex-column text-center' id='formGroup'>
        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='groupTitle' name='groupTitle' placeholder='Le nom de votre groupe' style='text-align:center' required>
        </div>
        <div class='input-group p-2'>
            <input type='text' class='form-control border' id='groupDescription' name='groupDescription' placeholder='Description' style='text-align:center' required>
        </div>


        <div class='form-group p-2'>
            <button type='button' class='btn btn-success m-auto shadow' id='submitGroup'>Ajouter</button>
            <button type='button' class='btn btn-warning m-auto shadow' id='updateGroup' style='display: none;'>Modifier</button>
        </div>

        <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">

    </form>
</div>