<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Response;
use App\Core\Session;

class PlateformeController extends Controller
{
    // ===== DASHBOARD =====
    public function dashboard(): Response
    {
        $db = Database::getInstance();

        $stats = [
            'communautes' => (int) $db->query('SELECT COUNT(*) FROM communautes WHERE statut = \'active\'')->fetchColumn(),
            'utilisateurs' => (int) $db->query('SELECT COUNT(*) FROM utilisateurs WHERE statut = \'actif\'')->fetchColumn(),
            'publications' => (int) $db->query('SELECT COUNT(*) FROM publications WHERE statut = \'active\'')->fetchColumn(),
            'formations' => (int) $db->query('SELECT COUNT(*) FROM formations')->fetchColumn(),
            'messages' => (int) $db->query('SELECT COUNT(*) FROM messages')->fetchColumn(),
            'abonnements' => (int) $db->query('SELECT COUNT(*) FROM abonnements WHERE statut = \'actif\'')->fetchColumn(),
            'revenus' => (float) ($db->query('SELECT COALESCE(SUM(p.prix_mensuel), 0) FROM abonnements a JOIN plans p ON p.id = a.plan_id WHERE a.statut = \'actif\'')->fetchColumn() ?? 0),
        ];

        $communautes = $db->query('SELECT c.*, COUNT(mc.id) as nombre_membres FROM communautes c LEFT JOIN membres_communautes mc ON mc.communaute_id = c.id AND mc.statut = \'actif\' GROUP BY c.id ORDER BY c.date_creation DESC LIMIT 10')->fetchAll();

        $derniersUtilisateurs = $db->query('SELECT * FROM utilisateurs ORDER BY date_creation DESC LIMIT 5')->fetchAll();

        $abonnementsRecents = $db->query('SELECT a.*, p.nom as plan_nom, p.prix_mensuel, c.nom as communaute_nom FROM abonnements a JOIN plans p ON p.id = a.plan_id JOIN communautes c ON c.id = a.communaute_id ORDER BY a.date_creation DESC LIMIT 5')->fetchAll();

        return $this->view('plateforme.dashboard', [
            'stats' => $stats,
            'communautes' => $communautes,
            'derniersUtilisateurs' => $derniersUtilisateurs,
            'abonnementsRecents' => $abonnementsRecents,
            'titre' => 'Administration plateforme',
        ]);
    }

    // ===== COMMUNAUTÉS =====
    public function communautes(): Response
    {
        $db = Database::getInstance();
        $communautes = $db->query('SELECT c.*, COUNT(mc.id) as nombre_membres, u.prenom, u.nom as proprietaire_nom FROM communautes c LEFT JOIN membres_communautes mc ON mc.communaute_id = c.id AND mc.statut = \'actif\' JOIN utilisateurs u ON u.id = c.proprietaire_id GROUP BY c.id ORDER BY c.date_creation DESC')->fetchAll();

        return $this->view('plateforme.communautes', [
            'communautes' => $communautes,
            'titre' => 'Gestion des communautés',
        ]);
    }

