<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Services\CommunauteService;
use App\Services\RessourceService;

class RessourceController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $ressourceService = new RessourceService();
        $ressources = $ressourceService->lister($communaute['id']);

        return $this->viewCommunity('ressources.index', [
            'communaute' => $communaute,
            'ressources' => $ressources,
            'titre' => 'Ressources',
        ]);
    }

    public function creer(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        // Gérer l'upload si fichier
        $data = $_POST;
        if (!empty($_FILES['fichier']['tmp_name'])) {
            $storage = new \App\Services\StorageService();
            $chemin = $storage->stocker($_FILES['fichier'], (int)$communaute['id'], 'ressources');
            if ($chemin) {
                $data['chemin'] = $chemin;
                $data['nom_fichier'] = $_FILES['fichier']['name'];
                $data['type'] = 'fichier';
            }
        }

        $ressourceService = new RessourceService();
        $resultat = $ressourceService->creer($communaute['id'], $data);

        if ($resultat['success']) {
            \App\Core\Session::flash('success', 'Ressource créée !');
        } else {
            \App\Core\Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/ressources");
    }
}
