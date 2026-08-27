<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Services\CommunauteService;

class AccueilController extends Controller
{
    public function index(): Response
    {
        $communauteService = new CommunauteService();
        $communautes = $communauteService->recupererPubliques(1, 6);

        return $this->view('public.accueil', [
            'communautes' => $communautes,
            'titre' => 'Bienvenue sur Cado.me',
        ]);
    }

    public function decouvrir(): Response
    {
        $communauteService = new CommunauteService();
        $communautes = $communauteService->recupererPubliques(1, 24);

        return $this->view('public.decouvrir', [
            'communautes' => $communautes,
            'titre' => 'Découvrir les communautés',
        ]);
    }
}
