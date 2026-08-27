<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;

class ContexteCommunauteService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Résoudre la communauté courante depuis un slug
     */
    public function resoudre(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM communautes WHERE slug = :slug AND statut = :statut');
        $stmt->execute(['slug' => $slug, 'statut' => 'active']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Vérifier qu'un utilisateur est membre d'une communauté
     */
    public function estMembre(int $communauteId, int $utilisateurId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId, 'statut' => 'actif']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Vérifier qu'un utilisateur a un rôle suffisant dans une communauté
     */
    public function aLeRole(int $communauteId, int $utilisateurId, array $roles): bool
    {
        $membre = $this->estMembre($communauteId, $utilisateurId);
        return $membre && in_array($membre['role'], $roles);
    }

    /**
     * Vérifier qu'un membre appartient à la communauté
     */
    public function membreAppartientA(int $communauteId, int $membreId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $membreId, 'statut' => 'actif']);
        return (int) $stmt->fetch()['c'] > 0;
    }

    /**
     * Récupérer le rôle d'un utilisateur dans une communauté
     */
    public function getRole(int $communauteId, int $utilisateurId): ?string
    {
        $membre = $this->estMembre($communauteId, $utilisateurId);
        return $membre ? $membre['role'] : null;
    }
}
