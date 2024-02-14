<?php

namespace App\Models;

use App\config\DbConnect;
use App\Entities\Slider;
use App\Helpers\MessageHelpers;
use Exception;


class SliderModel extends DbConnect
{


    public function __construct()
    {
        parent::__construct();
        $this->getConnection();
        $this->table = "apm_slider_list";
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
     * Récupère tous les sliders depuis la base de données, triés par ordre de rang.
     *
     * @return array Un tableau associatif contenant tous les sliders.
     * @throws Exception En cas d'échec de la récupération des sliders.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table ORDER BY sliderRank ASC";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un slider spécifique depuis la base de données.
     *
     * @param int $id L'identifiant du slider.
     *
     * @return object|false Un objet représentant le slider ou false en cas d'erreur.
     * @throws Exception En cas d'échec de la récupération du slider.
     */
    public function findById(int $id): object
    {
        $sql = "SELECT * FROM $this->table WHERE sliderId=:sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("sliderId", $id);
        $this->executeWithCatch($request);
        return $request->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Récupère le rang d'un slider spécifique depuis la base de données.
     *
     * @param int $id L'identifiant du slider.
     *
     * @return int|null Le rang du slider ou null en cas d'erreur ou si le slider n'est pas trouvé.
     * @throws Exception En cas d'échec de la récupération du rang du slider.
     */
    public function findRankById(int $id): ?int
    {
        $sql = "SELECT sliderRank FROM $this->table WHERE sliderId=:sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue("sliderId", $id);
        $this->executeWithCatch($request);

        $result = $request->fetch(\PDO::FETCH_ASSOC);
        return $result ? (int)$result['sliderRank'] : null;
    }

    /**
     * Ajoute un nouveau slider à la base de données.
     *
     * @param Slider $slider L'objet Slider représentant le nouveau slider.
     *
     * @return int|null L'identifiant du slider ajouté ou null en cas d'échec.
     * @throws Exception En cas d'échec de l'ajout du slider.
     */
    public function add(Slider $slider): ?int
    {
        $sql = "INSERT INTO $this->table (sliderName, sliderTitle, sliderDescription, sliderImagePath, sliderRank, sliderSlug) VALUES (:sliderName, :sliderTitle, :sliderDescription, :sliderImagePath, :sliderRank, :sliderSlug)";
        $request = $this->connection->prepare($sql);
        $request->bindValue(":sliderName", $slider->getSliderName());
        $request->bindValue(":sliderTitle", $slider->getSliderTitle());
        $request->bindValue(":sliderDescription", $slider->getSliderDescription());
        $request->bindValue(":sliderImagePath", $slider->getSliderImage());
        $request->bindValue(":sliderRank", $slider->getSliderRank());
        $request->bindValue(":sliderSlug", $slider->getSliderSlug());
        $this->executeWithCatch($request);

        $sliderId = $this->connection->lastInsertId();
        return $sliderId;
    }

    /**
     * Récupère le rang maximum des sliders depuis la base de données.
     *
     * @return array Un tableau associatif contenant le rang maximum ou null en cas d'erreur.
     * @throws Exception En cas d'échec de la récupération du rang maximum.
     */
    public function findMaxRank(): array
    {
        $sql = "SELECT MAX(sliderRank) AS maxRank FROM $this->table";
        $request = $this->connection->prepare($sql);
        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les sliders avec un nom spécifique depuis la base de données.
     *
     * @param string $name Le nom du slider.
     * @param int|null $excludeId L'identifiant de l'élément à exclure de la recherche.
     *
     * @return array Un tableau associatif contenant les sliders correspondants.
     * @throws Exception En cas d'échec de la récupération des éléments
     */
    public function findByName(string $name, ?int $excludeId = null): array
    {
        $sql = "SELECT * FROM $this->table WHERE sliderName = :sliderName";

        if ($excludeId !== null) {
            $sql .= " AND sliderId != :excludeId";
        }

        $request = $this->connection->prepare($sql);
        $request->bindValue(":sliderName", $name);

        if ($excludeId !== null) {
            $request->bindValue(":excludeId", $excludeId);
        }

        $this->executeWithCatch($request);
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Modifie un slider dans la base de données.
     *
     * @param Slider $slider L'objet Slider représentant le slider à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification du slider.
     */
    public function edit(Slider $slider): void
    {
        $sql = "UPDATE $this->table SET sliderName=:sliderName, sliderTitle=:sliderTitle, sliderDescription=:sliderDescription, sliderSlug=:sliderSlug WHERE sliderId=:sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':sliderId', $slider->getSliderId());
        $request->bindValue(':sliderName', $slider->getSliderName());
        $request->bindValue(':sliderTitle', $slider->getSliderTitle());
        $request->bindValue(':sliderDescription', $slider->getSliderDescription());
        $request->bindValue(':sliderSlug', $slider->getSliderSlug());
        $this->executeWithCatch($request);
    }

    /**
     * Modifie le rang d'un slider dans la base de données.
     *
     * @param Slider $slider L'objet Slider représentant le slider à modifier.
     *
     * @return void
     * @throws Exception En cas d'échec de la modification du rang du slider.
     */
    public function editRank(Slider $slider): void
    {
        $sql = "UPDATE $this->table SET sliderRank=:sliderRank WHERE sliderId=:sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':sliderId', $slider->getSliderId());
        $request->bindValue(':sliderRank', $slider->getSliderRank());
        $this->executeWithCatch($request);
    }

    /**
     * Supprime un slider de la base de données.
     *
     * @param int $id L'identifiant du slider à supprimer.
     *
     * @return void
     * @throws Exception En cas d'échec de la suppression du slider.
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM $this->table WHERE sliderId = :sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':sliderId', $id);
        $this->executeWithCatch($request);
    }

    /**
     * Met à jour le chemin de l'image d'un slider dans la base de données.
     *
     * @param int $sliderId L'identifiant du slider.
     * @param string $sliderImage Le nouveau chemin de l'image.
     *
     * @return void
     * @throws Exception En cas d'échec de la mise à jour du chemin de l'image.
     */
    public function updateSliderImagePath(int $sliderId, string $sliderImage): void
    {
        $sql = "UPDATE $this->table SET sliderImagePath=:sliderImagePath WHERE sliderId=:sliderId";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':sliderId', $sliderId);
        $request->bindValue(':sliderImagePath', $sliderImage);
        $this->executeWithCatch($request);
    }

    /**
     * Décrémente le rang de tous les sliders ayant un rang supérieur au rang supprimé.
     *
     * @param int $deletedRank Le rang supprimé.
     *
     * @return void
     * @throws Exception En cas d'échec de la mise à jour des rangs.
     */
    public function decrementAllRanks($deletedRank): void
    {
        $sql = "UPDATE $this->table SET sliderRank = sliderRank - 1 WHERE sliderRank > :deletedRank";
        $request = $this->connection->prepare($sql);
        $request->bindValue(':deletedRank', $deletedRank);
        $this->executeWithCatch($request);
    }
}
