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
        $userId = Session::get('utilisateur_id');

        // Upload photo profil
        $photoProfil = null;
        if (!empty($_FILES['photo_profil']['tmp_name']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
            $storage = new \App\Services\StorageService();
            $photoProfil = $storage->stocker($_FILES['photo_profil'], (int)$userId, 'profil');
        }

        // Supprimer photo profil
        if (!empty($_POST['supprimer_photo'])) {
            $photoProfil = '';
        }

        $sql = 'UPDATE utilisateurs SET prenom = :prenom, nom = :nom, biographie = :bio, whatsapp = :whatsapp, date_modification = NOW()';
        $params = [
            'prenom' => trim($_POST['prenom'] ?? ''),
            'nom' => trim($_POST['nom'] ?? ''),
            'bio' => trim($_POST['biographie'] ?? ''),
            'whatsapp' => trim($_POST['whatsapp'] ?? '') ?: null,
            'id' => $userId,
        ];

        if ($photoProfil !== null) {
            $sql .= ', photo_profil = :photo';
            $params['photo'] = $photoProfil;
        }

        $sql .= ' WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        // Mettre à jour la session
        if ($photoProfil !== null) {
            Session::set('utilisateur_avatar', $photoProfil ?: $_SESSION['utilisateur_avatar'] ?? null);
        }

        Session::flash('success', 'Profil mis à jour.');
        return $this->redirect("/c/{$slug}/profil");
    }

    public function changerMotDePasse(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $ancienMdp = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveauMdp = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

        if ($nouveauMdp !== $confirmation) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            return $this->redirect("/c/{$slug}/profil");
        }

        $authService = new \App\Services\AuthService();
        $resultat = $authService->changerMotDePasse(Session::get('utilisateur_id'), $ancienMdp, $nouveauMdp);

        if ($resultat['success']) {
            Session::flash('success', 'Mot de passe changé avec succès.');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors du changement.');
        }

        return $this->redirect("/c/{$slug}/profil");
    }
}
