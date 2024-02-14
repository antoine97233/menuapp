<div id='displayGroup'>


    <?php if (isset($groupList)) : ?>

        <?php foreach ($groupList as $row) : ?>
            <?php $groupId = $row['groupId']; ?>
            <?php $groupTitle = $row['groupTitle']; ?>
            <?php $groupDescription = $row['groupDescription']; ?>

            <div class="container justify-content-between d-flex flex-wrap flex-column mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm" style='background-color: #e3f2fd;' id='pushGroup<?php echo $groupId; ?>'>
                <div>
                    <h4 class="text-center" id='title<?php echo $groupId; ?>'><?php echo $groupTitle; ?></h4>
                </div>
                <div>
                    <p class="text-center" id='desc<?php echo $groupId; ?>'><?php echo $groupDescription; ?></p>
                </div>

                <!-- Boutons de gestion d'un groupe de produits -->
                <div class="d-flex justify-content-center">
                    <button type='button' data-toggle='tooltip' title='Renommer' data-placement='top' class='m-2 btn btn-primary btn-sm editGroup shadow-sm' data-id='<?php echo $groupId; ?>'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button>
                    <a class='m-2 btn btn-warning btn-sm shadow-sm' role='button' data-toggle='tooltip' title='consulter' data-placement='top' id='redirect<?php echo $groupId; ?>' data-id='<?php echo $groupId; ?>' href='index.php?controller=category&groupId=<?php echo $groupId; ?>'><i class='fa-solid fa-eye' style='color:white'></i></a>
                    <button type='button' class='m-2 btn btn-info btn-sm importGroup shadow-sm' data-toggle='tooltip' title='Importer' data-placement='top' data-id='<?php echo $groupId; ?>'><i class="fa-solid fa-upload" style="color:white"></i></button>
                    <button type='button' class='m-2 btn btn-danger btn-sm deleteGroup shadow-sm' data-toggle='tooltip' title='Supprimer' data-placement='top' data-id='<?php echo $groupId; ?>'><i class='fa-solid fa-trash' style='color:white'></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>