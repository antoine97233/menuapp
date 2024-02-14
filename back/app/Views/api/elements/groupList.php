<div class='container mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 rounded shadow-sm'>


    <?php if (isset($dataGroup) && !empty($dataGroup)) : ?>
        <!-- Affichage des données d'un groupe de produit en particulier -->

        <div class="d-flex container align-items-center p-2 mb-4">
            <div>
                <p class="pb-2 mb-0">
                    <!-- Lien vers le endpoint d'un groupe de produit  -->
                    <a id="groupApiLink" class="link-opacity-100" href="index.php?controller=api&action=getGroup&id=<?php echo $dataGroup[0]["group"]["groupId"]; ?>" style="color: #007bff; padding: 8px; border-radius: 4px;">
                        <span class="p-2"><i class="fa-solid fa-link"></i></span>Lien vers l'API - <strong><?php echo $dataGroup[0]["group"]["groupTitle"]; ?></strong> - format JSON
                    </a>

                </p>
                <p class="pb-2 mb-0">
                    <a id="groupfeedExport" class="link-opacity-100" href="index.php?controller=api&action=exportGroup&id=<?php echo $dataGroup[0]["group"]["groupId"]; ?>" style="color: #007bff; padding: 8px; border-radius: 4px;">
                        <span class="p-2"><i class="fa-solid fa-file-export"></i></span>Export - <?php echo $dataGroup[0]["group"]["groupTitle"]; ?> - format CSV
                    </a>

                </p>
            </div>
        </div>

        <!-- Affichage si données vides -->
        <div class="container p-2 border overflow-auto">
            <pre><?php echo json_encode($dataGroup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
        </div>

    <?php elseif (isset($dataAllGroups) && !empty($dataAllGroups)) : ?>
        <!-- Affichage des données de l'ensemble des groupes de produit -->

        <div class="d-flex container align-items-center p-2 mb-4">
            <div>
                <p class="pb-2 mb-0">
                    <a id="allGroupsApiLink" class="link-opacity-100" href="index.php?controller=api&action=getAllGroups" style="color: #007bff; padding: 8px; border-radius: 4px;">
                        <span class="p-2"><i class="fa-solid fa-link"></i></span>Lien vers l'API - Tous les produits - format JSON
                    </a>

                </p>
            </div>
        </div>

        <div class="container p-2 border overflow-auto">
            <pre><?php echo json_encode($dataAllGroups, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
        </div>

    <?php elseif (((isset($dataGroup) && empty($dataGroup)) || (isset($dataAllGroups))  && empty($dataAllGroups))) : ?>
        <!-- Affichage si données vides -->

        <div class="container p-2 border">
            <?php echo "no data"; ?>
        </div>

    <?php endif; ?>

</div>