<?php

namespace App\Services;

use App\Core\Database;

class NotificationService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une notification
     */
    public function creer(int $communauteId, int $utilisateurId, string $type, string $titre, string $message, ?string $lien = null): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO notifications (communaute_id, utilisateur_id, type, titre, message, lien, lue, date_creation)
             VALUES (:cid, :uid, :type, :titre, :message, :lien, 0, NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
            'lien' => $lien,
        ]);
    }

    /**
     * Récupérer les notifications d'un utilisateur dans une communauté
     */
    public function lister(int $communauteId, int $utilisateurId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT * FROM notifications
             WHERE communaute_id = :cid AND utilisateur_id = :uid
             ORDER BY date_creation DESC
             LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':uid', $utilisateurId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Compter les notifications non lues
     */
    public function compterNonLues(int $communauteId, int $utilisateurId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM notifications WHERE communaute_id = :cid AND utilisateur_id = :uid AND lue = 0'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId]);
        return (int) $stmt->fetch()['c'];
    }

    /**
     * Marquer comme lu
     */
    public function marquerLu(int $communauteId, int $notificationId, int $utilisateurId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE notifications SET lue = 1 WHERE id = :id AND communaute_id = :cid AND utilisateur_id = :uid'
        );
        $stmt->execute(['id' => $notificationId, 'cid' => $communauteId, 'uid' => $utilisateurId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Marquer toutes comme lues
     */
    public function marquerToutLu(int $communauteId, int $utilisateurId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE notifications SET lue = 1 WHERE communaute_id = :cid AND utilisateur_id = :uid AND lue = 0'
        );
        $stmt->execute(['cid' => $communauteId, 'uid' => $utilisateurId]);
        return true;
    }
}
