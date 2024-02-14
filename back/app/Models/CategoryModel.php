<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Category;
use App\Helpers\MessageHelpers;
use Exception;

class CategoryModel extends DbConnect
{
    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_category_list";
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
     * Récupère toutes les catégories depuis la base de données.
     *
     * @return array Un tableau associatif contenant toutes les catégories.
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
     * Récupère toutes les catégories d'un groupe spécifique depuis la base de données.
     *
     * @param int $id L'identifiant du groupe.
     *
     * @return array|false Un tableau associatif contenant les catégories du groupe.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function find(int $id): array
    {
        $sql = "SELECT * FROM $this->table WHERE groupId=:groupId ORDER BY categoryRank ASC";
        $request = $this->connection->prepare($sql);
        $request->bindValue("groupId", $id);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }



    /**
     * Récupère le rang d'une catégorie spécifique depuis la base de données.
     *
     * @param int $id L'identifiant de la catégorie.
     *
     * @return int|null Le rang de la catégorie ou null en cas d'erreur ou si la catégorie n'est pas trouvée.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findRankById(int $id): ?int
    {
        $sql = "SELECT categoryRank FROM $this->table WHERE categoryId=:categoryId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("categoryId", $id);
        $this->executeWithCatch($request);

        $result = $request->fetch(\PDO::FETCH_ASSOC);
        return $result ? (int)$result['categoryRank'] : null;
    }



    /**
     * Ajoute une nouvelle catégorie à la base de données.
     *
     * @param Category $category L'objet Category représentant la nouvelle catégorie.
     *
     * @return int|null L'identifiant de la catégorie ajoutée ou null en cas d'échec.
     * @throws Exception En cas d'échec de l'ajout de la catégorie.
     */
    public function add(Category $category): ?int
    {
        $sql = "INSERT INTO $this->table VALUES (NULL, :categoryTitle, :categoryDescription, :groupId, :categoryRank,:categorySlug)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(":categoryTitle", $category->getCategoryTitle());
        $request->bindValue(":categoryDescription", $category->getCategoryDescription());
        $request->bindValue(":groupId", $category->getGroupId());
        $request->bindValue(":categoryRank", $category->getCategoryRank());
        $request->bindValue(":categorySlug", $category->getCategorySlug());
        $this->executeWithCatch($request);
        $categoryId = $this->connection->lastInsertId();
        return $categoryId;
    }

    /**
     * Trouve le rang maximum des catégories pour un groupe donné.
     *
     * @param mixed $parmGet L'identifiant du groupe pour lequel le rang maximum doit être trouvé.
     *
     * @return array|null Un tableau associatif contenant le rang maximum ou null en cas d'erreur.
     *
     * @throws \Exception Lancée si le paramètre $parmGet est manquant, avec un code d'erreur HTTP 400 Bad Request.
     */
    public function findMaxRank(int $groupId): array
    {
        $sql = "SELECT MAX(categoryRank) AS maxRank FROM $this->table WHERE groupId = $groupId";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }



    /**
     * Modifie une catégorie dans la base de données.
     *
     * @param Category $category L'objet Category représentant la catégorie à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification de la catégorie.
     */
    public function edit(Category $category): void
    {
        $sql = "UPDATE $this->table SET categoryTitle=:categoryTitle, categoryDescription=:categoryDescription, categorySlug=:categorySlug WHERE categoryId=:categoryId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':categoryId', $category->getCategoryId());
        $request->bindValue(':categoryTitle', $category->getCategoryTitle());
        $request->bindValue(':categoryDescription', $category->getCategoryDescription());
        $request->bindValue(':categorySlug', $category->getCategorySlug());
        $this->executeWithCatch($request);
    }


    /**
     * Modifie le rang d'une catégorie dans la base de données.
     *
     * @param Category $category L'objet Category représentant la catégorie à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification du rang de la catégorie.
     */
    public function editRank(Category $category): void
    {
        $sql = "UPDATE $this->table SET categoryRank=:categoryRank WHERE categoryId=:categoryId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':categoryId', $category->getCategoryId());
        $request->bindValue(':categoryRank', $category->getCategoryRank());
        $this->executeWithCatch($request);
    }


    /**
     * Supprime une catégorie de la base de données.
     *
     * @param int $id L'identifiant de la catégorie à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression de la catégorie.
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM $this->table WHERE categoryId = :categoryId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':categoryId', $id);
        $this->executeWithCatch($request);
    }


    /**
     * Décrémente tous les rangs des catégories ayant un rang supérieur à celui spécifié.
     *
     * @param int $deletedRank Le rang à partir duquel les catégories doivent être décrémentées.
     *
     * @return void
     * @throws Exception En cas d'échec de la décrémentation des rangs.
     */
    public function decrementAllRanks($deletedRank): void
    {
        $sql = "UPDATE $this->table SET categoryRank = categoryRank - 1 WHERE categoryRank > :deletedRank";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':deletedRank', $deletedRank);
        $this->executeWithCatch($request);
    }
}
