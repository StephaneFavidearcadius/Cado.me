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
                        $stmt->execute(['conv_id' => $conversationId, 'cid' => $communauteId, 'uid' => $pid]);
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
            'SELECT id FROM participants_conversations WHERE conversation_id = :cid AND utilisateur_id = :uid AND communaute_id = :comm_id'
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
}
