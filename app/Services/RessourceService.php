<?php

namespace App\Services;

use App\Core\Database;

class RessourceService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une ressource
     */
    public function creer(int $communauteId, array $data): array
    {
        if (empty($data['titre'])) {
            return ['success' => false, 'errors' => ['Le titre est requis.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO ressources (communaute_id, titre, description, type, chemin, url, nom_fichier, statut, ordre, date_creation, date_modification)
             VALUES (:cid, :titre, :description, :type, :chemin, :url, :nom_fichier, :statut, :ordre, NOW(), NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'titre' => htmlspecialchars(trim($data['titre'])),
            'description' => htmlspecialchars(trim($data['description'] ?? '')),
            'type' => $data['type'] ?? 'fichier',
            'chemin' => $data['chemin'] ?? null,
            'url' => $data['url'] ?? null,
            'nom_fichier' => $data['nom_fichier'] ?? null,
            'statut' => 'active',
            'ordre' => $data['ordre'] ?? 0,
        ]);

        return ['success' => true, 'ressource_id' => $this->db->lastInsertId()];
    }

    /**
     * Lister les ressources d'une communauté
     */
    public function lister(int $communauteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM ressources WHERE communaute_id = :cid AND statut = :statut ORDER BY ordre ASC, date_creation DESC'
        );
        $stmt->execute(['cid' => $communauteId, 'statut' => 'active']);
        return $stmt->fetchAll();
    }

    /**
     * Supprimer une ressource
     */
    public function supprimer(int $communauteId, int $ressourceId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE ressources SET statut = :statut WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['statut' => 'supprimee', 'id' => $ressourceId, 'cid' => $communauteId]);
        return $stmt->rowCount() > 0;
    }
}
