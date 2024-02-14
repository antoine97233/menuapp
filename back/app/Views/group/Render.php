<?php

namespace App\Views\Group;

class Render
{

    /**
     * Génère le bloc d'affichage complet d'un groupe.
     *
     * @param string $groupId         L'identifiant du groupe.
     * @param string $groupTitle      Le titre du groupe.
     * @param string $groupDescription La description du groupe.
     *
     * @return string Le code HTML du bloc d'affichage du groupe.
     */
    public static function groupBlock(string $groupId, string $groupTitle, string $groupDescription): string
    {
        return "
    <div class='container justify-content-between d-flex flex-wrap flex-column mb-4 mt-2 p-4 border col-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded shadow-sm' style='background-color: #e3f2fd;' id='pushGroup{$groupId}'>
        <div>
            <h4 class='text-center' id='title{$groupId}'>{$groupTitle}</h4>
        </div>
        <div>
            <p class='text-center' id='desc{$groupId}'>{$groupDescription}</p>
        </div>
        <div class='d-flex justify-content-center'>
            <button type='button' data-toggle='tooltip' title='Renommer' data-placement='top' class='m-2 btn btn-primary btn-sm editGroup shadow-sm' data-id='{$groupId}'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button>
            <a class='m-2 btn btn-warning btn-sm shadow-sm' role='button' data-toggle='tooltip' title='Editer' data-placement='top' id='redirect{$groupId}' data-id='{$groupId}' href='index.php?controller=category&groupId={$groupId}'><i class='fa-solid fa-eye' style='color:white'></i></a>
            <button type='button' class='m-2 btn btn-info btn-sm exportGroup shadow-sm' data-toggle='tooltip' title='Exporter' data-placement='top' data-id='{$groupId}' ><i class='fa-solid fa-file-export' style='color:white'></i></i></button>
            <button type='button' class='m-2 btn btn-danger btn-sm deleteGroup shadow-sm' data-toggle='tooltip' title='Supprimer' data-placement='top' data-id='{$groupId}' ><i class='fa-solid fa-trash' style='color:white'></i></button>
        </div>
    </div>";
    }

    /**
     * Génère un élément de la liste des groupes.
     *
     * @param string $groupId    L'identifiant du groupe.
     * @param string $groupTitle Le titre du groupe.
     *
     * @return string Le code HTML de l'élément de la liste des groupes.
     */
    public static function groupList(string $groupId, string $groupTitle): string
    {
        return "<li class='list-group-item' id='bulletedGroupList{$groupId}'> 
            <a class='text-decoration-none text-dark ' href='index.php?controller=category&groupId={$groupId}'>
                <div class='justify-content-between d-flex'>
                    <div>
                        <h5>{$groupTitle}</h5>
                    </div>
    
                    <div>
                        <i class='fa-solid fa-chevron-right'></i>
                    </div>
                </div>
            </a>
        </li>";
    }

    /**
     * Génère une nouvelle URL pour accéder à la catégorie avec le titre du groupe et l'identifiant du groupe.
     *
     * @param string $groupTitle Le titre du groupe.
     * @param string $groupId    L'identifiant du groupe.
     *
     * @return string La nouvelle URL générée.
     */
    public static function newUrl(string $groupId): string
    {
        return "index.php?controller=category&groupId={$groupId}";
    }
}
