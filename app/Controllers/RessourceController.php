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
}
