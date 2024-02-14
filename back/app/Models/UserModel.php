<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Admin;
use App\Helpers\MessageHelpers;
use Exception;

/**
 * Actions sur les utilisateurs (admins) de la BDD.
 */
class UserModel extends DbConnect
{

    /**
     * Constructeur de la classe UserModel.
     */
    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_admin_list";
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
     * Récupère tous les utilisateurs (admins) depuis la base de données.
     *
     * @return array Un tableau associatif contenant tous les utilisateurs.
     * @throws Exception En cas d'échec de la récupération des utilisateurs.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur (admin) spécifique depuis la base de données en utilisant son adresse e-mail.
     *
     * @param Admin $admin L'objet Admin représentant l'utilisateur à rechercher.
     *
     * @return array Un tableau associatif contenant les informations de l'utilisateur ou un tableau vide en cas d'échec.
     * @throws Exception En cas d'échec de la récupération des informations de l'utilisateur.
     */
    public function findByEmail(Admin $admin): array
    {
        $sql = "SELECT * FROM $this->table WHERE adminEmail = :adminEmail";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':adminEmail', $admin->getAdminEmail());
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les adresses e-mail des utilisateurs (admins) depuis la base de données.
     *
     * @return array Un tableau contenant toutes les adresses e-mail des utilisateurs.
     * @throws Exception En cas d'échec de la récupération des adresses e-mail.
     */
    public function findAllEmails(): array
    {
        $sql = "SELECT adminEmail FROM $this->table";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        $results = $request->fetchAll(\PDO::FETCH_COLUMN, 0);
        return $results ? $results : [];
    }

    /**
     * Ajoute un nouvel utilisateur (admin) à la base de données.
     *
     * @param Admin $admin L'objet Admin représentant le nouvel utilisateur.
     *
     * @return void
     * @throws Exception En cas d'échec de l'ajout de l'utilisateur.
     */
    public function add(Admin $admin): void
    {
        $sql = "INSERT INTO $this->table (adminEmail, adminPassword, adminName, adminSuper) VALUES (:adminEmail, :adminPassword, :adminName, :adminSuper)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':adminEmail', $admin->getAdminEmail());
        $request->bindValue(':adminPassword', $admin->getAdminPassword());
        $request->bindValue(':adminName', $admin->getAdminName());
        $request->bindValue(':adminSuper', $admin->isAdminSuper());
        $this->executeWithCatch($request);
    }

    /**
     * Modifie les informations d'un utilisateur (admin) dans la base de données.
     *
     * @param Admin $admin L'objet Admin représentant l'utilisateur à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification des informations de l'utilisateur.
     */
    public function edit(Admin $admin): void
    {
        $sql = "UPDATE $this->table SET adminEmail = :adminEmail, adminName = :adminName WHERE adminId = :adminId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':adminEmail', $admin->getAdminEmail());
        $request->bindValue(':adminName', $admin->getAdminName());
        $request->bindValue(':adminId', $admin->getAdminId());
        $this->executeWithCatch($request);
    }

    /**
     * Supprime un utilisateur (admin) de la base de données.
     *
     * @param Admin $admin L'objet Admin représentant l'utilisateur à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression de l'utilisateur.
     */
    public function delete(Admin $admin): void
    {
        $sql = "DELETE FROM $this->table WHERE adminId = :adminId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':adminId', $admin->getAdminId());
        $this->executeWithCatch($request);
    }
}
