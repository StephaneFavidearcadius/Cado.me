<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;

class AuthService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Inscrire un nouvel utilisateur
     */
    public function inscrire(array $data): array
    {
        $errors = $this->validerInscription($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $whatsapp = !empty($data['whatsapp']) ? trim($data['whatsapp']) : null;

        $stmt = $this->db->prepare(
            'INSERT INTO utilisateurs (prenom, nom, identifiant, email, mot_de_passe, whatsapp, role_plateforme, statut, email_verifie, date_creation, date_modification)
             VALUES (:prenom, :nom, :identifiant, :email, :mot_de_passe, :whatsapp, :role_plateforme, :statut, :email_verifie, NOW(), NOW())'
        );

        $hash = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt->execute([
            'prenom' => trim($data['prenom']),
            'nom' => trim($data['nom']),
            'identifiant' => $this->genererIdentifiant($data['prenom'], $data['nom']),
            'email' => strtolower(trim($data['email'])),
            'mot_de_passe' => $hash,
            'whatsapp' => $whatsapp,
            'role_plateforme' => 'aucun',
            'statut' => 'actif',
            'email_verifie' => 0,
        ]);

        $utilisateurId = $this->db->lastInsertId();

        // Envoyer email de bienvenue
        $emailService = new EmailService();
        $emailService->envoyerBienvenue($email, trim($data['prenom']));

        // Envoyer email de vérification
        $this->envoyerVerificationEmail($utilisateurId);

        return ['success' => true, 'utilisateur_id' => $utilisateurId];
    }

    /**
     * Connecter un utilisateur
     */
    public function connecter(string $email, string $motDePasse): array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = :email AND statut = :statut');
        $stmt->execute(['email' => strtolower(trim($email)), 'statut' => 'actif']);
        $utilisateur = $stmt->fetch();

        if (!$utilisateur || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return ['success' => false, 'errors' => ['Identifiants incorrects.']];
        }

        Session::regenerate();

        // Journal d'audit
        $audit = new AuditService();
        $audit->connexion($utilisateur['id']);

        Session::set('utilisateur_id', $utilisateur['id']);
        Session::set('utilisateur_prenom', $utilisateur['prenom']);
        Session::set('utilisateur_nom', $utilisateur['nom']);
        Session::set('utilisateur_email', $utilisateur['email']);
        Session::set('utilisateur_avatar', $utilisateur['avatar']);
        Session::set('role_plateforme', $utilisateur['role_plateforme']);

        // Charger les communautés de l'utilisateur
        $this->chargerCommunautes($utilisateur['id']);

        return ['success' => true, 'utilisateur' => $utilisateur];
    }

    /**
     * Déconnecter un utilisateur
     */
    public function deconnecter(): void
    {
        $userId = Session::get('utilisateur_id');
        if ($userId) {
            $audit = new AuditService();
            $audit->deconnexion($userId);
        }
        Session::clear();
        Session::destroy();
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function estConnecte(): bool
    {
        return Session::has('utilisateur_id');
    }

    // ===== MOT DE PASSE OUBLIÉ =====

    /**
     * Demander la réinitialisation du mot de passe
     */
    public function demanderReset(string $email): array
    {
        $email = strtolower(trim($email));
        $stmt = $this->db->prepare('SELECT id, email FROM utilisateurs WHERE email = :email AND statut = :statut');
        $stmt->execute(['email' => $email, 'statut' => 'actif']);
        $utilisateur = $stmt->fetch();

        // Toujours retourner succès pour ne pas révéler si l'email existe
        if (!$utilisateur) {
            return ['success' => true];
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->db->prepare(
            'UPDATE utilisateurs SET reset_token = :token, reset_expires = :expires, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute(['token' => $token, 'expires' => $expires, 'id' => $utilisateur['id']]);

        // Envoyer l'email
        $emailService = new EmailService();
        $emailService->envoyerResetMotDePasse($email, $token);

        return ['success' => true];
    }

    /**
     * Vérifier un token de réinitialisation
     */
    public function verifierTokenReset(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, prenom, nom, email FROM utilisateurs
             WHERE reset_token = :token AND reset_expires > NOW() AND statut = :statut'
        );
        $stmt->execute(['token' => $token, 'statut' => 'actif']);
        return $stmt->fetch() ?: null;
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function reinitialiserMotDePasse(string $token, string $nouveauMotDePasse): array
    {
        if (strlen($nouveauMotDePasse) < 8) {
            return ['success' => false, 'errors' => ['Le mot de passe doit contenir au moins 8 caractères.']];
        }

        $utilisateur = $this->verifierTokenReset($token);
        if (!$utilisateur) {
            return ['success' => false, 'errors' => ['Lien de réinitialisation invalide ou expiré.']];
        }

        $hash = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $this->db->prepare(
            'UPDATE utilisateurs SET mot_de_passe = :mdp, reset_token = NULL, reset_expires = NULL, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute(['mdp' => $hash, 'id' => $utilisateur['id']]);

        return ['success' => true];
    }

    /**
     * Changer le mot de passe (utilisateur connecté)
     */
    public function changerMotDePasse(int $utilisateurId, string $ancienMdp, string $nouveauMdp): array
    {
        if (strlen($nouveauMdp) < 8) {
            return ['success' => false, 'errors' => ['Le nouveau mot de passe doit contenir au moins 8 caractères.']];
        }

        $stmt = $this->db->prepare('SELECT mot_de_passe FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $utilisateurId]);
        $utilisateur = $stmt->fetch();

        if (!$utilisateur || !password_verify($ancienMdp, $utilisateur['mot_de_passe'])) {
            return ['success' => false, 'errors' => ['Le mot de passe actuel est incorrect.']];
        }

        $hash = password_hash($nouveauMdp, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare('UPDATE utilisateurs SET mot_de_passe = :mdp, date_modification = NOW() WHERE id = :id');
        $stmt->execute(['mdp' => $hash, 'id' => $utilisateurId]);

        return ['success' => true];
    }

    // ===== VÉRIFICATION EMAIL =====

    /**
     * Générer et envoyer un token de vérification d'email
     */
    public function envoyerVerificationEmail(int $utilisateurId): void
    {
        $token = bin2hex(random_bytes(32));

        $stmt = $this->db->prepare(
            'UPDATE utilisateurs SET email_token = :token, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute(['token' => $token, 'id' => $utilisateurId]);

        $stmt = $this->db->prepare('SELECT email FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $utilisateurId]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur) {
            $emailService = new EmailService();
            $emailService->envoyerVerificationEmail($utilisateur['email'], $token);
        }
    }

    /**
     * Vérifier l'email via le token
     */
    public function verifierEmail(string $token): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE utilisateurs SET email_verifie = 1, email_token = NULL, date_modification = NOW()
             WHERE email_token = :token AND email_verifie = 0'
        );
        $stmt->execute(['token' => $token]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Charger les communautés de l'utilisateur en session
     */
    private function chargerCommunautes(int $utilisateurId): void
    {
        $stmt = $this->db->prepare(
            'SELECT mc.*, c.nom, c.slug, c.logo, c.couleur_principale
             FROM membres_communautes mc
             JOIN communautes c ON c.id = mc.communaute_id
             WHERE mc.utilisateur_id = :uid AND mc.statut = :statut
             ORDER BY mc.date_adhesion DESC'
        );
        $stmt->execute(['uid' => $utilisateurId, 'statut' => 'actif']);
        $communautes = $stmt->fetchAll();

        Session::set('mes_communautes', $communautes);

        // Si une seule communauté, la définir comme courante par défaut
        if (count($communautes) === 1 && !Session::has('communaute_courante')) {
            Session::set('communaute_courante', $communautes[0]);
        }
    }

    /**
     * Générer un identifiant unique
     */
    private function genererIdentifiant(string $prenom, string $nom): string
    {
        $base = strtolower(
            preg_replace('/[^a-zA-Z0-9]/', '', $prenom . $nom)
        );

        $identifiant = $base;
        $compteur = 1;

        while ($this->identifiantExiste($identifiant)) {
            $identifiant = $base . $compteur;
            $compteur++;
        }

        return $identifiant;
    }

    private function identifiantExiste(string $identifiant): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as c FROM utilisateurs WHERE identifiant = :id');
        $stmt->execute(['id' => $identifiant]);
        return (int) $stmt->fetch()['c'] > 0;
    }

    private function validerInscription(array $data): array
    {
        $errors = [];

        if (empty($data['prenom']) || strlen(trim($data['prenom'])) < 2) {
            $errors['prenom'] = 'Le prénom doit contenir au moins 2 caractères.';
        }
        if (empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = 'Le nom doit contenir au moins 2 caractères.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) as c FROM utilisateurs WHERE email = :email');
            $stmt->execute(['email' => strtolower(trim($data['email']))]);
            if ((int) $stmt->fetch()['c'] > 0) {
                $errors['email'] = 'Cette adresse email est déjà utilisée.';
            }
        }
        if (empty($data['mot_de_passe']) || strlen($data['mot_de_passe']) < 8) {
            $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if (($data['mot_de_passe'] ?? '') !== ($data['mot_de_passe_confirmation'] ?? '')) {
            $errors['mot_de_passe_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }

        return $errors;
    }
}
