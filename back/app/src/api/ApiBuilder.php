<?php

namespace App\src\api;

use App\Models\CategoryModel;
use App\Models\GroupModel;
use App\Models\ItemModel;
use Exception;

class ApiBuilder
{

    private $baseUrl;


    public function __construct()
    {
        $this->baseUrl = getenv('BASE_URL');
    }


    /**
     * Construit les données des sliders.
     *
     * @param array $sliderList Liste des sliders.
     * @return array Données des sliders.
     */
    public function buildSliderData($sliderList): array
    {
        $data = [];

        foreach ($sliderList as $slider) {
            $sliderData = [
                'sliderId' => $slider['sliderId'],
                'sliderName' => html_entity_decode($slider['sliderName']),
                'sliderTitle' => html_entity_decode($slider['sliderTitle']),
                'sliderDescription' => html_entity_decode($slider['sliderDescription']),
                'sliderImage' => $this->baseUrl . '/back/admin/' . $slider['sliderImagePath'],
                'sliderSlug' => html_entity_decode($slider['sliderSlug']),


            ];
            $data[] = $sliderData;
        }

        return $data;
    }



    //--------------------------------------------------------//

    /**
     * Construit les données d'un groupe spécifique.
     *
     * @param int $groupId Identifiant du groupe.
     * @param GroupModel $groupModel Instance du modèle de groupe.
     * @param CategoryModel $categoryModel Instance du modèle de catégorie.
     * @param ItemModel $itemModel Instance du modèle d'item.
     * @return array Données du groupe.
     */
    public function buildDataByGroup($groupId, GroupModel $groupModel, CategoryModel $categoryModel, ItemModel $itemModel): array
    {

        $groupData = $this->buildOneGroupData($groupId, $groupModel);
        $categoryList = $categoryModel->find($groupId);
        $groupData['categories'] = $this->buildCategoryData($categoryList, $itemModel);

        return [$groupData];
    }



    /**
     * Construit les données d'une seule groupe.
     *
     * @param int $groupId Identifiant du groupe.
     * @param mixed $model Instance du modèle.
     * @return array Données du groupe.
     * @throws Exception Si le groupe n'est pas trouvé.
     */
    private function buildOneGroupData($groupId, $model): array
    {

        $group = $model->find($groupId);

        if (!$group) {
            throw new Exception("Pas de groupe ajouté");
        }

        $groupData = [
            'group' => [
                'groupId' => $group['groupId'],
                'groupTitle' => html_entity_decode($group['groupTitle']),
                'groupDescription' => html_entity_decode($group['groupDescription']),
                'groupSlug' => html_entity_decode($group['groupSlug']),
            ],
            'categories' => [],
        ];

        return $groupData;
    }


    /**
     * Construit les données des catégories.
     *
     * @param array $categoryList Liste des catégories.
     * @param ItemModel $itemModel Instance du modèle d'item.
     * @return array Données des catégories.
     */
    private function buildCategoryData($categoryList, ItemModel $itemModel): array
    {
        $categories = [];

        foreach ($categoryList as $category) {
            $categoryData = [
                'categoryId' => $category['categoryId'],
                'categoryTitle' => html_entity_decode($category['categoryTitle']),
                'categoryDescription' => html_entity_decode($category['categoryDescription']),
                'items' => $this->buildItemData($category['categoryId'], $itemModel),
                'categorySlug' => html_entity_decode($category['categorySlug']),

            ];

            $categories[] = $categoryData;
        }

        return $categories;
    }


    private function buildItemData($categoryId, ItemModel $itemModel): array
    {
        $items = [];
        $itemList = $itemModel->find($categoryId);

        foreach ($itemList as $item) {
            $itemData = [
                'itemId' => $item['itemId'],
                'itemTitle' => html_entity_decode($item['itemTitle']),
                'itemDescription' => html_entity_decode($item['itemDescription']),
                'itemSlug' => html_entity_decode($item['itemSlug']),
                'itemImagePath' =>  $this->baseUrl . '/back/admin/' . $item['itemImagePath'],
            ];

            if (!empty($item['itemPrice'])) {
                $itemData['itemPrice'] = html_entity_decode($item['itemPrice']);
            }

            if (!empty($item['itemStock'])) {
                $itemData['itemStock'] = $item['itemStock'];
            }


            $items[] = $itemData;
        }

        return $items;
    }


    //--------------------------------------------------------//


    /**
     * Construit les données de tous les groupes.
     *
     * @param GroupModel $groupModel Instance du modèle de groupe.
     * @param CategoryModel $categoryModel Instance du modèle de catégorie.
     * @param ItemModel $itemModel Instance du modèle d'item.
     * @return array Données de tous les groupes.
     */
    public function buildAllGroupsData(GroupModel $groupModel, CategoryModel $categoryModel, ItemModel $itemModel): array
    {
        $allGroupsData = [];

        $groupList = $groupModel->findAll();

        foreach ($groupList as $group) {
            $groupData = $this->buildGroupData($group['groupId'], $groupModel, $categoryModel, $itemModel);
            $allGroupsData[] = $groupData;
        }

        return $allGroupsData;
    }


    private function buildGroupData($groupId, GroupModel $groupModel, CategoryModel $categoryModel, ItemModel $itemModel): array
    {
        $groupData = $this->buildOneGroupData($groupId, $groupModel);
        $categoryList = $categoryModel->find($groupId);
        $groupData['categories'] = $this->buildCategoryData($categoryList, $itemModel);

        return $groupData;
    }


    //--------------------------------------------------------//





}
