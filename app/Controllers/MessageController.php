<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\CommunauteService;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function index(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);

        if (!$communaute) {
            return $this->view('errors.404', [], 404);
        }

        $messageService = new MessageService();
        $conversations = $messageService->listerConversations($communaute['id'], Session::get('utilisateur_id'));

        // Lister les membres pour le formulaire de nouvelle conversation
        $membreService = new \App\Services\MembreCommunauteService();
        $membres = $membreService->lister($communaute['id']);

        return $this->viewCommunity('messages.index', [
            'communaute' => $communaute,
            'conversations' => $conversations,
            'membres' => $membres,
            'titre' => 'Messages',
        ]);
    }

    public function creerConversation(string $slug): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $participants = $_POST['participants'] ?? [];
        $contenu = trim($_POST['premier_message'] ?? '');

        if (empty($participants) || empty($contenu)) {
            Session::flash('error', 'Sélectionnez un membre et écrivez un message.');
            return $this->redirect("/c/{$slug}/messages");
        }

        $messageService = new MessageService();
        $resultat = $messageService->creerConversation($communaute['id'], Session::get('utilisateur_id'), $participants);

        if ($resultat['success']) {
            // Envoyer le premier message
            $messageService->envoyer($communaute['id'], $resultat['conversation_id'], Session::get('utilisateur_id'), $contenu);
            Session::flash('success', 'Conversation créée !');
        } else {
            Session::flash('error', $resultat['errors'][0] ?? 'Erreur.');
        }

        return $this->redirect("/c/{$slug}/messages");
    }

    public function envoyer(string $slug, string $conversation): Response
    {
        $communauteService = new CommunauteService();
        $communaute = $communauteService->recupererParSlug($slug);
        if (!$communaute) return $this->redirect('/app');

        $contenu = trim($_POST['contenu'] ?? '');
        if (empty($contenu)) {
            return $this->redirect("/c/{$slug}/messages");
        }

        $messageService = new MessageService();
        $resultat = $messageService->envoyer(
            $communaute['id'],
            (int)$conversation,
            Session::get('utilisateur_id'),
            $contenu
        );

        if ((new \App\Core\Request())->isAjax()) {
            return $this->json($resultat);
        }

        return $this->redirect("/c/{$slug}/messages");
    }
}
