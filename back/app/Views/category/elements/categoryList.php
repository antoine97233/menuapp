<?php

use App\Models\ItemModel;

?>
<div class="pb-4" id='displayCategory'>

    <?php if (isset($categoryList)) : ?>
        <?php foreach ($categoryList as $row) : ?>
            <?php
            $categoryId = $row['categoryId'];
            $categoryTitle = $row['categoryTitle'];
            $categoryDescription = $row['categoryDescription'];
            $categoryRank = $row['categoryRank'];
            $groupId = $row['groupId'];
            ?>
            <div class='container mb-4 mt-4 pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 rounded shadow-sm' style='background-color: #e3f2fd;' id='pushCategory<?php echo $categoryId; ?>' data-id='<?php echo $categoryId; ?>'>
                <div class='container mb-4'>
                    <h4 class='text-center' id='title<?php echo $categoryId; ?>'><?php echo $categoryTitle; ?></h4>
                </div>
                <div class='container mb-4'>
                    <p class='text-center' id='desc<?php echo $categoryId; ?>'><?php echo $categoryDescription; ?></p>
                </div>
                <div class='table-responsive'>
                    <table class='table  border' style='background-color: white;'>
                        <thead>
                            <tr class='d-flex pl-2 pr-2'>
                                <th class='col-5'>Nom</th>
                                <th class='col-3 '>Prix</th>
                                <th class='col-2 '>Stock</th>
                                <th class='col-2 '>
                                    <!-- Bouton de redirection vers le formulaire d'ajout d'un item -->
                                    <a href="index.php?controller=item&action=form&request=add&groupId=<?php echo $groupId; ?>&categoryId=<?php echo $categoryId; ?>#formItem" class='btn btn-sm btn-success' style='width:35px' data-toggle='tooltip' title='Ajouter'>
                                        <i class='fa-solid fa-plus' style='color:white'></i>
                                    </a>
                                </th>

                            </tr>
                        </thead>
                        <tbody id='displayItem<?php echo $categoryId; ?>'>
                            <!-- Liste des items appartenant à une catégorie -->
                            <?php
                            $model = new ItemModel();
                            $itemList = $model->find($categoryId);

                            foreach ($itemList as $row) : ?>
                                <?php
                                $itemId = $row['itemId'];
                                $itemTitle = $row['itemTitle'];
                                $itemDescription = $row['itemDescription'];
                                $itemPrice = $row['itemPrice'];
                                $itemImage = $row['itemImagePath'];
                                $itemStock = $row['itemStock'];
                                $categoryId = $row['categoryId']
                                ?>
                                <tr class='d-flex pl-2 pr-2 ' id='pushItem<?php echo $itemId; ?>'>
                                    <td class='col-5 '><span id='itemTitle<?php echo $itemId; ?>'><?php echo $itemTitle; ?></span></td>
                                    <td class='col-3 '><span id='itemPrice<?php echo $itemId; ?>'><?php echo $itemPrice; ?></span> €</td>
                                    <td class='col-2 '>x<span id='itemStock<?php echo $itemId; ?>'> <?php echo $itemStock; ?></span></td>

                                    <td class='col-2'>
                                        <button type="button" class="btn btn-sm btn-warning seeItem" style='width:35px' data-bs-toggle="modal" data-bs-target="#itemModal<?php echo $itemId; ?>">
                                            <i class='fa-solid fa-eye' style='color:white'></i>
                                        </button>
                                    </td>

                                    <div class="modal fade" id="itemModal<?php echo $itemId; ?>" tabindex="-1" aria-labelledby="itemModal" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $itemTitle; ?></h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="square-image overflow-hidden p-2 mb-2" id='imgPreview' style='height:300px;min-width:250px;max-width:300px;background-color:white'>
                                                        <img class="img-thumbnail" id='itemImagePreview' src="<?php echo $itemImage; ?>">
                                                    </div>
                                                    <div>
                                                        <p><span class="text-secondary">Description : </span></br>
                                                            <?php echo $itemDescription; ?>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p><span class="text-secondary">Prix : </span></br>
                                                            <?php echo $itemPrice; ?> €
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p><span class="text-secondary">Stock : </span></br>
                                                            <?php echo $itemStock; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-primary" data-toggle='tooltip' title='Editer' href="index.php?controller=item&action=form&request=edit&groupId=<?php echo $groupId; ?>&categoryId=<?php echo $categoryId; ?>&itemId=<?php echo $itemId; ?>#formItem" role="button">Modifier</a>
                                                    <button type='submit' class='btn btn-danger deleteItem' data-bs-dismiss="modal" data-toggle='tooltip' title='Supprimer' data-id='<?php echo $itemId; ?>' data-categoryid='<?php echo $categoryId; ?>' id='deleteItem<?php echo $categoryId; ?>'>Supprimer</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Boutons de gestion d'une catégorie -->
                <div class='form-group p-2 d-flex justify-content-between'>
                    <div>
                        <a href="#formCategory"><button type='button' class='btn btn-primary editCategory shadow-sm m-2' style='width:100px;height:40px' data-id='<?php echo $categoryId; ?>'>Modifier</button></a>
                        <button type='button' class='btn btn-danger deleteCategory shadow-sm m-2' style='width:100px;height:40px' data-id='<?php echo $categoryId; ?>'>Supprimer</button>
                    </div>
                    <div class='d-flex m-2'>
                        <div class='input-group' style='width:40px;height:40px'>
                            <input type='text' id='inputRank<?php echo $categoryId; ?>' class='form-control inputRank' value='<?php echo $categoryRank; ?>' readonly>
                        </div>
                        <div class='p-1 '>
                            <button type='button' class='btn btn-sm btn-primary downRank shadow-sm' data-id='<?php echo $categoryId; ?>'><i class='fa-solid fa-arrow-up'></i></button>
                        </div>
                        <div class='p-1'>
                            <button type='button' class='btn btn-sm btn-primary upRank shadow-sm' data-id='<?php echo $categoryId; ?>'><i class='fa-solid fa-arrow-down'></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>