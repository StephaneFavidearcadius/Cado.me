<?php

namespace App\Services;

use App\Core\Database;

class InvitationService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Envoyer une invitation à un email
     */
    public function envoyer(int $communauteId, string $email, string $role = 'membre', int $expireJours = 7): array
    {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'errors' => ['Adresse email invalide.']];
        }

        // Vérifier que le rôle est valide
        $rolesValides = ['membre', 'moderateur', 'administrateur'];
        if (!in_array($role, $rolesValides)) {
            return ['success' => false, 'errors' => ['Rôle invalide.']];
        }

        // Vérifier que l'utilisateur n'est pas déjà membre
        $stmt = $this->db->prepare(
            'SELECT id FROM membres_communautes
             WHERE communaute_id = :cid AND utilisateur_id = (
                 SELECT id FROM utilisateurs WHERE email = :email LIMIT 1
             ) AND statut = :statut'
        );
        $stmt->execute(['cid' => $communauteId, 'email' => $email, 'statut' => 'actif']);
        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Cet utilisateur est déjà membre de la communauté.']];
        }

        // Vérifier qu'il n'y a pas déjà une invitation en cours
        $stmt = $this->db->prepare(
            'SELECT id FROM invitations_communautes
             WHERE communaute_id = :cid AND email = :email AND acceptee IS NULL AND expire_le > NOW()'
        );
        $stmt->execute(['cid' => $communauteId, 'email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Une invitation est déjà en cours pour cette adresse.']];
        }

        // Vérifier la limite de membres du plan
        $limiteService = new LimitePlanService();
        if (!$limiteService->estAutorise($communauteId, 'membres')) {
            return ['success' => false, 'errors' => ['La limite de membres de votre plan a été atteinte.']];
        }

        // Générer le token
        $token = bin2hex(random_bytes(32));

        // Créer l'invitation
        $stmt = $this->db->prepare(
            'INSERT INTO invitations_communautes (communaute_id, email, token, role, expire_le, date_creation)
             VALUES (:cid, :email, :token, :role, DATE_ADD(NOW(), INTERVAL :expire DAY), NOW())'
        );
        $stmt->execute([
            'cid' => $communauteId,
            'email' => $email,
            'token' => $token,
            'role' => $role,
            'expire' => $expireJours,
        ]);

        return ['success' => true, 'token' => $token];
    }

    /**
     * Envoyer plusieurs invitations en une fois
     */
    public function envoyerEnMasse(int $communauteId, array $emails, string $role = 'membre'): array
    {
        $resultats = ['succes' => 0, 'echecs' => 0, 'erreurs' => []];

        foreach ($emails as $email) {
            $email = strtolower(trim($email));
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $resultats['echecs']++;
                $resultats['erreurs'][] = "{$email} : email invalide";
                continue;
            }

            $resultat = $this->envoyer($communauteId, $email, $role);
            if ($resultat['success']) {
                $resultats['succes']++;
            } else {
                $resultats['echecs']++;
                $resultats['erreurs'][] = "{$email} : " . ($resultat['errors'][0] ?? 'Erreur inconnue');
            }
        }

        return $resultats;
    }

    /**
     * Lister les invitations d'une communauté
     */
    public function lister(int $communauteId, ?string $filtre = null): array
    {
        $sql = 'SELECT * FROM invitations_communautes WHERE communaute_id = :cid';
        $params = ['cid' => $communauteId];

        if ($filtre === 'en_attente') {
            $sql .= ' AND acceptee IS NULL AND expire_le > NOW()';
        } elseif ($filtre === 'acceptee') {
            $sql .= ' AND acceptee = 1';
        } elseif ($filtre === 'expiree') {
            $sql .= ' AND acceptee IS NULL AND expire_le <= NOW()';
        } elseif ($filtre === ' refusee') {
            $sql .= ' AND acceptee = 0';
        }

        $sql .= ' ORDER BY date_creation DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une invitation par son token
     */
    public function recupererParToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT i.*, c.nom as communaute_nom, c.slug as communaute_slug, c.logo, c.description as communaute_description, c.couleur_principale
             FROM invitations_communautes i
             JOIN communautes c ON c.id = i.communaute_id
             WHERE i.token = :token'
        );
        $stmt->execute(['token' => $token]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Accepter une invitation
     */
    public function accepter(string $token, int $utilisateurId): array
    {
        $invitation = $this->recupererParToken($token);

        if (!$invitation) {
            return ['success' => false, 'errors' => ['Invitation introuvable.']];
        }

        if ($invitation['acceptee'] !== null) {
            return ['success' => false, 'errors' => ['Cette invitation a déjà été traitée.']];
        }

        if (strtotime($invitation['expire_le']) < time()) {
            return ['success' => false, 'errors' => ['Cette invitation a expiré.']];
        }

        // Vérifier que l'email de l'utilisateur correspond
        $stmt = $this->db->prepare('SELECT email FROM utilisateurs WHERE id = :uid');
        $stmt->execute(['uid' => $utilisateurId]);
        $utilisateur = $stmt->fetch();

        if (!$utilisateur || strtolower($utilisateur['email']) !== strtolower($invitation['email'])) {
            return ['success' => false, 'errors' => ['Cette invitation n\'est pas destinée à votre compte.']];
        }

        // Vérifier qu'il n'est pas déjà membre
        $stmt = $this->db->prepare(
            'SELECT id FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut'
        );
        $stmt->execute(['cid' => $invitation['communaute_id'], 'uid' => $utilisateurId, 'statut' => 'actif']);
        if ($stmt->fetch()) {
            return ['success' => false, 'errors' => ['Vous êtes déjà membre de cette communauté.']];
        }

        try {
            $this->db->beginTransaction();

            // Ajouter le membre
            $stmt = $this->db->prepare(
                'INSERT INTO membres_communautes (communaute_id, utilisateur_id, role, statut, date_adhesion, date_modification)
                 VALUES (:cid, :uid, :role, :statut, NOW(), NOW())'
            );
            $stmt->execute([
                'cid' => $invitation['communaute_id'],
                'uid' => $utilisateurId,
                'role' => $invitation['role'],
                'statut' => 'actif',
            ]);

            // Marquer l'invitation comme acceptée
            $stmt = $this->db->prepare(
                'UPDATE invitations_communautes SET acceptee = 1 WHERE id = :id'
            );
            $stmt->execute(['id' => $invitation['id']]);

            $this->db->commit();

            return [
                'success' => true,
                'communaute_slug' => $invitation['communaute_slug'],
                'communaute_nom' => $invitation['communaute_nom'],
            ];
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'errors' => ['Erreur lors de l\'acceptation de l\'invitation.']];
        }
    }

    /**
     * Supprimer / annuler une invitation
     */
    public function supprimer(int $communauteId, int $invitationId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM invitations_communautes WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['id' => $invitationId, 'cid' => $communauteId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Renvoyer une invitation (regénérer le token et prolonger)
     */
    public function renvoyer(int $communauteId, int $invitationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM invitations_communautes WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['id' => $invitationId, 'cid' => $communauteId]);
        $invitation = $stmt->fetch();

        if (!$invitation) {
            return ['success' => false, 'errors' => ['Invitation introuvable.']];
        }

        $nouveauToken = bin2hex(random_bytes(32));

        $stmt = $this->db->prepare(
            'UPDATE invitations_communautes
             SET token = :token, expire_le = DATE_ADD(NOW(), INTERVAL 7 DAY), acceptee = NULL
             WHERE id = :id AND communaute_id = :cid'
        );
        $stmt->execute(['token' => $nouveauToken, 'id' => $invitationId, 'cid' => $communauteId]);

        return ['success' => true, 'token' => $nouveauToken];
    }

    /**
     * Compter les invitations en attente
     */
    public function compterEnAttente(int $communauteId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as c FROM invitations_communautes
             WHERE communaute_id = :cid AND acceptee IS NULL AND expire_le > NOW()'
        );
        $stmt->execute(['cid' => $communauteId]);
        return (int) $stmt->fetch()['c'];
    }

    /**
     * Nettoyer les invitations expirées
     */
    public function nettoyerExpirees(int $communauteId): int
    {
        $stmt = $this->db->prepare(
            'DELETE FROM invitations_communautes
             WHERE communaute_id = :cid AND acceptee IS NULL AND expire_le <= NOW()'
        );
        $stmt->execute(['cid' => $communauteId]);
        return $stmt->rowCount();
    }
}
