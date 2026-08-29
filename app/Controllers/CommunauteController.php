<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\MembreCommunauteService;

class CommunauteController extends Controller
{
    public function formulaireCreation(): Response
    {
        return $this->view('communaute.creer', [
            'titre' => 'Créer une communauté',
        ]);
    }

    public function creer(): Response
    {
        $communauteService = new CommunauteService();
        $resultat = $communauteService->creer($_POST, Session::get('utilisateur_id'));

        if ($resultat['success']) {
            $communauteId = $resultat['communaute_id'];
            $storage = new \App\Services\StorageService();

            // Upload logo
            if (!empty($_FILES['logo']['tmp_name'])) {
                $chemin = $storage->stocker($_FILES['logo'], (int)$communauteId, 'logo');
                if ($chemin) {
                    $db = \App\Core\Database::getInstance();
                    $stmt = $db->prepare('UPDATE communautes SET logo = :logo WHERE id = :id');
                    $stmt->execute(['logo' => $chemin, 'id' => $communauteId]);
                }
            }

            // Upload cover
            if (!empty($_FILES['image_couverture']['tmp_name'])) {
                $chemin = $storage->stocker($_FILES['image_couverture'], (int)$communauteId, 'couverture');
                if ($chemin) {
                    $db = \App\Core\Database::getInstance();
                    $stmt = $db->prepare('UPDATE communautes SET image_couverture = :cover WHERE id = :id');
                    $stmt->execute(['cover' => $chemin, 'id' => $communauteId]);
                }
            }

            // Recharger la session avec les images
            $this->rechargerCommunautesSession($communauteId);

            Session::flash('success', 'Votre communauté a été créée avec succès !');
            return $this->redirect("/c/{$resultat['slug']}/app");
        }

        Session::flash('error', 'Veuillez corriger les erreurs.');
        Session::set('errors', $resultat['errors']);
        Session::set('old', $_POST);
        return $this->redirect('/app/communautes/creer');
    }

    private function rechargerCommunautesSession(int $communauteId = 0): void
    {
        $db = \App\Core\Database::getInstance();
        $uid = Session::get('utilisateur_id');
        $stmt = $db->prepare(
            'SELECT mc.*, c.nom, c.slug, c.logo, c.couleur_principale, c.image_couverture
             FROM membres_communautes mc
             JOIN communautes c ON c.id = mc.communaute_id
             WHERE mc.utilisateur_id = :uid AND mc.statut = :statut
             ORDER BY mc.date_adhesion DESC'
        );
        $stmt->execute(['uid' => $uid, 'statut' => 'actif']);
        Session::set('mes_communautes', $stmt->fetchAll());

        if ($communauteId > 0) {
            foreach (Session::get('mes_communautes', []) as $comm) {
                if ((int)$comm['communaute_id'] === $communauteId) {
                    Session::set('communaute_courante', $comm);
                    break;
                }
            }
        }
    }

    public function accueil(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        // Si connecté, rediriger vers l'app
        if (Session::has('utilisateur_id')) {
            $membreService = new MembreCommunauteService();
            $membre = $communauteService->recupererParSlug($slug);

            // Vérifier l'appartenance
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut');
            $stmt->execute(['cid' => $communaute['id'], 'uid' => Session::get('utilisateur_id'), 'statut' => 'actif']);

            if ($stmt->fetch()) {
                return $this->redirect("/c/{$slug}/app");
            }
        }

        return \App\Core\Response::html(\App\Core\View::make('communaute.publique', [
            'communaute' => $communaute,
            'estConnecte' => !empty(Session::get('utilisateur_id')),
            'titre' => $communaute['nom'],
        ]));
    }

    public function rejoindre(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $membreService = new MembreCommunauteService();
        $resultat = $membreService->rejoindre($communaute['id'], Session::get('utilisateur_id'));

        if ($resultat['success']) {
            Session::flash('success', 'Vous avez rejoint la communauté !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de l\'adhésion.');
        }

        return $this->redirect("/c/{$slug}/app");
    }

    public function app(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        return $this->viewCommunity('communaute.app', [
            'communaute' => $communaute,
            'titre' => $communaute['nom'],
        ]);
    }

    public function gestion(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        return $this->viewCommunity('communaute.gestion', [
            'communaute' => $communaute,
            'titre' => "Gestion - {$communaute['nom']}",
        ]);
    }

