<?php

namespace App\Services;

use App\Core\Database;

class ProgressionService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Marquer une leçon comme terminée
     */
    public function marquerTerminee(int $communauteId, int $utilisateurId, int $leconId): array
    {
        // Vérifier que la leçon existe et appartient à la communauté
        $stmt = $this->db->prepare(
            'SELECT l.id, l.formation_id, l.communaute_id FROM lecons l WHERE l.id = :lid AND l.communaute_id = :cid AND l.statut = :statut'
        );
        $stmt->execute(['lid' => $leconId, 'cid' => $communauteId, 'statut' => 'active']);
        $lecon = $stmt->fetch();

        if (!$lecon) {
            return ['success' => false, 'errors' => ['Leçon introuvable.']];
        }

        // Upsert la progression
        $stmt = $this->db->prepare(
            'INSERT INTO progression_formations (communaute_id, utilisateur_id, lecon_id, terminee, date_completion, date_creation)
             VALUES (:cid, :uid, :lid, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE terminee = 1, date_completion = NOW()'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'lid' => $leconId,
        ]);

        return [
            'success' => true,
            'pourcentage' => $this->getPourcentage($communauteId, $utilisateurId, $lecon['formation_id']),
        ];
    }

    /**
     * Annuler la complétion d'une leçon
     */
    public function annulerTerminee(int $communauteId, int $utilisateurId, int $leconId): array
    {
        $stmt = $this->db->prepare(
            'DELETE FROM progression_formations WHERE communaute_id = :cid AND utilisateur_id = :uid AND lecon_id = :lid'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'lid' => $leconId,
        ]);

        // Récupérer la formation_id
        $stmt = $this->db->prepare('SELECT formation_id FROM lecons WHERE id = :lid');
        $stmt->execute(['lid' => $leconId]);
        $lecon = $stmt->fetch();

        return [
            'success' => true,
            'pourcentage' => $lecon ? $this->getPourcentage($communauteId, $utilisateurId, $lecon['formation_id']) : 0,
        ];
    }

    /**
     * Obtenir le pourcentage de complétion d'une formation
     */
    public function getPourcentage(int $communauteId, int $utilisateurId, int $formationId): int
    {
        $total = $this->compterLecons($formationId);
        if ($total === 0) return 0;

        $terminees = $this->compterTerminees($communauteId, $utilisateurId, $formationId);
        return (int) round(($terminees / $total) * 100);
    }

    /**
     * Obtenir les leçons terminées d'un utilisateur pour une formation
     */
    public function getLeconsTerminees(int $communauteId, int $utilisateurId, int $formationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT pf.lecon_id FROM progression_formations pf
             JOIN lecons l ON l.id = pf.lecon_id
             WHERE pf.communaute_id = :cid AND pf.utilisateur_id = :uid AND l.formation_id = :fid AND pf.terminee = 1'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId, 'fid' => $formationId]);
        return array_column($stmt->fetchAll(), 'lecon_id');
    }

    private function compterLecons(int $formationId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM lecons WHERE formation_id = :fid AND statut = :statut'
        );
        $stmt->execute(['fid' => $formationId, 'statut' => 'active']);
        return (int) $stmt->fetch()['c'];
    }

    private function compterTerminees(int $communauteId, int $utilisateurId, int $formationId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM progression_formations pf
             JOIN lecons l ON l.id = pf.lecon_id
             WHERE pf.communaute_id = :cid AND pf.utilisateur_id = :uid AND l.formation_id = :fid AND pf.terminee = 1'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId, 'fid' => $formationId]);
        return (int) $stmt->fetch()['c'];
    }
}
