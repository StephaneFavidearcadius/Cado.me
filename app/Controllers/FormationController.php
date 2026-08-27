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

        return $this->view('formations.index', [
            'communaute' => $communaute,
            'formations' => $formations,
            'titre' => 'Formations',
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

        return $this->view('formations.detail', [
            'communaute' => $communaute,
            'formation' => $formationData,
            'lecons' => $lecons,
            'titre' => $formationData['titre'],
        ]);
    }
}
