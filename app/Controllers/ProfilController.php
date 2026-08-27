<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Response;
use App\Core\Session;

class ProfilController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $stmt->execute(['id' => Session::get('utilisateur_id')]);
        $utilisateur = $stmt->fetch();

        return $this->viewCommunity('profil.index', [
            'communaute' => $communaute,
            'utilisateur' => $utilisateur,
            'titre' => 'Mon profil',
        ]);
    }

    public function modifier(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $db = Database::getInstance();
        $stmt = $db->prepare(
            'UPDATE utilisateurs SET prenom = :prenom, nom = :nom, biographie = :bio, date_modification = NOW() WHERE id = :id'
        );
        $stmt->execute([
            'prenom' => htmlspecialchars(trim($_POST['prenom'] ?? '')),
            'nom' => htmlspecialchars(trim($_POST['nom'] ?? '')),
            'bio' => htmlspecialchars(trim($_POST['biographie'] ?? '')),
            'id' => Session::get('utilisateur_id'),
        ]);

        Session::flash('success', 'Profil mis à jour.');
        return $this->redirect("/c/{$slug}/profil");
    }
}
