<?php

namespace App\Views\Category;

class Render
{


    /**
     * Génère un élément de la liste des catégories.
     *
     * @param string $categoryId   L'identifiant de la catégorie.
     * @param string $categoryTitle Le titre de la catégorie.
     *
     * @return string Le code HTML de l'élément de la liste des catégories.
     */
    public static function categoryList(string $categoryId, string $categoryTitle): string
    {
        return "<a href='#pushCategory{$categoryId}' class='list-group-item list-group-item-action text-secondary' id='category{$categoryId}'>{$categoryTitle}</a>";
    }



    /**
     * Génère le bloc d'affichage complet d'une catégorie.
     *
     * @param string $categoryId         L'identifiant de la catégorie.
     * @param string $categoryTitle       Le titre de la catégorie.
     * @param string $categoryDescription La description de la catégorie.
     * @param string $categoryRank        Le rang de la catégorie.
     *
     * @return string Le code HTML du bloc d'affichage de la catégorie.
     */
    public static function categoryBlock(string $groupId, string $categoryId, string $categoryTitle, string $categoryDescription, string $categoryRank): string
    {
        $category = "";
        $category .= "
    <div class='container mb-4 mt-4 pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 rounded shadow-sm' style='background-color: #e3f2fd;' id='pushCategory{$categoryId}' data-id='{$categoryId}'>
       <div class='container mb-4'>
           <h4 class='text-center' id='title{$categoryId}'>{$categoryTitle}</h4>
       </div>
       <div class='container mb-4'>
           <p class='text-center' id='desc{$categoryId}'>{$categoryDescription}</p>
       </div>
       <div class='table-responsive'>
           <table class='table table-borderless border' style='background-color: white;'>
               <thead class>
                   <tr class='d-flex pl-2 pr-2'>
                       <th class='col-5'>Nom</th>
                       <th class='col-3'>Prix</th>
                       <th class='col-2'>Stock</th>
                       <th class='col-2'>
                           <a href='index.php?controller=item&action=form&request=add&groupId={$groupId}&categoryId={$categoryId}#formItem' class='btn btn-sm btn-success' style='width:35px' data-toggle='tooltip' title='Ajouter'>
                               <i class='fa-solid fa-plus' style='color:white'></i>
                           </a>
                       </th>
                   </tr>
               </thead>
               <tbody id='displayItem{$categoryId}'>
               </tbody>
           </table>
        </div>
      </div>
      <div class='form-group p-2 d-flex justify-content-between'>
          <div>
             <a href='#formCategory'><button type='button' class='btn btn-primary editCategory shadow-sm m-2' style='width:100px;height:40px' data-id='{$categoryId}'>Modifier</button></a>
             <button type='button' class='btn btn-danger deleteCategory shadow-sm m-2' style='width:100px;height:40px' data-id='{$categoryId}'>Supprimer</button>
          </div>
          <div class='d-flex m-2'>
             <div class='input-group' style='width:40px;height:40px'>
                <input type='text' id='inputRank{$categoryId}' class='form-control inputRank' value='{$categoryRank}' readonly>
             </div>
             <div class='p-1 '>
                <button type='button' class='btn btn-sm btn-primary downRank shadow-sm' data-id='{$categoryId}'><i class='fa-solid fa-arrow-up'></i></button>
             </div>
             <div class='p-1'>
                <button type='button' class='btn btn-sm btn-primary upRank shadow-sm' data-id='{$categoryId}'><i class='fa-solid fa-arrow-down'></i></button>
             </div>   
          </div>
       </div>
 </div>";

        return $category;
    }
}
