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
            // Vérifier si un token d'invitation est en attente
            $invitationToken = Session::get('invitation_token');
            if ($invitationToken) {
                Session::forget('invitation_token');
                return $this->redirect("/invitation/{$invitationToken}");
            }

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

            // Vérifier si un token d'invitation est en attente
            $invitationToken = Session::get('invitation_token');
            if ($invitationToken) {
                Session::forget('invitation_token');
                return $this->redirect("/invitation/{$invitationToken}");
            }

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

    // ===== MOT DE PASSE OUBLIÉ =====

    public function formulaireMotDePasseOublie(): Response
    {
        return $this->viewAuth('combined', [
            'titre' => 'Mot de passe oublié',
            'activeTab' => 'reset_request',
        ]);
    }

    public function motDePasseOublie(): Response
    {
        $email = $_POST['email'] ?? '';

        $authService = new AuthService();
        $authService->demanderReset($email);

        // Toujours afficher le même message
        return $this->viewAuth('combined', [
            'titre' => 'Email envoyé',
            'activeTab' => 'reset_sent',
        ]);
    }

    public function formulaireReinitialiser(string $token): Response
    {
        $authService = new AuthService();
        $utilisateur = $authService->verifierTokenReset($token);

        if (!$utilisateur) {
            Session::flash('error', 'Lien de réinitialisation invalide ou expiré.');
            return $this->redirect('/connexion');
        }

        return $this->viewAuth('combined', [
            'titre' => 'Nouveau mot de passe',
            'activeTab' => 'reset_form',
            'reset_token' => $token,
        ]);
    }

    public function reinitialiserMotDePasse(string $token): Response
    {
        $motDePasse = $_POST['mot_de_passe'] ?? '';
        $confirmation = $_POST['mot_de_passe_confirmation'] ?? '';

        if ($motDePasse !== $confirmation) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            return $this->redirect("/reinitialiser-mot-de-passe/{$token}");
        }

        $authService = new AuthService();
        $resultat = $authService->reinitialiserMotDePasse($token, $motDePasse);

        if ($resultat['success']) {
            Session::flash('success', 'Votre mot de passe a été réinitialisé. Connectez-vous.');
            return $this->redirect('/connexion');
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de la réinitialisation.');
        return $this->redirect("/reinitialiser-mot-de-passe/{$token}");
    }

    // ===== VÉRIFICATION EMAIL =====

    public function renvoyerVerification(): Response
    {
        $authService = new AuthService();
        $userId = Session::get('utilisateur_id');
        if ($userId) {
            $authService->envoyerVerificationEmail($userId);
        }
        Session::flash('success', 'Un email de vérification vous a été envoyé.');
        return $this->redirect('/app');
    }

    public function verifierEmail(string $token): Response
    {
        $authService = new AuthService();
        $ok = $authService->verifierEmail($token);

        if ($ok) {
            Session::flash('success', 'Votre adresse email a été vérifiée !');
        } else {
            Session::flash('error', 'Lien de vérification invalide ou déjà utilisé.');
        }

        return $this->redirect('/app');
    }
}
