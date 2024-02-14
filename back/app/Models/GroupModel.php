<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Group;
use App\Helpers\MessageHelpers;
use Exception;

class GroupModel extends DbConnect
{

    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_group_list";
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
     * Récupère tous les groupes depuis la base de données.
     *
     * @return array Un tableau associatif contenant tous les groupes.
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
     * Récupère un groupe spécifique depuis la base de données.
     *
     * @param int $id L'identifiant du groupe à récupérer.
     *
     * @return array|false Un tableau associatif représentant le groupe ou false en cas d'erreur.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function find(int $id): array
    {
        $sql = "SELECT * FROM $this->table WHERE groupId=:groupId";
        $request = $this->connection->prepare($sql);
        $request->bindParam("groupId", $id);
        $this->executeWithCatch($request);
        return $request->fetch();
    }

    /**
     * Ajoute un nouveau groupe à la base de données.
     *
     * @param Group $group L'objet Group représentant le nouveau groupe.
     *
     * @return int|null L'identifiant du groupe ajouté ou null en cas d'échec.
     * @throws Exception En cas d'échec de l'ajout du groupe.
     */
    public function add(Group $group): ?int
    {
        $sql = "INSERT INTO $this->table VALUES (NULL, :groupTitle, :groupDescription, :groupSlug)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(":groupTitle", $group->getGroupTitle());
        $request->bindValue(":groupDescription", $group->getGroupDescription());
        $request->bindValue(":groupSlug", $group->getGroupSlug());
        $this->executeWithCatch($request);
        $groupId = $this->connection->lastInsertId();
        return $groupId;
    }

    /**
     * Modifie un groupe dans la base de données.
     *
     * @param Group $group L'objet Group représentant le groupe à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification du groupe.
     */
    public function edit(Group $group): void
    {
        $sql = "UPDATE $this->table SET groupTitle=:groupTitle, groupDescription=:groupDescription, groupSlug=:groupSlug WHERE groupId=:groupId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':groupId', $group->getGroupId());
        $request->bindValue(':groupTitle', $group->getGroupTitle());
        $request->bindValue(':groupDescription', $group->getGroupDescription());
        $request->bindValue(':groupSlug', $group->getGroupSlug());
        $this->executeWithCatch($request);
    }

    /**
     * Supprime un groupe de la base de données.
     *
     * @param int $id L'identifiant du groupe à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression du groupe.
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM $this->table WHERE groupId = :groupId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':groupId', $id);
        $this->executeWithCatch($request);
    }

    /**
     * Récupère tous les groupes en ligne depuis la base de données.
     *
     * @return array Un tableau associatif contenant les groupes en ligne.
     * @throws Exception En cas d'échec de la récupération des groupes en ligne.
     */
    public function findOnlineGroups(): array
    {
        $sql = "SELECT * FROM $this->table WHERE groupIsOnline = 1";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }
}