    public function parametres(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        return $this->viewCommunity('communaute.parametres', [
            'communaute' => $communaute,
            'titre' => "Paramètres - {$communaute['nom']}",
        ]);
    }

    public function apropos(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $db = \App\Core\Database::getInstance();
        $storage = new \App\Services\StorageService();

        // Nombre de membres
        $stmt = $db->prepare('SELECT COUNT(*) as total FROM membres_communautes WHERE communaute_id = :cid AND statut = :statut');
        $stmt->execute(['cid' => $communaute['id'], 'statut' => 'actif']);
        $totalMembres = (int) $stmt->fetch()['total'];

        // Nombre d'admins
        $stmt = $db->prepare('SELECT COUNT(*) as total FROM membres_communautes WHERE communaute_id = :cid AND statut = :statut AND role IN ("proprietaire", "administrateur")');
        $stmt->execute(['cid' => $communaute['id'], 'statut' => 'actif']);
        $totalAdmins = (int) $stmt->fetch()['total'];

        // Propriétaire
        $stmt = $db->prepare('SELECT u.prenom, u.nom, u.avatar FROM membres_communautes mc JOIN utilisateurs u ON u.id = mc.utilisateur_id WHERE mc.communaute_id = :cid AND mc.role = :role LIMIT 1');
        $stmt->execute(['cid' => $communaute['id'], 'role' => 'proprietaire']);
        $proprietaire = $stmt->fetch();

        // Derniers membres (pour avatars)
        $stmt = $db->prepare('SELECT u.id, u.prenom, u.nom, u.avatar FROM membres_communautes mc JOIN utilisateurs u ON u.id = mc.utilisateur_id WHERE mc.communaute_id = :cid AND mc.statut = :statut ORDER BY mc.date_adhesion DESC LIMIT 10');
        $stmt->execute(['cid' => $communaute['id'], 'statut' => 'actif']);
        $derniersMembres = $stmt->fetchAll();

        // Est admin ?
        $estAdmin = false;
        if (Session::has('utilisateur_id')) {
            $stmt = $db->prepare('SELECT role FROM membres_communautes WHERE communaute_id = :cid AND utilisateur_id = :uid AND statut = :statut');
            $stmt->execute(['cid' => $communaute['id'], 'uid' => Session::get('utilisateur_id'), 'statut' => 'actif']);
            $role = $stmt->fetch();
            $estAdmin = in_array($role['role'] ?? '', ['proprietaire', 'administrateur']);
        }

        return $this->viewCommunity('communaute.apropos', [
            'communaute' => $communaute,
            'totalMembres' => $totalMembres,
            'totalAdmins' => $totalAdmins,
            'proprietaire' => $proprietaire,
            'derniersMembres' => $derniersMembres,
            'estAdmin' => $estAdmin,
            'titre' => "À propos - {$communaute['nom']}",
        ]);
    }

    public function modifierParametres(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $resultat = $communauteService->modifier($communaute['id'], $_POST);
        $storage = new \App\Services\StorageService();
        $db = \App\Core\Database::getInstance();

        // Upload logo
        if (!empty($_FILES['logo']['tmp_name'])) {
            $chemin = $storage->stocker($_FILES['logo'], (int)$communaute['id'], 'logo');
            if ($chemin) {
                $stmt = $db->prepare('UPDATE communautes SET logo = :logo WHERE id = :id');
                $stmt->execute(['logo' => $chemin, 'id' => $communaute['id']]);
            }
        }

        // Upload cover
        if (!empty($_FILES['image_couverture']['tmp_name'])) {
            $chemin = $storage->stocker($_FILES['image_couverture'], (int)$communaute['id'], 'couverture');
            if ($chemin) {
                $stmt = $db->prepare('UPDATE communautes SET image_couverture = :cover WHERE id = :id');
                $stmt->execute(['cover' => $chemin, 'id' => $communaute['id']]);
            }
        }

        // Recharger la session
        $this->rechargerCommunautesSession((int)$communaute['id']);

        if ($resultat['success']) {
            Session::flash('success', 'Paramètres mis à jour.');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de la mise à jour.');
        }

        return $this->redirect("/c/{$slug}/gestion/parametres");
    }
}
