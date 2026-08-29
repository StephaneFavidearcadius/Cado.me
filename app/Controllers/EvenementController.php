<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Services\CommunauteService;
use App\Services\EvenementService;

class EvenementController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $evenementService = new EvenementService();
        $evenements = $evenementService->lister($communaute['id']);

        return $this->viewCommunity('evenements.index', [
            'communaute' => $communaute,
            'evenements' => $evenements,
            'titre' => 'Événements',
        ]);
    }

    public function calendrier(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $evenementService = new EvenementService();
        $evenements = $evenementService->lister($communaute['id']);

        return $this->viewCommunity('evenements.calendrier', [
            'communaute' => $communaute,
            'evenements' => $evenements,
            'titre' => 'Calendrier',
        ]);
    }

    public function creer(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $evenementService = new EvenementService();
        $resultat = $evenementService->creer($communaute['id'], $_POST);

        if ($resultat['success']) {
            \App\Core\Session::flash('success', 'Événement créé !');
        } else {
            \App\Core\Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/evenements");
    }
}
