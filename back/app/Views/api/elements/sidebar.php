<div class='col-md-3 col-lg-2 pt-4 pb-4 border' style="background-color: rgba(86,61,124,.1)">
    <div class="sticky-top">

        <div class="p-2 mb-4">
            <h3>API</h3>

        </div>

        <ul class="list-group sticky-top shadow-sm" id='displayBulletPointGroup'>


            <li class='list-group-item'>

                <div class="justify-content-between d-flex">
                    <div>
                        <a class='text-decoration-none text-dark' href='index.php?controller=api&action=group'>
                            <h5>
                                Produits
                            </h5>
                        </a>
                    </div>



                </div>
                <?php if (isset($groupList) && !empty($groupList)) : ?>
                    <!-- Liste des accès aux différents endpoints par groupe de Produits -->
                    <?php foreach ($groupList as $row) : ?>
                        <?php $groupId = $row['groupId']; ?>
                        <?php $groupTitle = $row['groupTitle']; ?>
                        <div class='list-group list-group-flush' id='displayBulletPointGroup'>

                            <a href='index.php?controller=api&action=group&id=<?php echo $row['groupId']; ?>' class='list-group-item list-group-item-action text-secondary'>
                                <?php echo $row['groupTitle']; ?>
                            </a>


                        </div>

                    <?php endforeach; ?>
                <?php else : ?>

                <?php endif; ?>
            </li>


            <li class='list-group-item'>

                <div class="justify-content-between d-flex">
                    <div>
                        <a class='text-decoration-none text-dark' href='index.php?controller=api&action=slider'>

                            <h5>
                                Sliders
                            </h5>

                        </a>

                    </div>



                </div>
            </li>

            <li class='list-group-item'>

                <div class="justify-content-between d-flex">
                    <div>
                        <a class='text-decoration-none text-dark' href='index.php?controller=api&action=menu'>

                            <h5>
                                Menu PDF
                            </h5>

                        </a>

                    </div>



                </div>
            </li>







        </ul>
    </div>
</div>