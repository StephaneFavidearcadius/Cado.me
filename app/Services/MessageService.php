<?php

namespace App\Services;

use App\Core\Database;

class MessageService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une conversation
     */
    public function creerConversation(int $communauteId, int $utilisateurId, array $participantsIds): array
    {
        try {
            $this->db->beginTransaction();

            // Créer la conversation
            $stmt = $this->db->prepare(
                'INSERT INTO conversations (communaute_id, date_creation, date_modification) VALUES (:cid, NOW(), NOW())'
            );
            $stmt->execute(['cid' => $communauteId]);
            $conversationId = $this->db->lastInsertId();

            // Ajouter le créateur
            $stmt = $this->db->prepare(
                'INSERT INTO participants_conversations (conversation_id, communaute_id, utilisateur_id, date_creation) VALUES (:conv_id, :cid, :uid, NOW())'
            );
            $stmt->execute(['conv_id' => $conversationId, 'cid' => $communauteId, 'uid' => $utilisateurId]);

            // Ajouter les autres participants
            foreach ($participantsIds as $pid) {
                if ((int)$pid !== $utilisateurId) {
                    // Vérifier qu'ils sont membres de la communauté
                    $check = $this->db->prepare(
                        'SELECT id FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
                    );
                    $check->execute(['cid' => $communauteId, 'uid' => $pid, 'statut' => 'actif']);

                    if ($check->fetch()) {
                        $addStmt = $this->db->prepare(
                            'INSERT IGNORE INTO participants_conversations (conversation_id, communaute_id, utilisateur_id, date_creation) VALUES (:conv_id, :cid, :uid, NOW())'
                        );
                        $addStmt->execute(['conv_id' => $conversationId, 'cid' => $communauteId, 'uid' => $pid]);
                    }
                }
            }

            $this->db->commit();
            return ['success' => true, 'conversation_id' => $conversationId];
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'errors' => ['Erreur lors de la création de la conversation.']];
        }
    }

    /**
     * Envoyer un message
     */
    public function envoyer(int $communauteId, int $conversationId, int $utilisateurId, string $contenu): array
    {
        $contenu = trim($contenu);
        if (empty($contenu)) {
            return ['success' => false, 'errors' => ['Le message ne peut pas être vide.']];
        }

        // Vérifier que la conversation appartient à la communauté
        $stmt = $this->db->prepare(
            'SELECT id FROM conversations WHERE id = :cid AND communaute_id = :comm_id'
        );
        $stmt->execute(['cid' => $conversationId, 'comm_id' => $communauteId]);

        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Conversation introuvable.']];
        }

        // Vérifier que l'utilisateur est participant
        $stmt = $this->db->prepare(
            'SELECT conversation_id FROM participants_conversations WHERE conversation_id = :cid AND utilisateur_id = :uid AND communaute_id = :comm_id'
        );
        $stmt->execute(['cid' => $conversationId, 'uid' => $utilisateurId, 'comm_id' => $communauteId]);

        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Vous n\'êtes pas participant à cette conversation.']];
        }

        // Envoyer
        $stmt = $this->db->prepare(
            'INSERT INTO messages (communaute_id, conversation_id, utilisateur_id, contenu, lu, date_creation)
             VALUES (:comm_id, :conv_id, :uid, :contenu, 0, NOW())'
        );
        $stmt->execute([
            'comm_id' => $communauteId,
            'conv_id' => $conversationId,
            'uid' => $utilisateurId,
            'contenu' => htmlspecialchars($contenu),
        ]);

        // Mettre à jour la date de modification
        $stmt = $this->db->prepare(
            'UPDATE conversations SET date_modification = NOW() WHERE id = :cid'
        );
        $stmt->execute(['cid' => $conversationId]);

        return ['success' => true, 'message_id' => $this->db->lastInsertId()];
    }

    /**
     * Lister les conversations d'un utilisateur dans une communauté
     */
    public function listerConversations(int $communauteId, int $utilisateurId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, 
                    (SELECT contenu FROM messages WHERE conversation_id = c.id AND communaute_id = c.communaute_id ORDER BY date_creation DESC LIMIT 1) as dernier_message,
                    (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND communaute_id = c.communaute_id AND lu = 0 AND utilisateur_id != :uid) as non_lus
             FROM conversations c
             JOIN participants_conversations pc ON pc.conversation_id = c.id AND pc.utilisateur_id = :uid2 AND pc.communaute_id = c.communaute_id
             WHERE c.communaute_id = :cid
             ORDER BY c.date_modification DESC'
        );

        $stmt->execute([
            'cid' => $communauteId,
            'uid' => $utilisateurId,
            'uid2' => $utilisateurId,
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Lister les participants d'une conversation
     */
    public function listerParticipants(int $conversationId, int $communauteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT pc.utilisateur_id, u.prenom, u.nom, u.avatar, u.email
             FROM participants_conversations pc
             JOIN utilisateurs u ON u.id = pc.utilisateur_id
             WHERE pc.conversation_id = :cid AND pc.communaute_id = :comm_id'
        );
        $stmt->execute(['cid' => $conversationId, 'comm_id' => $communauteId]);
        return $stmt->fetchAll();
    }

    /**
     * Lister les messages d'une conversation
     */
    public function listerMessages(int $conversationId, int $communauteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, u.prenom, u.nom, u.avatar
             FROM messages m
             JOIN utilisateurs u ON u.id = m.utilisateur_id
             WHERE m.conversation_id = :cid AND m.communaute_id = :comm_id
             ORDER BY m.date_creation ASC'
        );
        $stmt->execute(['cid' => $conversationId, 'comm_id' => $communauteId]);
        return $stmt->fetchAll();
    }

    /**
     * Envoyer un message avec média (image, vidéo, fichier)
     */
    public function envoyerAvecMedia(int $communauteId, int $conversationId, int $utilisateurId, string $contenu, array $fichiers = []): array
    {
        $contenu = trim($contenu);
        if (empty($contenu) && empty($fichiers)) {
            return ['success' => false, 'errors' => ['Le message ne peut pas être vide.']];
        }

        // Vérifier que l'utilisateur est participant
        $stmt = $this->db->prepare(
            'SELECT conversation_id FROM participants_conversations WHERE conversation_id = :cid AND utilisateur_id = :uid AND communaute_id = :comm_id'
        );
        $stmt->execute(['cid' => $conversationId, 'uid' => $utilisateurId, 'comm_id' => $communauteId]);
        if (!$stmt->fetch()) {
            return ['success' => false, 'errors' => ['Vous n\'êtes pas participant à cette conversation.']];
        }

        // Envoyer le message
        $stmt = $this->db->prepare(
            'INSERT INTO messages (communaute_id, conversation_id, utilisateur_id, contenu, lu, date_creation)
             VALUES (:comm_id, :conv_id, :uid, :contenu, 0, NOW())'
        );
        $stmt->execute([
            'comm_id' => $communauteId,
            'conv_id' => $conversationId,
            'uid' => $utilisateurId,
            'contenu' => htmlspecialchars($contenu),
        ]);
        $messageId = $this->db->lastInsertId();

        // Sauvegarder les fichiers
        $storage = new StorageService();
        foreach ($fichiers as $fichier) {
            if ($fichier['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
                $typeMedia = 'fichier';
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $typeMedia = 'image';
                elseif (in_array($ext, ['mp4', 'webm', 'mov'])) $typeMedia = 'video';

                $dossier = "uploads/{$communauteId}/messages/{$conversationId}";
                $nomUnique = uniqid() . '.' . $ext;
                $cheminRelatif = $dossier . '/' . $nomUnique;
                $cheminComplet = dirname(__DIR__, 2) . '/public/' . $cheminRelatif;

                @mkdir(dirname($cheminComplet), 0755, true);
                if (move_uploaded_file($fichier['tmp_name'], $cheminComplet)) {
                    $ins = $this->db->prepare(
                        'INSERT INTO medias_messages (message_id, communaute_id, type_media, nom_original, chemin, taille, date_creation)
                         VALUES (:mid, :cid, :type, :nom, :chemin, :taille, NOW())'
                    );
                    $ins->execute([
                        'mid' => $messageId,
                        'cid' => $communauteId,
                        'type' => $typeMedia,
                        'nom' => $fichier['name'],
                        'chemin' => $cheminRelatif,
                        'taille' => $fichier['size'],
                    ]);
                }
            }
        }

        // Mettre à jour la date de modification
        $this->db->prepare('UPDATE conversations SET date_modification = NOW() WHERE id = :cid')->execute(['cid' => $conversationId]);

        return ['success' => true, 'message_id' => $messageId];
    }

    /**
     * Trouver une conversation existante entre 2 membres
     */
    public function trouverConversation(int $communauteId, int $userId1, int $userId2): ?int
    {
        $stmt = $this->db->prepare(
            'SELECT pc1.conversation_id
             FROM participants_conversations pc1
             JOIN participants_conversations pc2 ON pc2.conversation_id = pc1.conversation_id AND pc2.utilisateur_id = :uid2
             WHERE pc1.utilisateur_id = :uid1 AND pc1.communaute_id = :cid'
        );
        $stmt->execute(['uid1' => $userId1, 'uid2' => $userId2, 'cid' => $communauteId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['conversation_id'] : null;
    }

    /**
     * Marquer tous les messages d'une conversation comme lus
     */
    public function marquerCommeLus(int $conversationId, int $communauteId, int $utilisateurId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE messages SET lu = 1 WHERE conversation_id = :cid AND communaute_id = :comm_id AND utilisateur_id != :uid AND lu = 0'
        );
        $stmt->execute(['cid' => $conversationId, 'comm_id' => $communauteId, 'uid' => $utilisateurId]);
    }
}
