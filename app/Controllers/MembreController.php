<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\MembreCommunauteService;

class MembreController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $membreService = new MembreCommunauteService();
        $membres = $membreService->lister($communaute['id']);

        return $this->viewCommunity('membres.index', [
            'communaute' => $communaute,
            'membres' => $membres,
            'titre' => 'Membres',
        ]);
    }

    public function profil(string $slug, string $identifiant): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        // Récupérer l'utilisateur par identifiant
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE identifiant = :identifiant');
        $stmt->execute(['identifiant' => $identifiant]);
        $membre = $stmt->fetch();

        if (!$membre) {
            return $this->view('errors.404', [], 404);
        }

        return $this->viewCommunity('membres.profil', [
            'communaute' => $communaute,
            'membre' => $membre,
            'titre' => "{$membre['prenom']} {$membre['nom']}",
        ]);
    }
}
