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
            Session::flash('success', 'Votre communauté a été créée avec succès !');
            return $this->redirect("/c/{$resultat['slug']}/app");
        }

        Session::flash('error', 'Veuillez corriger les erreurs.');
        Session::set('errors', $resultat['errors']);
        Session::set('old', $_POST);
        return $this->redirect('/app/communautes/creer');
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

        return $this->view('communaute.publique', [
            'communaute' => $communaute,
            'estConnecte' => !empty(Session::get('utilisateur_id')),
            'titre' => $communaute['nom'],
        ]);
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

    public function modifierParametres(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $resultat = $communauteService->modifier($communaute['id'], $_POST);

        if ($resultat['success']) {
            Session::flash('success', 'Paramètres mis à jour.');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de la mise à jour.');
        }

        return $this->redirect("/c/{$slug}/gestion/parametres");
    }
}
