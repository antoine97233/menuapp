<?php if (isset($error)) : ?>
    <!-- Afficher le message d'erreur -->
    <?php echo $error; ?>


<?php else : ?>
    <div id='containerUser' class='col-md-12 pt-4 pb-4 border'>
        <div class='container ml-4 mr-4 mt-4 mb-4 pb-2 pt-2'>
            <h1 class='text-center'>Administrateurs</h1>
        </div>

        <div class='container mt-4 pt-2 pb-2 col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6'>
            <div class='col-auto p-4 text-center'>
                <a href='index.php?controller=user&action=form&request=add' type='button' class='btn btn-success mb-4 text-center shadow-sm'>Ajouter un compte</a>
            </div>

            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Compte</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($userList)) : ?>

                            <?php foreach ($userList as $row) : ?>
                                <?php
                                $adminId = $row['adminId'];
                                $adminEmail = $row['adminEmail'];
                                $adminName = $row['adminName'];
                                $adminSuper = $row['adminSuper'];

                                $adminStatus = ($adminSuper == 1) ? "SuperAdmin" : "Admin";

                                $deleteUrl = "index.php?controller=user&action=form&request=delete"
                                    . "&adminId=" . urlencode($adminId)
                                    . "&adminName=" . urlencode($adminName)
                                    . "&adminEmail=" . urlencode($adminEmail);
                                ?>
                                <tr id='adminId<?php echo $adminId; ?>'>
                                    <td><span><?php echo $adminId; ?></span></td>
                                    <td><span id='adminName<?php echo $adminId; ?>'><?php echo $adminName; ?></span></td>
                                    <td><span id='adminEmail<?php echo $adminId; ?>'><?php echo $adminEmail; ?></span></td>
                                    <td><span id='adminSuper<?php echo $adminId; ?>'><?php echo $adminStatus; ?></span></td>
                                    <td>
                                        <a href='<?php echo $deleteUrl; ?>' type='button' class='btn btn-sm btn-danger deleteAdmin shadow-sm'>
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