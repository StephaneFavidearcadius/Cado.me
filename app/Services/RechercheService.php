<?php

namespace App\Services;

use App\Core\Database;

class RechercheService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Recherche globale dans une communauté
     */
    public function rechercher(int $communauteId, string $requete, int $limite = 20): array
    {
        $requete = trim($requete);
        if (strlen($requete) < 2) {
            return [];
        }

        $motCle = "%{$requete}%";
        $resultats = [];

        // 1. Publications
        $stmt = $this->db->prepare(
            "SELECT p.id, p.contenu, p.date_creation, u.prenom, u.nom, 'publication' as type
             FROM publications p
             JOIN utilisateurs u ON u.id = p.utilisateur_id
             WHERE p.communaute_id = :cid AND p.statut = 'active'
               AND (p.contenu LIKE :mot)
             ORDER BY p.date_creation DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':mot', $motCle);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        $resultats = array_merge($resultats, $stmt->fetchAll());

        // 2. Membres
        $stmt = $this->db->prepare(
            "SELECT u.id, u.prenom, u.nom, u.identifiant, u.avatar, 'membre' as type
             FROM membres_communautes mc
             JOIN utilisateurs u ON u.id = mc.utilisateur_id
             WHERE mc.communaute_id = :cid AND mc.statut = 'actif'
               AND (u.prenom LIKE :mot1 OR u.nom LIKE :mot2 OR u.identifiant LIKE :mot3)
             LIMIT :limite"
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':mot1', $motCle);
        $stmt->bindValue(':mot2', $motCle);
        $stmt->bindValue(':mot3', $motCle);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        $resultats = array_merge($resultats, $stmt->fetchAll());

        // 3. Formations
        $stmt = $this->db->prepare(
            "SELECT f.id, f.titre, f.slug, f.description, 'formation' as type
             FROM formations f
             WHERE f.communaute_id = :cid AND f.statut != 'supprimee'
               AND (f.titre LIKE :mot OR f.description LIKE :mot2)
             LIMIT :limite"
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':mot', $motCle);
        $stmt->bindValue(':mot2', $motCle);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        $resultats = array_merge($resultats, $stmt->fetchAll());

        // 4. Ressources
        $stmt = $this->db->prepare(
            "SELECT r.id, r.titre, r.description, 'ressource' as type
             FROM ressources r
             WHERE r.communaute_id = :cid AND r.statut = 'active'
               AND (r.titre LIKE :mot OR r.description LIKE :mot2)
             LIMIT :limite"
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':mot', $motCle);
        $stmt->bindValue(':mot2', $motCle);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        $resultats = array_merge($resultats, $stmt->fetchAll());

        // 5. Événements
        $stmt = $this->db->prepare(
            "SELECT e.id, e.titre, e.slug, e.description, e.date_debut, 'evenement' as type
             FROM evenements e
             WHERE e.communaute_id = :cid AND e.statut = 'active'
               AND (e.titre LIKE :mot OR e.description LIKE :mot2)
             LIMIT :limite"
        );
        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':mot', $motCle);
        $stmt->bindValue(':mot2', $motCle);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();
        $resultats = array_merge($resultats, $stmt->fetchAll());

        // Trier par date de création décroissante
        usort($resultats, function ($a, $b) {
            $dateA = $a['date_creation'] ?? $a['date_debut'] ?? '0000';
            $dateB = $b['date_creation'] ?? $b['date_debut'] ?? '0000';
            return strtotime($dateB) - strtotime($dateA);
        });

        return array_slice($resultats, 0, $limite);
    }
}
