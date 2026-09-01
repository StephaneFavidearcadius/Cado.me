<?php

namespace App\Services;

use App\Core\Database;

class SignalementService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Signaler une publication
     */
    public function signalerPublication(int $communauteId, int $utilisateurId, int $publicationId, string $motif): array
    {
        $motif = trim($motif);
        if (empty($motif)) {
            return ['success' => false, 'errors' => ['Le motif est requis.']];
        }

        if (strlen($motif) > 500) {
            return ['success' => false, 'errors' => ['Le motif ne peut pas dépasser 500 caractères.']];
        }

        // Vérifier que la publication existe et appartient à la communauté
        $stmt = $this->db->prepare(
            'SELECT id FROM publications WHERE id = :pid AND communaute_id = :cid AND statut = :statut'
        );
        $stmt->execute(['pid' => $publicationId, 'cid' => $communauteId, 'statut' => 'active']);
        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Publication introuvable.']];
        }

        // Vérifier qu'il n'a pas déjà signalé cette publication
        $stmt = $this->db->prepare(
            'SELECT id FROM signalements WHERE communaute_id = :cid AND publication_id = :pid AND utilisateur_id = :uid AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'pid' => $publicationId, 'uid' => $utilisateurId, 'statut' => 'en_attente']);
        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Vous avez déjà signalé cette publication.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO signalements (communaute_id, utilisateur_id, publication_id, motif, statut, date_creation)
             VALUES (:cid, :uid, :pid, :motif, :statut, NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'pid' => $publicationId,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        return ['success' => true];
    }

    /**
     * Signaler un commentaire
     */
    public function signalerCommentaire(int $communauteId, int $utilisateurId, int $commentaireId, string $motif): array
    {
        $motif = trim($motif);
        if (empty($motif)) {
            return ['success' => false, 'errors' => ['Le motif est requis.']];
        }

        // Vérifier que le commentaire existe
        $stmt = $this->db->prepare(
            'SELECT id FROM commentaires WHERE id = :cid AND communaute_id = :commid AND statut = :statut'
        );
        $stmt->execute(['cid' => $commentaireId, 'commid' => $communauteId, 'statut' => 'actif']);
        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Commentaire introuvable.']];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO signalements (communaute_id, utilisateur_id, commentaire_id, motif, statut, date_creation)
             VALUES (:cid, :uid, :cid_commentaire, :motif, :statut, NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'cid_commentaire' => $commentaireId,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        return ['success' => true];
    }

    /**
     * Lister les signalements d'une communauté
     */
    public function lister(int $communauteId, ?string $filtre = null): array
    {
        $sql = 'SELECT s.*,
                       u.prenom as signalant_prenom, u.nom as signalant_nom,
                       p.contenu as publication_contenu, p.utilisateur_id as publication_auteur_id,
                       c.contenu as commentaire_contenu, c.utilisateur_id as commentaire_auteur_id,
                       up.prenom as pub_auteur_prenom, up.nom as pub_auteur_nom,
                       uc.prenom as com_auteur_prenom, uc.nom as com_auteur_nom
                FROM signalements s
                JOIN utilisateurs u ON u.id = s.utilisateur_id
                LEFT JOIN publications p ON p.id = s.publication_id
                LEFT JOIN commentaires c ON c.id = s.commentaire_id
                LEFT JOIN utilisateurs up ON up.id = p.utilisateur_id
                LEFT JOIN utilisateurs uc ON uc.id = c.utilisateur_id
                WHERE s.communaute_id = :cid';
        $params = ['cid' => $communauteId];

        if ($filtre === 'en_attente') {
            $sql .= ' AND s.statut = :statut';
            $params['statut'] = 'en_attente';
        } elseif ($filtre === 'traite') {
            $sql .= ' AND s.statut = :statut';
            $params['statut'] = 'traite';
        } elseif ($filtre === 'rejete') {
            $sql .= ' AND s.statut = :statut';
            $params['statut'] = 'rejete';
        }

        $sql .= ' ORDER BY s.date_creation DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Traiter un signalement (masquer la cible ou rejeter)
     */
    public function traiter(int $communauteId, int $signalementId, string $decision): array
    {
        if (!in_array($decision, ['traite', 'rejete'])) {
            return ['success' => false, 'errors' => ['Décision invalide.']]; 
        }

        $stmt = $this->db->prepare(
            'SELECT * FROM signalements WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['id' => $signalementId, 'cid' => $communauteId]);
        $signalement = $stmt->fetch();

        if (!$signalement) {
            return ['success' => false, 'errors' => ['Signalement introuvable.']];
        }

        try {
            $this->db->beginTransaction();

            // Si on traite et qu'il y a une cible, la masquer
            if ($decision === 'traite') {
                if ($signalement['publication_id']) {
                    $stmt = $this->db->prepare(
                        'UPDATE publications SET statut = :statut WHERE id = :id AND communaute_id = :cid'
                    );
                    $stmt->execute(['statut' => 'masquee', 'id' => $signalement['publication_id'], 'cid' => $communauteId]);
                } elseif ($signalement['commentaire_id']) {
                    $stmt = $this->db->prepare(
                        'UPDATE commentaires SET statut = :statut WHERE id = :id AND communaute_id = :cid'
                    );
                    $stmt->execute(['statut' => 'masque', 'id' => $signalement['commentaire_id'], 'cid' => $communauteId]);
                }
            }

            // Mettre à jour le statut du signalement
            $stmt = $this->db->prepare(
                'UPDATE signalements SET statut = :statut WHERE id = :id AND communaute_id = :cid'
            );
            $stmt->execute(['statut' => $decision, 'id' => $signalementId, 'cid' => $communauteId]);

            $this->db->commit();
            return ['success' => true];
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'errors' => ['Erreur lors du traitement.']]; 
        }
    }

    /**
     * Compter les signalements en attente
     */
    public function compterEnAttente(int $communauteId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM signalements WHERE communaute_id = :cid AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'statut' => 'en_attente']);
        return (int) $stmt->fetch()['c'];
    }
}
