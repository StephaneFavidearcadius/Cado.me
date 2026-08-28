<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\CommunauteService;

class AccueilController extends Controller
{
    public function index(): Response
    {
        // Si connecté, rediriger vers le dashboard
        if (Session::get('utilisateur_id')) {
            return $this->redirect('/app');
        }

        $communauteService = new CommunauteService();
        $communautes = $communauteService->recupererPubliques(1, 6);

        return Response::html(View::make('public.accueil', [
            'communautes' => $communautes,
            'titre' => 'Bienvenue sur Cado.me',
        ]));
    }

    public function decouvrir(): Response
    {
        $estConnecte = !empty(Session::get('utilisateur_id'));

        $communauteService = new CommunauteService();
        $communautes = $communauteService->recupererPubliques(1, 24);

        return Response::html(View::make('public.decouvrir', [
            'communautes' => $communautes,
            'estConnecte' => $estConnecte,
            'titre' => 'Découvrir les communautés',
        ]));
    }
}
