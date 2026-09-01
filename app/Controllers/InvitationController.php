<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\InvitationService;

class InvitationController extends Controller
{
    /**
     * Page de gestion des invitations d'une communauté
     */
    public function index(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $invitationService = new InvitationService();
        $filtre = $_GET['filtre'] ?? null;

        $invitations = $invitationService->lister($communaute['id'], $filtre);
        $nbEnAttente = $invitationService->compterEnAttente($communaute['id']);

        return $this->viewCommunity('invitations.index', [
            'communaute' => $communaute,
            'invitations' => $invitations,
            'nbEnAttente' => $nbEnAttente,
            'filtre' => $filtre,
            'titre' => "Invitations - {$communaute['nom']}",
        ]);
    }

    /**
     * Envoyer une invitation
     */
    public function envoyer(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'membre';

        $invitationService = new InvitationService();
        $resultat = $invitationService->envoyer($communaute['id'], $email, $role);

        if ($resultat['success']) {
            // Envoyer l'email d'invitation
            $emailService = new \App\Services\EmailService();
            $emailService->envoyerInvitation(
                $email,
                $communaute['nom'],
                $communaute['slug'],
                $resultat['token']
            );

            Session::flash('success', "Invitation envoyée à {$email} !");
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de l\'envoi de l\'invitation.');
        }

        return $this->redirect("/c/{$slug}/gestion/invitations");
    }

    /**
     * Envoyer plusieurs invitations en une fois
     */
    public function envoyerEnMasse(string $slug): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $emailsRaw = $_POST['emails'] ?? '';
        $role = $_POST['role'] ?? 'membre';

        // Séparer les emails par virgule, espace ou saut de ligne
        $emails = preg_split('/[\s,;]+/', $emailsRaw);
        $emails = array_filter(array_map('trim', $emails), fn($e) => !empty($e));

        if (empty($emails)) {
            Session::flash('error', 'Veuillez saisir au moins une adresse email.');
            return $this->redirect("/c/{$slug}/gestion/invitations");
        }

        $invitationService = new InvitationService();
        $emailService = new \App\Services\EmailService();
        $resultats = $invitationService->envoyerEnMasse($communaute['id'], $emails, $role);

        // Envoyer les emails pour chaque succès
        foreach ($emails as $email) {
            // Récupérer le token de la dernière invitation créée
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare(
                'SELECT token FROM invitations_communautes
                 WHERE communaute_id = :cid AND email = :email AND acceptee IS NULL
                 ORDER BY date_creation DESC LIMIT 1'
            );
            $stmt->execute(['cid' => $communaute['id'], 'email' => strtolower(trim($email))]);
            $inv = $stmt->fetch();

            if ($inv) {
                $emailService->envoyerInvitation(
                    $email,
                    $communaute['nom'],
                    $communaute['slug'],
                    $inv['token']
                );
            }
        }

        if ($resultats['succes'] > 0) {
            $msg = "{$resultats['succes']} invitation(s) envoyée(s).";
            if ($resultats['echecs'] > 0) {
                $msg .= " {$resultats['echecs']} échec(s).";
            }
            Session::flash('success', $msg);
        } else {
            $erreur = $resultats['erreurs'][0] ?? 'Aucune invitation envoyée.';
            Session::flash('error', $erreur);
        }

        return $this->redirect("/c/{$slug}/gestion/invitations");
    }

    /**
     * Supprimer / annuler une invitation
     */
    public function supprimer(string $slug, string $id): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $invitationService = new InvitationService();
        $supprime = $invitationService->supprimer($communaute['id'], (int) $id);

        if ($supprime) {
            Session::flash('success', 'Invitation supprimée.');
        } else {
            Session::flash('error', 'Invitation introuvable.');
        }

        return $this->redirect("/c/{$slug}/gestion/invitations");
    }

    /**
     * Renvoyer une invitation
     */
    public function renvoyer(string $slug, string $id): Response
    {
        $communauteService = new \App\Services\CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->redirect('/app');
        }

        $invitationService = new InvitationService();
        $resultat = $invitationService->renvoyer($communaute['id'], (int) $id);

        if ($resultat['success']) {
            // Récupérer l'email pour renvoyer
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare('SELECT email FROM invitations_communautes WHERE id = :id');
            $stmt->execute(['id' => (int) $id]);
            $inv = $stmt->fetch();

            if ($inv) {
                $emailService = new \App\Services\EmailService();
                $emailService->envoyerInvitation(
                    $inv['email'],
                    $communaute['nom'],
                    $communaute['slug'],
                    $resultat['token']
                );
            }

            Session::flash('success', 'Invitation renvoyée.');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors du renvoi.');
        }

        return $this->redirect("/c/{$slug}/gestion/invitations");
    }

    /**
     * Page publique d'acceptation d'invitation
     */
    public function accepterPage(string $token): Response
    {
        $invitationService = new InvitationService();
        $invitation = $invitationService->recupererParToken($token);

        if (!$invitation) {
            Session::flash('error', 'Cette invitation n\'existe pas.');
            return $this->redirect('/');
        }

        if ($invitation['acceptee'] !== null) {
            Session::flash('info', 'Cette invitation a déjà été traitée.');
            return $this->redirect("/c/{$invitation['communaute_slug']}");
        }

        if (strtotime($invitation['expire_le']) < time()) {
            Session::flash('error', 'Cette invitation a expiré.');
            return $this->redirect('/');
        }

        // Si l'utilisateur n'est pas connecté, le rediriger vers l'inscription/connexion
        if (!Session::has('utilisateur_id')) {
            Session::set('invitation_token', $token);
            Session::flash('info', 'Connectez-vous ou créez un compte pour accepter l\'invitation.');
            return $this->redirect('/connexion');
        }

        return $this->view('invitations.accepter', [
            'invitation' => $invitation,
            'token' => $token,
            'titre' => 'Accepter l\'invitation',
        ]);
    }

    /**
     * Traiter l'acceptation d'invitation
     */
    public function accepter(string $token): Response
    {
        if (!Session::has('utilisateur_id')) {
            Session::set('invitation_token', $token);
            return $this->redirect('/connexion');
        }

        $invitationService = new InvitationService();
        $resultat = $invitationService->accepter($token, Session::get('utilisateur_id'));

        if ($resultat['success']) {
            // Recharger les communautés en session
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare(
                'SELECT mc.*, c.nom, c.slug, c.logo, c.couleur_principale
                 FROM membres_communautes mc
                 JOIN communautes c ON c.id = mc.communaute_id
                 WHERE mc.utilisateur_id = :uid AND mc.statut = :statut
                 ORDER BY mc.date_adhesion DESC'
            );
            $stmt->execute(['uid' => Session::get('utilisateur_id'), 'statut' => 'actif']);
            Session::set('mes_communautes', $stmt->fetchAll());

            Session::flash('success', "Vous avez rejoint la communauté {$resultat['communaute_nom']} !");
            return $this->redirect("/c/{$resultat['communaute_slug']}/app");
        }

        Session::flash('error', $resultat['errors'][0] ?? 'Erreur lors de l\'acceptation.');
        return $this->redirect('/');
    }

    /**
     * Refuser une invitation
     */
    public function refuser(string $token): Response
    {
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare(
            'UPDATE invitations_communautes SET acceptee = 0 WHERE token = :token AND acceptee IS NULL'
        );
        $stmt->execute(['token' => $token]);

        Session::flash('info', 'Invitation refusée.');
        return $this->redirect('/');
    }
}
