<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Item;
use App\Helpers\MessageHelpers;
use Exception;

class ItemModel extends DbConnect
{

    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_item_list";
    }

    /**
     * Exécute une requête préparée et capture les exceptions PDO.
     *
     * @param \PDOStatement $request La requête préparée à exécuter.
     *
     * @return void
     * @throws Exception En cas d'échec de l'exécution de la requête.
     */
    private function executeWithCatch(\PDOStatement $request): void
    {
        try {
            $request->execute();
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Récupère tous les items depuis la base de données.
     *
     * @return array Un tableau associatif contenant tous les items.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Récupère tous les items d'une catégorie spécifique depuis la base de données.
     *
     * @param int $id L'identifiant de la catégorie.
     *
     * @return array Un tableau associatif contenant les items de la catégorie.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function find(int $id): array
    {
        $sql = "SELECT * FROM $this->table WHERE categoryId=:categoryId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("categoryId", $id);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Récupère un item spécifique depuis la base de données.
     *
     * @param int $id L'identifiant de l'item.
     *
     * @return object|false Un objet représentant l'item ou false en cas d'erreur.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findById(int $id): object
    {
        $sql = "SELECT * FROM $this->table WHERE itemId=:itemId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("itemId", $id);
        $this->executeWithCatch($request);
        return $request->fetch(\PDO::FETCH_OBJ);
    }



    /**
     * Récupère les items avec un titre spécifique dans une catégorie donnée depuis la base de données.
     *
     * @param string $title Le titre de l'item.
     * @param int $categoryId L'identifiant de la catégorie.
     * @param int|null $excludeId L'identifiant de l'élément à exclure de la recherche.
     *
     * @return array Un tableau associatif contenant les items correspondants.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findByTitleAndCategory(string $title, int $categoryId, ?int $excludeId = null): array
    {
        $sql = "SELECT * FROM $this->table WHERE itemTitle = :title AND categoryId = :categoryId";

        if ($excludeId !== null) {
            $sql .= " AND itemId != :excludeId";
        }

        $request = $this->connection->prepare($sql);
        $request->bindValue(":title", $title);
        $request->bindValue(":categoryId", $categoryId);

        if ($excludeId !== null) {
            $request->bindValue(":excludeId", $excludeId);
        }

        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }




    /**
     * Ajoute un nouvel item à la base de données.
     *
     * @param Item $item L'objet Item représentant le nouvel item.
     *
     * @return int|null L'identifiant de l'item ajouté ou null en cas d'échec.
     * @throws Exception En cas d'échec de l'ajout de l'item.
     */
    public function add(Item $item): ?int
    {
        $sql = "INSERT INTO $this->table (itemTitle, itemDescription, itemPrice, itemStock, categoryId, itemImagePath, itemSlug) VALUES (:itemTitle, :itemDescription, :itemPrice, :itemStock, :categoryId, :itemImagePath, :itemSlug)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':itemTitle', $item->getItemTitle());
        $request->bindValue(':itemDescription', $item->getItemDescription());
        $request->bindValue(':itemPrice', $item->getItemPrice());
        $request->bindValue(':itemStock', $item->getItemStock());
        $request->bindValue(':categoryId', $item->getCategoryId());
        $request->bindValue(':itemImagePath', $item->getItemImagePath());
        $request->bindValue(':itemSlug', $item->getItemSlug());
        $this->executeWithCatch($request);

        $itemId = $this->connection->lastInsertId();
        return $itemId;
    }

    /**
     * Supprime un item de la base de données.
     *
     * @param int $id L'identifiant de l'item à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression de l'item.
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM $this->table WHERE itemId = :itemId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':itemId', $id);
        $this->executeWithCatch($request);
    }


    /**
     * Modifie un item dans la base de données.
     *
     * @param Item $item L'objet Item représentant l'item à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification de l'item.
     */
    public function edit(Item $item): void
    {
        $sql = "UPDATE $this->table SET itemTitle=:itemTitle, itemDescription=:itemDescription, itemPrice=:itemPrice, itemStock=:itemStock, itemImagePath=:itemImagePath, itemSlug=:itemSlug WHERE itemId=:itemId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':itemId', $item->getItemId());
        $request->bindValue(':itemTitle', $item->getItemTitle());
        $request->bindValue(':itemDescription', $item->getItemDescription());
        $request->bindValue(':itemPrice', $item->getItemPrice());
        $request->bindValue(':itemStock', $item->getItemStock());
        $request->bindValue(':itemImagePath', $item->getItemImagePath());
        $request->bindValue(':itemSlug', $item->getItemSlug());
        $this->executeWithCatch($request);
    }


    /**
     * Modifie un item dans la base de données sans changer l'image.
     *
     * @param Item $item L'objet Item représentant l'item à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification de l'item.
     */
    public function editNoImage(Item $item): void
    {
        $sql = "UPDATE $this->table SET itemTitle=:itemTitle, itemDescription=:itemDescription, itemPrice=:itemPrice, itemStock=:itemStock, itemSlug=:itemSlug WHERE itemId=:itemId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':itemId', $item->getItemId());
        $request->bindValue(':itemTitle', $item->getItemTitle());
        $request->bindValue(':itemDescription', $item->getItemDescription());
        $request->bindValue(':itemPrice', $item->getItemPrice());
        $request->bindValue(':itemStock', $item->getItemStock());
        $request->bindValue(':itemSlug', $item->getItemSlug());
        $this->executeWithCatch($request);
    }

    /**
     * Met à jour le chemin de l'image d'un item dans la base de données.
     *
     * @param int $itemId L'identifiant de l'item.
     * @param string $newImagePath Le nouveau chemin de l'image.
     *
     * @return void
     * @throws Exception En cas d'échec de la mise à jour du chemin de l'image.
     */
    public function updateItemImagePath(int $itemId, string $newImagePath): void
    {
        $sql = "UPDATE $this->table SET itemImagePath=:itemImagePath WHERE itemId=:itemId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':itemId', $itemId);
        $request->bindValue(':itemImagePath', $newImagePath);
        $this->executeWithCatch($request);
    }
}
