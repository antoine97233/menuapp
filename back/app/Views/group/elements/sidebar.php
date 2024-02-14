<div class='col-md-3 col-lg-2 pt-4 pb-4 border' style="background-color: rgba(86,61,124,.1)">
    <div class="sticky-top">

        <div class="p-2 mb-4">
            <h3>Menu</h3>

        </div>

        <ul class="list-group sticky-top shadow-sm" id='displayBulletPointGroup'>
            <li class='list-group-item'>
                <a class='text-decoration-none text-dark' role='button' id='addGroup' href='index.php?controller=group'>

                    <div class="justify-content-between d-flex">

                        <div>
                            <h5 class="text-secondary">Ajouter</h5>
                        </div>

                        <div>
                            <i class='fa-solid fa-plus align-middle text-secondary'></i>
                        </div>
                    </div>
                </a>
            </li>

            <?php if (isset($groupList)) : ?>
                <!-- Liste des accès aux différents groupes de produit -->

                <?php foreach ($groupList as $row) : ?>
                    <?php $groupId = $row['groupId']; ?>
                    <?php $groupTitle = $row['groupTitle']; ?>

                    <li class='list-group-item' id='bulletedGroupList<?php echo $groupId; ?>'>
                        <a class='text-decoration-none text-dark' href='index.php?controller=category&groupId=<?php echo $groupId; ?>'>

                            <div class="justify-content-between d-flex">
                                <div>
                                    <h5>
                                        <?php echo $groupTitle; ?>
                                    </h5>
                                </div>

                                <div class>
                                    <?php if (isset($_GET["groupId"]) && $groupId == (int)$_GET["groupId"]) : ?>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    <?php else : ?>
                                        <i class="fa-solid fa-chevron-right"></i>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </a>
                        <?php if (isset($_GET["groupId"]) && $groupId == (int)$_GET["groupId"]) : ?>
                            <!-- Liste des accès aux différentes catégories d'un groupe de produit si groupId est renseigné -->

                            <div class='list-group list-group-flush' id='displayBulletPointCategory'>

                                <?php if (isset($categoryList)) : ?>
                                    <?php foreach ($categoryList as $row) : ?>
                                        <a href='#pushCategory<?php echo $row["categoryId"]; ?>' class='list-group-item list-group-item-action text-secondary' id='category<?php echo $row["categoryId"]; ?>'>
                                            <?php echo $row['categoryTitle']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </div>

                        <?php endif; ?>


                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

        </ul>
    </div>
</div>