<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\AuthService;

class AdminAuthController extends Controller
{
    public function formulaireConnexion(): Response
    {
        $content = View::make('admin.connexion');
        $html = View::make('layouts.admin_login', [
            'titre' => 'Connexion administration',
            'slot' => $content,
        ]);

        return Response::html($html);
    }

    public function connecter(): Response
    {
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $authService = new AuthService();
        $resultat = $authService->connecter($email, $motDePasse);

        if ($resultat['success']) {
            // Verifier que l'utilisateur est super administrateur
            $role = Session::get('role_plateforme');
            if ($role !== 'super_administrateur') {
                $authService->deconnecter();
                Session::flash('error', 'Acces reserve aux super administrateurs.');
                return $this->redirect('/admin/connexion');
            }

            Session::flash('success', 'Bienvenue dans l\'administration.');
            return $this->redirect('/admin');
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur de connexion.');
        Session::set('old', $_POST);
        return $this->redirect('/admin/connexion');
    }

    public function deconnecter(): Response
    {
        $authService = new AuthService();
        $authService->deconnecter();
        return $this->redirect('/admin/connexion');
    }
}
