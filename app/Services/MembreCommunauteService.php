<?php

namespace App\Services;

use App\Core\Database;

class MembreCommunauteService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Rejoindre une communauté
     */
    public function rejoindre(int $communauteId, int $utilisateurId): array
    {
        // Vérifier si déjà membre
        $stmt = $this->db->prepare(
            'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId]);

        if ($existant = $stmt->fetch()) {
            if ($existant['statut'] === 'actif') {
                return ['success' => false, 'errors' => ['Vous êtes déjà membre de cette communauté.']];
            }
            // Réactiver
            $stmt = $this->db->prepare(
                'UPDATE membres_communautes SET statut = :statut, date_modification = NOW() WHERE id = :id'
            );
            $stmt->execute(['statut' => 'actif', 'id' => $existant['id']]);
            return ['success' => true];
        }

        // Vérifier que la communauté est publique
        $stmt = $this->db->prepare('SELECT visibilite FROM communautes WHERE id = :cid');
        $stmt->execute(['cid' => $communauteId]);
        $comm = $stmt->fetch();

        if (!$comm || $comm['visibilite'] !== 'publique') {
            return ['success' => false, 'errors' => ['Cette communauté est privée. Invitation requise.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO membres_communautes (communaute_id, utilisateur_id, role, statut, date_adhesion, date_modification)
             VALUES (:cid, :uid, :role, :statut, NOW(), NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'role' => 'membre',
            'statut' => 'actif',
        ]);

        return ['success' => true];
    }

    /**
     * Quitter une communauté
     */
    public function quitter(int $communauteId, int $utilisateurId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId, 'statut' => 'actif']);
        $membre = $stmt->fetch();

        if (!$membre) {
            return ['success' => false, 'errors' => ['Vous n\'êtes pas membre de cette communauté.']];
        }

        if ($membre['role'] === 'proprietaire') {
            return ['success' => false, 'errors' => ['Le propriétaire ne peut pas quitter sa propre communauté.']];
        }

        $stmt = $this->db->prepare(
            'UPDATE membres_communautes SET statut = :statut, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute(['statut' => 'inactif', 'id' => $membre['id']]);

        return ['success' => true];
    }

    /**
     * Lister les membres d'une communauté
     */
    public function lister(int $communauteId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT mc.*, u.prenom, u.nom, u.avatar, u.identifiant, u.biographie
             FROM membres_communautes mc
             JOIN utilisateurs u ON u.id = mc.utilisateur_id
             WHERE mc.communaute_id = :cid AND mc.statut = :statut
             ORDER BY mc.date_adhesion ASC
             LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':statut', 'actif');
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Modifier le rôle d'un membre
     */
    public function modifierRole(int $communauteId, int $membreId, string $nouveauRole): array
    {
        $rolesValides = ['membre', 'moderateur', 'administrateur'];
        if (!in_array($nouveauRole, $rolesValides)) {
            return ['success' => false, 'errors' => ['Rôle invalide.']];
        }

        $stmt = $this->db->prepare(
            'UPDATE membres_communautes SET role = :role, date_modification = NOW() WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['role' => $nouveauRole, 'id' => $membreId, 'cid' => $communauteId]);

        return ['success' => true];
    }
}
