<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\FormationService;

class FormationController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $formationService = new FormationService();
        $formations = $formationService->lister($communaute['id']);

        return $this->viewCommunity('formations.index', [
            'communaute' => $communaute,
            'formations' => $formations,
            'titre' => 'Formations',
        ]);
    }

    public function classroom(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $formationService = new FormationService();
        $formations = $formationService->lister($communaute['id']);

        return $this->viewCommunity('formations.classroom', [
            'communaute' => $communaute,
            'formations' => $formations,
            'titre' => 'Classe',
        ]);
    }

    public function detail(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);

        if (!$formationData) {
            return $this->view('errors.404', [], 404);
        }

        $lecons = $formationService->listerLecons($communaute['id'], $formationData['id']);

        return $this->viewCommunity('formations.detail', [
            'communaute' => $communaute,
            'formation' => $formationData,
            'lecons' => $lecons,
            'titre' => $formationData['titre'],
        ]);
    }

    public function creer(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $resultat = $formationService->creer($communaute['id'], $_POST);

        if ($resultat['success']) {
            \App\Core\Session::flash('success', 'Formation créée !');
        } else {
            \App\Core\Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations");
    }

    public function ajouterLecon(string $slug, string $formation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $formationService = new FormationService();
        $formationData = $formationService->recupererParSlug($communaute['id'], $formation);
        if (!$formationData) return $this->redirect("/c/{$slug}/formations");

        $resultat = $formationService->ajouterLecon($communaute['id'], $formationData['id'], $_POST);

        if ($resultat['success']) {
            \App\Core\Session::flash('success', 'Leçon ajoutée !');
        } else {
            \App\Core\Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/formations/{$formation}");
    }
}
