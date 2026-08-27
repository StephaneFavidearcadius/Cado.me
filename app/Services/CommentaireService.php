<?php

namespace App\Services;

use App\Core\Database;

class CommentaireService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ajouter un commentaire
     */
    public function ajouter(int $communauteId, int $publicationId, int $utilisateurId, string $contenu, ?int $parentId = null): array
    {
        $contenu = trim($contenu);
        if (empty($contenu)) {
            return ['success' => false, 'errors' => ['Le commentaire ne peut pas être vide.']];
        }

        // Vérifier que la publication appartient à la communauté
        $stmt = $this->db->prepare(
            'SELECT id FROM publications WHERE id = :pid AND communaute_id = :cid AND statut = :statut'
        );
        $stmt->execute(['pid' => $publicationId, 'cid' => $communauteId, 'statut' => 'active']);

        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Publication introuvable.']];
        }

        // Si parentId, vérifier qu'il appartient à la même publication et communauté
        if ($parentId !== null) {
            $stmt = $this->db->prepare(
                'SELECT id FROM commentaires WHERE id = :pid AND publication_id = :pub_id AND communaute_id = :cid'
            );
            $stmt->execute(['pid' => $parentId, 'pub_id' => $publicationId, 'cid' => $communauteId]);

            if (!$stmt->fetch()) {
                return ['success' => false, 'errors' => ['Commentaire parent introuvable.']];
            }
        }

        $stmt = $this->db->prepare(
            'INSERT INTO commentaires (communaute_id, publication_id, utilisateur_id, commentaire_parent_id, contenu, statut, date_creation, date_modification)
             VALUES (:cid, :pid, :uid, :parent_id, :contenu, :statut, NOW(), NOW())'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'pid' => $publicationId,
            'uid' => $utilisateurId,
            'parent_id' => $parentId,
            'contenu' => htmlspecialchars($contenu),
            'statut' => 'actif',
        ]);

        return ['success' => true, 'commentaire_id' => $this->db->lastInsertId()];
    }

    /**
     * Récupérer les commentaires d'une publication
     */
    public function lister(int $communauteId, int $publicationId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            'SELECT c.*, u.prenom, u.nom, u.avatar, u.identifiant
             FROM commentaires c
             JOIN utilisateurs u ON u.id = c.utilisateur_id
             WHERE c.communaute_id = :cid AND c.publication_id = :pid AND c.statut = :statut
             ORDER BY c.date_creation ASC
             LIMIT :limit OFFSET :offset'
        );

        $stmt->bindValue(':cid', $communauteId, \PDO::PARAM_INT);
        $stmt->bindValue(':pid', $publicationId, \PDO::PARAM_INT);
        $stmt->bindValue(':statut', 'actif');
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Supprimer un commentaire
     */
    public function supprimer(int $communauteId, int $commentaireId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE commentaires SET statut = :statut WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['statut' => 'supprime', 'id' => $commentaireId, 'cid' => $communauteId]);
        return $stmt->rowCount() > 0;
    }
}
