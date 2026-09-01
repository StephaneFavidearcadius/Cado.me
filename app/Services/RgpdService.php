<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;

class RgpdService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Exporter toutes les données d'un utilisateur (RGPD)
     */
    public function exporterDonnees(int $utilisateurId): array
    {
        $donnees = [];

        // 1. Profil
        $stmt = $this->db->prepare('SELECT prenom, nom, identifiant, email, avatar, biographie, whatsapp, role_plateforme, date_creation FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $utilisateurId]);
        $donnees['profil'] = $stmt->fetch();

        // 2. Communautés
        $stmt = $this->db->prepare(
            'SELECT c.nom, c.slug, mc.role, mc.date_adhesion
             FROM membres_communautes mc JOIN communautes c ON c.id = mc.communaute_id
             WHERE mc.utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['communautes'] = $stmt->fetchAll();

        // 3. Publications
        $stmt = $this->db->prepare(
            'SELECT p.contenu, p.type, p.date_creation, c.nom as communaute
             FROM publications p JOIN communautes c ON c.id = p.communaute_id
             WHERE p.utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['publications'] = $stmt->fetchAll();

        // 4. Commentaires
        $stmt = $this->db->prepare(
            'SELECT contenu, date_creation FROM commentaires WHERE utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['commentaires'] = $stmt->fetchAll();

        // 5. Likes
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as total FROM likes_publications WHERE utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['statistiques']['likes'] = (int) $stmt->fetch()['total'];

        // 6. Messages
        $stmt = $this->db->prepare(
            'SELECT contenu, date_creation FROM messages WHERE utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['messages'] = $stmt->fetchAll();

        // 7. Progression formations
        $stmt = $this->db->prepare(
            'SELECT l.titre as lecon, f.titre as formation, pf.terminee, pf.date_completion
             FROM progression_formations pf
             JOIN lecons l ON l.id = pf.lecon_id
             JOIN formations f ON f.id = l.formation_id
             WHERE pf.utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['progression_formations'] = $stmt->fetchAll();

        // 8. Signalements
        $stmt = $this->db->prepare(
            'SELECT motif, statut, date_creation FROM signalements WHERE utilisateur_id = :uid'
        );
        $stmt->execute(['uid' => $utilisateurId]);
        $donnees['signalements'] = $stmt->fetchAll();

        $donnees['export_date'] = date('Y-m-d H:i:s');
        $donnees['plateforme'] = 'Cado.me';

        return $donnees;
    }

    /**
     * Générer un fichier JSON exportable
     */
    public function genererFichierExport(int $utilisateurId): ?string
    {
        $donnees = $this->exporterDonnees($utilisateurId);
        $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $chemin = sys_get_temp_dir() . "/cado_me_export_{$utilisateurId}_" . time() . ".json";
        file_put_contents($chemin, $json);

        return $chemin;
    }

    /**
     * Supprimer le compte d'un utilisateur (soft + anonymisation)
     */
    public function supprimerCompte(int $utilisateurId, string $motDePasse): array
    {
        // Vérifier le mot de passe
        $stmt = $this->db->prepare('SELECT mot_de_passe, email FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => $utilisateurId]);
        $utilisateur = $stmt->fetch();

        if (!$utilisateur || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return ['success' => false, 'errors' => ['Le mot de passe est incorrect.']];
        }

        try {
            $this->db->beginTransaction();

            $audit = new AuditService();

            // 1. Quitter toutes les communautés (sauf propriétés)
            $stmt = $this->db->prepare(
                'UPDATE membres_communautes SET statut = :statut WHERE utilisateur_id = :uid AND role != :role'
            );
            $stmt->execute(['statut' => 'inactif', 'uid' => $utilisateurId, 'role' => 'proprietaire']);

            // 2. Vérifier s'il est propriétaire d'une communauté
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) as c FROM communautes WHERE proprietaire_id = :uid AND statut = :statut'
            );
            $stmt->execute(['uid' => $utilisateurId, 'statut' => 'active']);
            $nbProprietaire = (int) $stmt->fetch()['c'];

            if ($nbProprietaire > 0) {
                $this->db->rollBack();
                return ['success' => false, 'errors' => [
                    "Vous êtes propriétaire de {$nbProprietaire} communauté(s). Transférez la propriété avant de supprimer votre compte."
                ]];
            }

            // 3. Supprimer les données personnelles
            $stmt = $this->db->prepare('DELETE FROM likes_publications WHERE utilisateur_id = :uid');
            $stmt->execute(['uid' => $utilisateurId]);

            $stmt = $this->db->prepare('DELETE FROM favoris_publications WHERE utilisateur_id = :uid');
            $stmt->execute(['uid' => $utilisateurId]);

            $stmt = $this->db->prepare('DELETE FROM progression_formations WHERE utilisateur_id = :uid');
            $stmt->execute(['uid' => $utilisateurId]);

            $stmt = $this->db->prepare('DELETE FROM notifications WHERE utilisateur_id = :uid');
            $stmt->execute(['uid' => $utilisateurId]);

            // 4. Anonymiser le compte au lieu de le supprimer
            $anonymeEmail = 'supprime_' . bin2hex(random_bytes(8)) . '@deleted.cado.me';
            $stmt = $this->db->prepare(
                "UPDATE utilisateurs SET
                    prenom = 'Compte',
                    nom = 'Supprimé',
                    identifiant = :identifiant,
                    email = :email,
                    mot_de_passe = '',
                    avatar = NULL,
                    biographie = NULL,
                    whatsapp = NULL,
                    statut = 'inactif',
                    date_modification = NOW()
                 WHERE id = :id"
            );
            $stmt->execute([
                'identifiant' => 'supprime_' . $utilisateurId,
                'email' => $anonymeEmail,
                'id' => $utilisateurId,
            ]);

            $audit->supprimer('utilisateur', $utilisateurId);

            $this->db->commit();

            // Déconnecter
            Session::clear();
            Session::destroy();

            return ['success' => true];
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'errors' => ['Erreur lors de la suppression.']]; 
        }
    }
}
