<?php

namespace App\Services;

use App\Core\Database;

class PartageService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Partager une publication
     */
    public function partager(int $communauteId, int $publicationId, int $utilisateurId): array
    {
        // Vérifier que la publication existe et appartient à la communauté
        $stmt = $this->db->prepare(
            'SELECT id FROM publications WHERE id = :pid AND communaute_id = :cid AND statut = :statut'
        );
        $stmt->execute(['pid' => $publicationId, 'cid' => $communauteId, 'statut' => 'active']);

        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Publication introuvable.']];
        }

        // Vérifier si déjà partagé
        $stmt = $this->db->prepare(
            'SELECT id FROM partages_publications WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);

        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Vous avez déjà partagé cette publication.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO partages_publications (communaute_id, publication_id, utilisateur_id, date_creation)
             VALUES (:cid, :pid, :uid, NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'pid' => $publicationId,
            'uid' => $utilisateurId,
        ]);

        return ['success' => true, 'nb_partages' => $this->compterPartages($communauteId, $publicationId)];
    }

    /**
     * Annuler un partage
     */
    public function annulerPartage(int $communauteId, int $publicationId, int $utilisateurId): array
    {
        $stmt = $this->db->prepare(
            'DELETE FROM partages_publications WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);

        return ['success' => true, 'nb_partages' => $this->compterPartages($communauteId, $publicationId)];
    }

    /**
     * Compter les partages d'une publication
     */
    public function compterPartages(int $communauteId, int $publicationId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM partages_publications WHERE communaute_id = :cid AND publication_id = :pid'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId]);
        return (int) $stmt->fetch()['c'];
    }

    /**
     * Vérifier si une publication est partagée par un utilisateur
     */
    public function estPartage(int $communauteId, int $publicationId, int $utilisateurId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM partages_publications WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);
        return (bool) $stmt->fetch();
    }
}
