<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Menu;
use Exception;


class MenuModel extends DbConnect
{


    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_menu_list";
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
     * Récupère un menu spécifique depuis la base de données.
     *
     * @param int $id L'identifiant du menu.
     *
     * @return object|false Un objet représentant le menu ou false en cas d'erreur.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findById(int $id): object
    {
        $sql = "SELECT * FROM $this->table WHERE menuId=:menuId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("menuId", $id);
        $this->executeWithCatch($request);
        return $request->fetch(\PDO::FETCH_OBJ);
    }



    /**
     * Récupère tous les menus depuis la base de données.
     *
     * @return array Un tableau associatif contenant tous les sliders.
     * @throws Exception En cas d'échec de la récupération des sliders.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }



    /**
     * Ajoute un nouveau menu à la base de données.
     *
     * @param Menu $item L'objet Menu représentant le nouveau menu.
     *
     * @return int|null L'identifiant deu menu ajouté ou null en cas d'échec.
     * @throws Exception En cas d'échec de l'ajout du menu.
     */
    public function add(Menu $menu): ?int
    {
        $sql = "INSERT INTO $this->table (menuTitle, menuPath) VALUES (:menuTitle, :menuPath)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':menuTitle', $menu->getMenuTitle());
        $request->bindValue(':menuPath', $menu->getMenuPath());
        $this->executeWithCatch($request);

        $menuId = $this->connection->lastInsertId();
        return $menuId;
    }



    /**
     * Supprime un menu de la base de données.
     *
     * @param int $id L'identifiant du menu à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression du menu.
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM $this->table WHERE menuId = :menuId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':menuId', $id);
        $this->executeWithCatch($request);
    }
}
