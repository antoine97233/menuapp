<?php if (isset($error)) : ?>
    <?php echo $error; ?>


<?php else : ?>
    <div id='containerUser' class='col-md-12 pt-4 pb-4 border'>
        <div class='container ml-4 mr-4 mt-4 mb-4 pb-2 pt-2'>
            <h1 class='text-center'>Menu PDF</h1>
        </div>

        <div class='container mt-4 pt-2 pb-2 col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6'>
            <div class='col-auto p-4 text-center'>
                <?php if (isset($menuList) && !$menuList) : ?>
                    <a href='index.php?controller=menu&action=form&request=add' type='button' class='btn btn-success mb-4 text-center shadow-sm'>Ajouter</a>
                <?php else : ?>
                <?php endif; ?>
            </div>

            <!-- Liste des menu PDF enregistrés -->
            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Fichier</th>
                            <th></th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($menuList)) : ?>

                            <?php foreach ($menuList as $row) : ?>
                                <?php
                                $menuId = $row['menuId'];
                                $menuTitle = $row['menuTitle'];
                                $menuPath = $row['menuPath'];


                                $deleteUrl = "index.php?controller=menu&action=form&request=delete"
                                    . "&menuId=" . urlencode($menuId)
                                    . "&menuTitle=" . urlencode($menuTitle)
                                    . "&menuPath=" . urlencode($menuPath);


                                ?>
                                <tr id='menuId<?php echo $menuId; ?>'>
                                    <td><span id='menuTitle<?php echo $menuId; ?>'><?php echo $menuTitle; ?></span></td>
                                    <td><span id='menuPath<?php echo $menuId; ?>'><?php echo $menuPath; ?></span></td>

                                    <td>
                                        <a href='<?php echo $menuPath; ?>' target="_blank" type='button' class='btn btn-sm btn-warning  shadow-sm'>
                                            <i class='fa-solid fa-eye' style='color:white'></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href='<?php echo $deleteUrl; ?>' type='button' class='btn btn-sm btn-danger shadow-sm'>
                                            <i class='fa-solid fa-trash' style='color:white'></i>
                                        </a>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>