    public function suspendreCommunaute(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE communautes SET statut = \'suspendue\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Communauté suspendue.');
        return $this->redirect('/admin/communautes');
    }

    public function activerCommunaute(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE communautes SET statut = \'active\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Communauté réactivée.');
        return $this->redirect('/admin/communautes');
    }

    public function supprimerCommunaute(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE communautes SET statut = \'archivee\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Communauté archivée.');
        return $this->redirect('/admin/communautes');
    }

    // ===== UTILISATEURS =====
    public function utilisateurs(): Response
    {
        $db = Database::getInstance();
        $utilisateurs = $db->query('SELECT * FROM utilisateurs ORDER BY date_creation DESC LIMIT 50')->fetchAll();

        return $this->view('plateforme.utilisateurs', [
            'utilisateurs' => $utilisateurs,
            'titre' => 'Gestion des utilisateurs',
        ]);
    }

    public function promouvoirSuperAdmin(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE utilisateurs SET role_plateforme = \'super_administrateur\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Utilisateur promu super administrateur.');
        return $this->redirect('/admin/utilisateurs');
    }

    public function retrograderUtilisateur(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE utilisateurs SET role_plateforme = \'aucun\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Rôle retiré.');
        return $this->redirect('/admin/utilisateurs');
    }

    public function suspendreUtilisateur(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE utilisateurs SET statut = \'suspendu\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Utilisateur suspendu.');
        return $this->redirect('/admin/utilisateurs');
    }

    public function reactiverUtilisateur(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE utilisateurs SET statut = \'actif\', date_modification = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Utilisateur réactivé.');
        return $this->redirect('/admin/utilisateurs');
    }

    // ===== PLANS & ABONNEMENTS =====
    public function plans(): Response
    {
        $db = Database::getInstance();
        $plans = $db->query('SELECT p.*, (SELECT COUNT(*) FROM abonnements a WHERE a.plan_id = p.id AND a.statut = \'actif\') as nb_abonnements FROM plans p ORDER BY p.prix_mensuel ASC')->fetchAll();

        return $this->view('plateforme.plans', [
            'plans' => $plans,
            'titre' => 'Gestion des plans',
        ]);
    }

    public function creerPlan(): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO plans (nom, description, prix_mensuel, prix_annuel, limite_membres, limite_stockage, limite_formations, limite_communautes, actif, date_creation) VALUES (:nom, :description, :prix_mensuel, :prix_annuel, :limite_membres, :limite_stockage, :limite_formations, :limite_communautes, :actif, NOW())');
        $stmt->execute([
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'prix_mensuel' => (float) ($_POST['prix_mensuel'] ?? 0),
            'prix_annuel' => (float) ($_POST['prix_annuel'] ?? 0),
            'limite_membres' => (int) ($_POST['limite_membres'] ?? 50),
            'limite_stockage' => (int) (($_POST['limite_stockage'] ?? 1) * 1073741824),
            'limite_formations' => (int) ($_POST['limite_formations'] ?? 3),
            'limite_communautes' => (int) ($_POST['limite_communautes'] ?? 1),
            'actif' => isset($_POST['actif']) ? 1 : 0,
        ]);

        Session::flash('success', 'Plan créé.');
        return $this->redirect('/admin/plans');
    }

    public function modifierPlan(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE plans SET nom = :nom, description = :description, prix_mensuel = :prix_mensuel, prix_annuel = :prix_annuel, limite_membres = :limite_membres, limite_stockage = :limite_stockage, limite_formations = :limite_formations, limite_communautes = :limite_communautes, actif = :actif WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'prix_mensuel' => (float) ($_POST['prix_mensuel'] ?? 0),
            'prix_annuel' => (float) ($_POST['prix_annuel'] ?? 0),
            'limite_membres' => (int) ($_POST['limite_membres'] ?? 50),
            'limite_stockage' => (int) (($_POST['limite_stockage'] ?? 1) * 1073741824),
            'limite_formations' => (int) ($_POST['limite_formations'] ?? 3),
            'limite_communautes' => (int) ($_POST['limite_communautes'] ?? 1),
            'actif' => isset($_POST['actif']) ? 1 : 0,
        ]);

        Session::flash('success', 'Plan mis à jour.');
        return $this->redirect('/admin/plans');
    }

    public function supprimerPlan(int $id): Response
    {
        $db = Database::getInstance();
        // Only delete if no active subscriptions
        $stmt = $db->prepare('SELECT COUNT(*) FROM abonnements WHERE plan_id = :id AND statut = \'actif\'');
        $stmt->execute(['id' => $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            Session::flash('error', 'Impossible de supprimer un plan avec des abonnements actifs.');
            return $this->redirect('/admin/plans');
        }
        $stmt = $db->prepare('DELETE FROM plans WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Plan supprimé.');
        return $this->redirect('/admin/plans');
    }

    public function abonnements(): Response
    {
        $db = Database::getInstance();
        $abonnements = $db->query('SELECT a.*, p.nom as plan_nom, p.prix_mensuel, c.nom as communaute_nom, c.slug as communaute_slug FROM abonnements a JOIN plans p ON p.id = a.plan_id JOIN communautes c ON c.id = a.communaute_id ORDER BY a.date_creation DESC LIMIT 50')->fetchAll();

        return $this->view('plateforme.abonnements', [
            'abonnements' => $abonnements,
            'titre' => 'Gestion des abonnements',
        ]);
    }

    // ===== MODÉRATION =====
    public function moderation(): Response
    {
        $db = Database::getInstance();

        $publications = $db->query('SELECT p.*, u.prenom, u.nom, c.nom as communaute_nom, c.slug as communaute_slug FROM publications p JOIN utilisateurs u ON u.id = p.utilisateur_id JOIN communautes c ON c.id = p.communaute_id ORDER BY p.date_creation DESC LIMIT 30')->fetchAll();

        $commentaires = $db->query('SELECT cm.*, u.prenom, u.nom, p.id as publication_id FROM commentaires cm JOIN utilisateurs u ON u.id = cm.utilisateur_id JOIN publications p ON p.id = cm.publication_id ORDER BY cm.date_creation DESC LIMIT 30')->fetchAll();

        return $this->view('plateforme.moderation', [
            'publications' => $publications,
            'commentaires' => $commentaires,
            'titre' => 'Modération',
        ]);
    }

    public function supprimerPublication(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE publications SET statut = \'supprimee\' WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Publication supprimée.');
        return $this->redirect('/admin/moderation');
    }

    public function supprimerCommentaire(int $id): Response
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM commentaires WHERE id = :id');
        $stmt->execute(['id' => $id]);
        Session::flash('success', 'Commentaire supprimé.');
        return $this->redirect('/admin/moderation');
    }

    // ===== PARAMÈTRES PLATEFORME =====
    public function parametres(): Response
    {
        $db = Database::getInstance();
        $parametres = $db->query('SELECT * FROM parametres_plateforme WHERE id = 1')->fetch() ?: [];

        return $this->view('plateforme.parametres', [
            'parametres' => $parametres,
            'titre' => 'Paramètres plateforme',
        ]);
    }

    public function sauvegarderParametres(): Response
    {
        $db = Database::getInstance();

        // Check if table exists and has a row
        $existing = $db->query('SELECT id FROM parametres_plateforme WHERE id = 1')->fetch();

        if ($existing) {
            $stmt = $db->prepare('UPDATE parametres_plateforme SET nom_plateforme = :nom, description_plateforme = :desc, email_contact = :email, maintenance = :maintenance, date_modification = NOW() WHERE id = 1');
            $stmt->execute([
                'nom' => trim($_POST['nom_plateforme'] ?? 'Cado.me'),
                'desc' => trim($_POST['description_plateforme'] ?? ''),
                'email' => trim($_POST['email_contact'] ?? ''),
                'maintenance' => isset($_POST['maintenance']) ? 1 : 0,
            ]);
        } else {
            $stmt = $db->prepare('INSERT INTO parametres_plateforme (id, nom_plateforme, description_plateforme, email_contact, maintenance, date_creation, date_modification) VALUES (1, :nom, :desc, :email, :maintenance, NOW(), NOW())');
            $stmt->execute([
                'nom' => trim($_POST['nom_plateforme'] ?? 'Cado.me'),
                'desc' => trim($_POST['description_plateforme'] ?? ''),
                'email' => trim($_POST['email_contact'] ?? ''),
                'maintenance' => isset($_POST['maintenance']) ? 1 : 0,
            ]);
        }

        Session::flash('success', 'Paramètres sauvegardés.');
        return $this->redirect('/admin/parametres');
    }
}
