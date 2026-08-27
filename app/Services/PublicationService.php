<?php

namespace App\Services;

use App\Core\Database;

class PublicationService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une publication
     */
    public function creer(int $communauteId, int $utilisateurId, array $data): array
    {
        if (empty($data['contenu']) && empty($data['type'])) {
            return ['success' => false, 'errors' => ['Le contenu est requis.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO publications (communaute_id, utilisateur_id, contenu, type, statut, date_creation, date_modification)
             VALUES (:cid, :uid, :contenu, :type, :statut, NOW(), NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'contenu' => htmlspecialchars(trim($data['contenu'] ?? '')),
            'type' => $data['type'] ?? 'texte',
            'statut' => 'active',
        ]);

        return ['success' => true, 'publication_id' => $this->db->lastInsertId()];
    }

    /**
     * Récupérer le feed d'une communauté
     */
    public function feed(int $communauteId, int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT p.*, u.prenom, u.nom, u.avatar, u.identifiant,
                    (SELECT COUNT(*) FROM commentaires WHERE publication_id = p.id AND statut = :statut_c AND communaute_id = :cid) as nb_commentaires,
                    (SELECT COUNT(*) FROM likes_publications WHERE publication_id = p.id AND communaute_id = :cid) as nb_likes
             FROM publications p
             JOIN utilisateurs u ON u.id = p.utilisateur_id
             WHERE p.communaute_id = :cid AND p.statut = :statut
             ORDER BY p.date_creation DESC
             LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':statut', 'active');
        $stmt->bindValue(':statut_c', 'actif');
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $publications = $stmt->fetchAll();

        // Nombre total
        $countStmt = $this->db->prepare(
            'SELECT COUNT(*) as total FROM publications WHERE communaute_id = :cid AND statut = :statut'
        );
        $countStmt->execute(['cid' => $communauteId, 'statut' => 'active']);
        $total = (int) $countStmt->fetch()['total'];

        return [
            'publications' => $publications,
            'total' => $total,
            'page' => $page,
            'last_page' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    /**
     * Aimer une publication
     */
    public function aimer(int $communauteId, int $publicationId, int $utilisateurId): array
    {
        // Vérifier la publication appartient à la communauté
        if (!$this->publicationAppartientA($communauteId, $publicationId)) {
            return ['success' => false, 'errors' => ['Publication introuvable.']];
        }

        // Vérifier si déjà liké
        $stmt = $this->db->prepare(
            'SELECT id FROM likes_publications WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);

        if ($stmt->fetch()) {
            // Unlike
            $stmt = $this->db->prepare(
                'DELETE FROM likes_publications WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid'
            );
            $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);
            return ['success' => true, 'action' => 'unlike'];
        }

        // Like
        $stmt = $this->db->prepare(
            'INSERT INTO likes_publications (communaute_id, publication_id, utilisateur_id, date_creation) VALUES (:cid, :pid, :uid, NOW())'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId]);
        return ['success' => true, 'action' => 'like'];
    }

    /**
     * Supprimer une publication
     */
    public function supprimer(int $communauteId, int $publicationId): bool
    {
        if (!$this->publicationAppartientA($communauteId, $publicationId)) {
            return false;
        }

        $stmt = $this->db->prepare(
            'UPDATE publications SET statut = :statut WHERE id = :pid AND communaute_id = :cid'
        );
        $stmt->execute(['statut' => 'supprimee', 'pid' => $publicationId, 'cid' => $communauteId]);
        return true;
    }

    /**
     * Vérifier qu'une publication appartient à une communauté
     */
    public function publicationAppartientA(int $communauteId, int $publicationId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM publications WHERE id = :pid AND communaute_id = :cid'
        );
        $stmt->execute(['pid' => $publicationId, 'cid' => $communauteId]);
        return (int) $stmt->fetch()['c'] > 0;
    }
}
