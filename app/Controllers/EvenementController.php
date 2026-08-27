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

        return $this->view('evenements.index', [
            'communaute' => $communaute,
            'evenements' => $evenements,
            'titre' => 'Événements',
        ]);
    }
}
