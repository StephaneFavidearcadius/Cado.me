<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;

class AuditService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Enregistrer une action d'audit
     */
    public function enregistrer(
        string $action,
        string $entite,
        ?int $entiteId = null,
        ?int $communauteId = null,
        ?array $donnees = null
    ): void {
        $utilisateurId = Session::get('utilisateur_id');
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        $stmt = $this->db->prepare(
            'INSERT INTO journaux_audit (communaute_id, utilisateur_id, action, entite, entite_id, donnees, adresse_ip, date_creation)
             VALUES (:cid, :uid, :action, :entite, :eid, :donnees, :ip, NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'action' => $action,
            'entite' => $entite,
            'eid' => $entiteId,
            'donnees' => $donnees ? json_encode($donnees) : null,
            'ip' => $ip,
        ]);
    }

    /**
     * Raccourcis pour les actions courantes
     */
    public function creer(string $entite, int $entiteId, ?int $communauteId = null, ?array $donnees = null): void
    {
        $this->enregistrer('creation', $entite, $entiteId, $communauteId, $donnees);
    }

    public function modifier(string $entite, int $entiteId, ?int $communauteId = null, ?array $donnees = null): void
    {
        $this->enregistrer('modification', $entite, $entiteId, $communauteId, $donnees);
    }

    public function supprimer(string $entite, int $entiteId, ?int $communauteId = null): void
    {
        $this->enregistrer('suppression', $entite, $entiteId, $communauteId);
    }

    public function connexion(int $utilisateurId): void
    {
        $this->enregistrer('connexion', 'utilisateur', $utilisateurId);
    }

    public function deconnexion(int $utilisateurId): void
    {
        $this->enregistrer('deconnexion', 'utilisateur', $utilisateurId);
    }

    /**
     * Lister les entrées d'audit pour une communauté
     */
    public function lister(int $communauteId, int $page = 1, int $perPage = 30): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT ja.*, u.prenom, u.nom
             FROM journaux_audit ja
             LEFT JOIN utilisateurs u ON u.id = ja.utilisateur_id
             WHERE ja.communaute_id = :cid
             ORDER BY ja.date_creation DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
