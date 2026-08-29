<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Response;
use App\Core\Session;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function formulaireConnexion(): Response
    {
        return $this->viewAuth('combined', [
            'titre' => 'Connexion',
            'activeTab' => 'connexion',
        ]);
    }

    public function connecter(): Response
    {
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $authService = new AuthService();
        $resultat = $authService->connecter($email, $motDePasse);

        if ($resultat['success']) {
            Session::flash('success', 'Bienvenue !');
            return $this->redirect('/app');
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur de connexion.');
        Session::set('old', $_POST);
        return $this->redirect('/connexion');
    }

    public function formulaireInscription(): Response
    {
        return $this->viewAuth('combined', [
            'titre' => 'Inscription',
            'activeTab' => 'inscription',
        ]);
    }

    public function inscrire(): Response
    {
        $authService = new AuthService();
        $resultat = $authService->inscrire($_POST);

        if ($resultat['success']) {
            // Connecter automatiquement
            $authService->connecter($_POST['email'], $_POST['mot_de_passe']);
            Session::flash('success', 'Votre compte a été créé avec succès !');
            return $this->redirect('/app');
        }

        Session::flash('error', 'Veuillez corriger les erreurs.');
        Session::set('errors', $resultat['errors']);
        Session::set('old', $_POST);
        return $this->redirect('/inscription');
    }

    public function deconnecter(): Response
    {
        $authService = new AuthService();
        $authService->deconnecter();
        return $this->redirect('/');
    }
}
